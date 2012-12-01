<?php

namespace li3_usermanager\controllers;

use lithium\security\Auth;
use lithium\util\String;
use lithium\net\http\Router;
use li3_usermanager\models\Users;
use li3_usermanager\models\AboutUsers;
use li3_usermanager\models\PasswordResets;
use li3_usermanager\models\UserActivations;
use li3_swiftmailer\mailer\Transports;
use li3_swiftmailer\mailer\Message;

class UsersController extends \li3_usermanager\extensions\controllers\AccessController {

	/**
	 * Predefined redirect routes
	 */
	private $_login = array('library' => 'li3_usermanager', 'Session::create');
	private $_index = array('library' => 'li3_usermanager', 'Users::index');

	/**
	 * Setup template paths
	 */
	protected function _init() {
		$this->_render['paths'] = array(
			'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
			'layout'   => LITHIUM_APP_PATH . '/views/layouts/default.html.php',
			'element'  => LITHIUM_APP_PATH . '/views/elements/{:template}.html.php'
		);
		parent::_init();
		$this->response->cache(false);
	}

	/**
	 * Index
	 * @requestLogin
	 */
	public function index() {
		$this->_rejectNotLogged();
		return array('auth' => $this->_auth, 'session' => \lithium\storage\Session::read());
	}

	/**
	 * Register new account
	 */
	public function add() {
		$this->_rejectLogged();
		$user = null;
		if ($this->request->data) {
			$user = Users::create($this->request->data);
			if ($user->save()) {
				AboutUsers::create(array('user_id' => $user->id))->save();
				if (LI3_UM_RequireUserActivation) {
					$token = String::hash(mt_rand(100000, 999999) . md5($user->email), array(
						'salt' => LI3_UM_TokenSalt,
						'type' => 'sha256'
					));
					UserActivations::create(array(
						'user_id' => $user->id,
						'token' => $token
					))->save();
					$link = Router::match(array(
						'library' => 'li3_usermanager',
						'Users::activate',
						'username' => $user->username,
						'token' => $token
					), $this->request, array('absolute' => true));
					$mailer = Transports::adapter('default');
					$message = Message::newInstance()
						->setSubject('Your activation link!')
						->setFrom(LI3_UM_ActivationEmailFrom)
						->setTo(array($user->email))
						->setBody('This is your activation link: ' . $link);
					$mailer->send($message);
				}
				return $this->redirect($this->_login);
			}
		}
		return compact('user');
	}

	/**
	 * Activate your account with token
	 */
	public function activate() {
		$this->_rejectLogged();
		$token = $this->request->params['token'];
		$username = $this->request->params['username'];
		$user = null;
		$activation = null;
		if ($token && $username) {
			$user = Users::first(array(
				'conditions' => array('username' => $username, 'active' => 0)
			));
			if ($user) {
				$activation = UserActivations::first(array(
					'conditions' => array('user_id' => $user->id, 'token' => $token)
				));
				if ($activation) {
					$user->active = 1;
					if ($user->save()) {
						$activation->delete();
					}
				}
			}
		}
		return $this->redirect($this->_index);
	}

	/**
	 * Edit details
	 * @requestLogin
	 */
	public function editDetails() {
		$this->_rejectNotLogged();
		$details = AboutUsers::first(array('conditions' => array('user_id' => $this->_auth['id'])));
		if ($this->request->data) {
			if ($details->save($this->request->data)) {
				return $this->redirect($this->_index);
			}
		}
		return compact('details');
	}

	/**
	 * Change email
	 * @requestLogin
	 */
	public function changeEmail() {
		$this->_rejectNotLogged();
		$user = Users::first(array('conditions' => $this->_auth));
		if ($this->request->data) {
			if ($user->save(
				array('email' => $this->request->data['email']),
				array('events' => array('change_email'))
			)) {
				Auth::set('default', array('email' => $user->email) + $this->_auth);
				return $this->redirect($this->_index);
			}
		}
		return compact('user');
	}

	/**
	 * Change password
	 * @requestLogin
	 */
	public function changePassword() {
		$this->_rejectNotLogged();
		$user = Users::first(array('conditions' => $this->_auth));
		if ($this->request->data) {
			if ($user->save(
				array(
					'old_password' => $this->request->data['old_password'],
					'password' => $this->request->data['password'],
					'confirm_password' => $this->request->data['confirm_password']
				), array(
					'events' => 'change_password'
				)
			)) {
				return $this->redirect($this->_index);
			}
		}
		return compact('user');
	}

	/**
	 * Request password reset
	 * @todo Enable custom expires configuration
	 * @todo Make better token generator
	 */
	public function requestResetPassword() {
		$this->_rejectLogged();
		$emailSent = false;
		$message = null;
		if ($this->request->data) {
			$user = Users::first(array('conditions' => array(
				'username' => $this->request->data['username'],
				'email' => $this->request->data['email']
			)));
			if ($user) {
				$time = new \DateTime();
				$reset = PasswordResets::first(array('conditions' => array(
					'user_id' => $user->id
				)));
				if ($reset) {
					$expires = new \DateTime($reset->expires);
					if ($expires <= $time) {
						$reset->delete();
					} else {
						$message = 'You already have reset token in your email inbox!';
					}
				}
				if (!$reset || !$reset->exists()) {
					$expires = clone $time;
					$expires->modify('+10 minutes');
					$token = String::hash(mt_rand(100000, 999999) . md5($user->email), array(
						'salt' => LI3_UM_TokenSalt,
						'type' => 'sha256'
					));
					$reset = PasswordResets::create(array(
						'user_id' => $user->id,
						'expires' => $expires->format('Y-m-d H:i:s'),
						'token' => $token
					));
					if ($reset->save()) {
						$link = Router::match(array(
							'library' => 'li3_usermanager',
							'Users::resetPassword',
							'username' => $user->username,
							'token' => $token
						), $this->request, array('absolute' => true));
						$mailer = Transports::adapter('default');
						$message = Message::newInstance()
							->setSubject('Your password reset link!')
							->setFrom(LI3_UM_PasswordResetEmailFrom)
							->setTo(array($user->email))
							->setBody('This is your password reset link: ' . $link);
						$emailSent = $mailer->send($message);
						$message = 'Check your email inbox for reset token.';
					}
				}
			}
		}
		return compact('emailSent', 'message');
	}

	/**
	 * Reset password
	 * @todo Fix old_password validation!
	 */
	public function resetPassword() {
		$this->_rejectLogged();
		$token = $this->request->params['token'];
		$username = $this->request->params['username'];
		$user = null;
		if (!$token || !$username) {
			return $this->redirect($this->_index);
		}
		$time = new \DateTime();
		$expires = clone $time;
		$user = PasswordResets::first(array(
			'conditions' => array(
				'token' => $token,
				'users.username' => $username
			),
			'with' => 'Users'
		));
		if (!$user) {
			return $this->redirect($this->_index);
		}
		$expires->modify($user->expires);
		if ($expires <= $time) {
			$user->delete();
			return $this->redirect($this->_index);
		}
		if ($this->request->data) {
			$user->user->set(array(
				'password' => $this->request->data['password'],
				'confirm_password' => $this->request->data['confirm_password']
			));
			if ($user->user->save(null, array('events' => array('reset_password')))) {
				$user->delete();
				return $this->redirect($this->_login);
			}
		}
		return array('user' => $user->user);
	}

}

?>