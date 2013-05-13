<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\extensions\controllers;

use li3_usermanager\models\UserGroups;
use lithium\security\Auth;
use li3_usermanager\extensions\security\AccessDeniedException;

/**
 * `AccessController` extend it if you need to control access in your controller.
 */
class AccessController extends \lithium\action\Controller {

	/**
	 * Auth `Auth::check('default')` data available as controller's property
	 */
	protected $_user = null;

	protected function _accessAlias() {
		extract($this->request->params);
		$library = isset($library) ? $library . '/' : '';
		return $library . 'controllers/' . $controller . '/' . $action;
	}

	/**
	 * Import Auth data and fetch user group data
	 */
	protected function _init() {
		parent::_init();
		if($this->_user = Auth::check('default')) {
			$this->_user['group'] = UserGroups::first($this->_user['user_group_id'])->to('array');
		}
	}

	/**
	 * You can reject any banned user to access with this method.
	 * Call it in your controller `_init()` method to apply it to whole controller or
	 * add it at top of any action that should rejectd banned users!
	 * @throws \li3_usermanager\extensions\security\AccessDeniedException
	 */
	protected function _rejectBanned() {
		if ($this->_user && $this->_user['user_group_id'] === 1) {
			throw new AccessDeniedException('You\'ve been banned!');
		}
	}

	/**
	 * Redirect any logged in users from actions or controllers.
	 * You can apply this method on controller (`_init()`) or action level
	 * @param array $url `Router::match()` compatible url
	 */
	protected function _rejectLogged(array $url = array()) {
		$url += array('library' => 'li3_usermanager', 'Users::index');
		if ($this->_user) {
			$this->redirect($url);
		}
	}

	/**
	 * Block access if user not logged in.
	 * You can apply this method on controller (`_init()`) or action level
	 * @param array $options Configure what should occur:
	 * 	`method` redirect|message Redirect to another page or throw exception with error message
	 * 	`message` _string_ Message that will be used if method is `message`
	 * 	`redirect` _array_ `Router::match()` compatible url to redirect if method is `redirect`
	 * @throws \li3_usermanager\extensions\security\AccessDeniedException
	 */
	protected function _rejectNotLogged(array $options = array()) {
		$options += array(
			'method' => 'redirect',
			'message' => 'You don\'t have permissions to access here!',
			'redirect' => array('library' => 'li3_usermanager', 'Session::create')
		);
		if (!$this->_user) {
			switch ($options['method']) {
				case 'redirect':
					return $this->redirect($options['redirect']);
				case 'message':
					throw new AccessDeniedException($options['message']);
					break;
			}
		}
	}

	/**
	 * Configure access just for allowed user groups
	 * You can apply this method on controller (`_init()`) or action level
	 * @param mixed string|array $allow Allowed user groups
	 * @param array $options Configure what should occur:
	 * 	`method` redirect|message Redirect to another page or throw exception with error message
	 * 	`message` _string_ Message that will be used if method is `message`
	 * 	`redirect` _array_ `Router::match()` compatible url to redirect if method is `redirect`
	 * @throws \li3_usermanager\extensions\security\AccessDeniedException
	 */
	protected function _allowGroups($allow = null, array $options = array()) {
		$groups = array(
			1 => 'banned',
			2 => 'member',
			3 => 'manager',
			4 => 'admin',
			5 => 'root'
		);

		$options += array(
			'allowed' => (array) $allow,
			'method' => 'message',
			'message' => 'You don\'t have permissions to access here!',
			'redirect' => array('library' => 'li3_usermanager', 'Users::index')
		);
		$userGroup = ($this->_user['user_group_id']) ? $groups[$this->_user['user_group_id']] : 0;
		if (!$userGroup || !in_array($userGroup, $options['allowed'])) {
			switch ($options['method']) {
				case 'redirect':
					return $this->redirect($options['redirect']);
				case 'message':
					throw new AccessDeniedException($options['message']);
					break;
			}
		}
	}

}

?>