<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\models;

use li3_usermanager\extensions\util\Token;
use lithium\security\Password;
use lithium\net\http\Router;

class Users extends \lithium\data\Model {

	public $belongsTo = array('UserGroups');
	public $hasMany = array('PasswordResets');
	public $hasOne = array('AboutUsers', 'UserActivations');

	/**
	 * @var object `lithium\action\Request`
	 */
	public static $request;

	public $validates = array(
		'username' => array(
			array('unique', 'message' => 'Username already in use', 'on' => 'create'),
			array(
				'lengthBetween',
				'min' => 3, 'max' => 10,
				'message' => 'Must be between 3 to 10 characters', 'on' => 'create'
			)
		),
		'old_password' => array(
			'compareWithOldDbValue', 'strategy' => 'password', 'field' => 'password',
			'message' => 'You must enter valid password', 'on' => 'change_password'
		),
		'password' => array('notEmpty', 'message' => 'Password is required'),
		'confirm_password' => array(
			'confirm',
			'message' => 'Field didn\'t match new password',
			'strategy' => 'password',
			'on' => array('create', 'change_password', 'reset_password')
		),
		'email' => array(
			array(
				'unique',
				'message' => 'Email already in use',
				'on' => array('create', 'change_email')
			),
			array(
				'email',
				'message' => 'Valid email is required',
				'on' => array('create', 'change_email')
			)
		)
	);

	/**
	 * Apply proper password hashing (on create or change password)
	 * On create:
	 * 	- apply defined default user group (LI3_UM_DefaultUserGroup)
	 * 	- apply defined active status (LI3_UM_RequireUserActivation)
	 * 	  0 - require user to activate account trough generated link with token
	 *    1 - don't require further actions, account is already activated
	 */
	public static function __init() {
		static::applyFilter('save', function($self, $params, $chain) {
			if ($params['data']) {
				$params['entity']->set($params['data']);
				$params['data'] = array();
			}
			if (!$params['entity']->exists()) {
				if ($params['entity']->password) {
					$params['entity']->password = Password::hash($params['entity']->password);
				}
				$params['entity']->active = 1;
				if (LI3_UM_RequireUserActivation && $self::$request) {
					$params['entity']->active = 0;
				}
				$params['entity']->user_group_id = LI3_UM_DefaultUserGroup;
			} else {
				if ($params['entity']->password && $params['entity']->modified('password')) {
					$params['entity']->password = Password::hash($params['entity']->password);
				}
			}
			return $chain->next($self, $params, $chain);
		});
		static::applyFilter('save', function($self, $params, $chain) {
			if (($save = $chain->next($self, $params, $chain)) &&
				($params['options']['events'] === 'create'))
			{
				$user = $params['entity'];
				AboutUsers::create(array('user_id' => $user->id))->save();
				if (LI3_UM_RequireUserActivation && $self::$request) {
					$token = Token::generate($user->email);
					UserActivations::create(array(
						'user_id' => $user->id, 'token' => $token
					))->save();
					$link = Router::match(
						array(
							'li3_usermanager.Users::activate',
							'id' => $user->id,
							'token' => $token
						),
						$self::$request,
						array('absolute' => true)
					);
					Mailer::$_data['subject'] = 'Your activation link!';
					Mailer::$_data['from'] = LI3_UM_ActivationEmailFrom;
					Mailer::$_data['to'] = $user->email;
					Mailer::$_data['body'] = 'This is your activation link:' . "\n" . $link;
				}
			}
			return $save;
		});
	}

	/**
	 * Helper method to enable `Auth` to fetch user with group
	 */
	public static function firstWithGroup($conditions) {
		$self = static::_object();
		$conditions['with'] = 'UserGroups';
		return $self::find('first', $conditions);
	}

	/**
	 * Logic to request password reset for user
	 *
	 * @param array $conditions
	 * @return int
	 */
	public static function requestPasswordReset(array $conditions = array()) {
		$self = static::_object();
		if ($user = $self::first(compact('conditions'))) {
			$time = new \DateTime();
			$reset = PasswordResets::first(array('conditions' => array('user_id' => $user->id)));
			if ($reset) {
				$expires = new \DateTime($reset->expires);
				if ($expires <= $time) {
					$reset->delete();
				} else {
					return PasswordResets::RESET_TOKEN_EXISTS;
				}
			}
			if (!$reset || !$reset->exists()) {
				$expires = clone $time;
				$expires->modify(LI3_UM_PasswordResetExpires);
				$token = Token::generate($user->email);
				$reset = PasswordResets::create(array(
					'user_id' => $user->id,
					'expires' => $expires->format('Y-m-d H:i:s'),
					'token' => $token
				));
				if ($reset->save()) {
					$link = Router::match(
						array(
							'li3_usermanager.Users::resetPassword',
							'id' => $user->id,
							'token' => $token
						),
						$self::$request,
						array('absolute' => true)
					);
					Mailer::$_data['subject'] = 'Your password reset link!';
					Mailer::$_data['from'] = LI3_UM_PasswordResetEmailFrom;
					Mailer::$_data['to'] = $user->email;
					Mailer::$_data['body'] = 'This is your password reset link:' . "\n" . $link;
					return PasswordResets::GENERATED_NEW_RESET_TOKEN;
				}
			}
		}
	}

}

?>