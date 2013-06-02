<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */


namespace li3_usermanager\controllers;

use li3_usermanager\models\Mailer;
use li3_usermanager\extensions\controllers\AccessController;
use lithium\security\Auth;
use li3_usermanager\models\Users;
use li3_usermanager\models\AboutUsers;
use li3_usermanager\models\PasswordResets;
use li3_usermanager\models\UserActivations;

class UsersController extends AccessController {

	protected $_viewAs = 'partial-component';

	/**
	 * Setup template paths
	 */
	protected function _init() {
		parent::_init();
		Users::$request = $this->request;
		$this->response->cache(false);
	}

	/**
	 * Index
	 * @requestLogin
	 */
	public function index() {
		$this->_rejectNotLogged();
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
				Mailer::send();
				return $this->redirect('li3_usermanager.Session::create');
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
		$id = $this->request->params['id'];
		$user = null;
		$activation = null;
		if ($token && $id) {
			UserActivations::activate(array('user_id' => $id, 'token' => $token));
		}
		return $this->redirect('li3_usermanager.Session::create');
	}

	/**
	 * Edit details
	 * @requestLogin
	 */
	public function editDetails() {
		$this->_rejectNotLogged();
		$details = AboutUsers::first(array('conditions' => array('user_id' => $this->_user['id'])));
		if ($this->request->data) {
			if ($details->save($this->request->data)) {
				return $this->redirect('li3_usermanager.Users::index');
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
		$user = $this->_user;
		unset($user['user_group']);
		$user = Users::first(array('conditions' => $user));
		if ($this->request->data) {
			if ($user->save(
				array('email' => $this->request->data['email']),
				array('events' => array('change_email'))
			)) {
				Auth::set('default', array('email' => $user->email) + $this->_user);
				return $this->redirect('li3_usermanager.Users::index');
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
		$user = $this->_user;
		unset($user['user_group']);
		$user = Users::first(array('conditions' => $user));
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
				return $this->redirect('li3_usermanager.Users::index');
			}
		}
		return compact('user');
	}

	/**
	 * Request password reset
	 */
	public function requestResetPassword() {
		$this->_rejectLogged();
		$emailSent = false;
		$message = null;
		if ($this->request->data) {
			$requestPasswordReset = Users::requestPasswordReset($this->request->data);
			if ($requestPasswordReset === PasswordResets::RESET_TOKEN_EXISTS) {
				$message = 'You already have reset token in your email inbox!';
			}
			if ($requestPasswordReset === PasswordResets::GENERATED_NEW_RESET_TOKEN) {
				$message = 'Check your email inbox for reset token.';
			}
			$emailSent = Mailer::send();
		}
		return compact('emailSent', 'message');
	}

	/**
	 * Reset password
	 */
	public function resetPassword() {
		$this->_rejectLogged();
		$token = $this->request->params['token'];
		$id = $this->request->params['id'];
		if (!$token || !$id) {
			return $this->redirect('li3_usermanager.Session::create');
		}
		if (!$reset = PasswordResets::getResetUser(array('user_id' => $id, 'token' => $token))) {
			return $this->redirect('li3_usermanager.Session::create');
		}

		if ($this->request->data) {
			$reset->user->set(array(
				'password' => $this->request->data['password'],
				'confirm_password' => $this->request->data['confirm_password']
			));
			if ($reset->user->save(null, array('events' => array('reset_password')))) {
				$reset->delete();
				return $this->redirect('li3_usermanager.Session::create');
			}
		}
		return array('user' => $reset->user);
	}

}

?>