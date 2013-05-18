<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\models;

use lithium\security\Password;
use lithium\util\Validator;

class Users extends \lithium\data\Model {

	public $belongsTo = array('UserGroups');
	public $hasMany = array('PasswordResets');
	public $hasOne = array('AboutUsers', 'UserActivations');

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
				$params['entity']->active = (LI3_UM_RequireUserActivation) ? 0 : 1;
				$params['entity']->user_group_id = LI3_UM_DefaultUserGroup;
			} else {
				if ($params['entity']->password && $params['entity']->modified('password')) {
					$params['entity']->password = Password::hash($params['entity']->password);
				}
			}
			return $chain->next($self, $params, $chain);
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

}

?>