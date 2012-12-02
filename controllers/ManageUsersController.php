<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\controllers;

use li3_usermanager\models\Users;
use li3_usermanager\models\UserGroups;

class ManageUsersController extends \li3_usermanager\extensions\controllers\AccessController {

	protected function _init() {
		$this->_render['paths'] = array(
			'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
			'layout'   => LITHIUM_APP_PATH . '/views/layouts/default.html.php',
			'element'  => LITHIUM_APP_PATH . '/views/elements/{:template}.html.php'
		);
		parent::_init();
		$this->response->cache(false);
		$this->_allowGroups(array('admin', 'root'), array('method' => 'redirect'));
	}

	/**
	 * List all users
	 */
	public function index() {
		$users = Users::all(array('with' => 'UserGroups'));
		return compact('users');
	}

	/**
	 * Promote user to other user group
	 */
	public function promote() {
		if ($id = $this->request->params['id']) {
			$groups = array();
			foreach (UserGroups::all() as $group) {
				$groups[$group->id] = $group->slug;
			}
			$rootId = array_flip($groups);
			$rootId = $rootId['root'];
			unset($groups[$rootId]);
			$user = Users::first(array('conditions' => compact('id')));
			if ($this->request->data && $this->request->data['user_group_id'] != $rootId) {
				$user->user_group_id = $this->request->data['user_group_id'];
				if ($user->save()) {
					return $this->redirect(array(
						'library' => 'li3_usermanager', 'ManageUsers::index'
					));
				}
			}
			if ($user && $user->user_group_id != $rootId) {
				return compact('user', 'groups');
			}
		}
		return $this->redirect(array('library' => 'li3_usermanager', 'ManageUsers::index'));
	}

	/**
	 * @todo Remove activation token if exists!
	 */
	public function activate() {
		$success = false;
		$user = null;
		if ($id = $this->request->params['id']) {
			$user = Users::first(array(
				'conditions' => compact('id')
			));
			if ($user && !$user->active) {
				$user->active = 1;
				$success = $user->save();
			}
		}
		return compact('success', 'user');
	}

	/**
	 * @return array
	 */
	public function deactivate() {
		$success = false;
		$user = null;
		if ($id = $this->request->params['id']) {
			$user = Users::first(array(
				'conditions' => compact('id')
			));
			if ($user && $user->active) {
				$user->active = 0;
				$success = $user->save();
			}
		}
		return compact('success', 'user');
	}

	/**
	 * Destroy user with all related data if not in `root` group
	 */
	public function destroy() {
		$destroyed = false;
		$user = null;
		if ($id = $this->request->params['id']) {
			$user = Users::first(array(
				'conditions' => array('users.id' => $id),
				'with' => array('AboutUsers', 'PasswordResets', 'UserActivations')
			));
			if ($user && $user->user_group_id == 5) {
				$user = null;
			}
			if ($user) {
				$user->about_user->delete();
				$user->user_activation->delete();
				foreach($user->password_resets as $pasword_reset) {
					$pasword_reset->delete();
				}
				$destroyed = $user->delete();
			};
		}
		return compact('destroyed', 'user');
	}

}

?>