<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\extensions\controllers;

use li3_backend\extensions\controller\ComponentController;
use lithium\security\Auth;
use lithium\util\Inflector;
use li3_usermanager\extensions\security\AccessDeniedException;
use li3_usermanager\models\UserGroups;
use li3_access\security\Access;

/**
 * `AccessController` extend it if you need to control access in your controller.
 */
class AccessController extends ComponentController {

	/**
	 * Auth `Auth::check('default')` data available as controller's property
	 */
	protected $_user;

	/**
	 * By default anyone access protected area should be treated as guest user.
	 * If he pass authentication this param will be set to `false`.
	 *
	 * @var bool
	 */
	protected $_guest = true;

	/**
	 * Define should controller run DbAcl check or you will use some custom rule
	 * By default anything that inherit from `AccessController` will run DbAcl check,
	 * but you maybe need user and group data, but without DbAcl access control.
	 * If disabled DbAcl you can use Rules adapter check.
	 *
	 * @var bool
	 */
	protected $_acl = true;

	/**
	 * Aro identifier, this will be set if user pass authentication.
	 * For example `'users/members'`
	 *
	 * @var string|null
	 */
	protected $_aro = null;

	/**
	 * Aco identifier and privilege to check against.
	 * For example `'li3_usermanager/controllers/Users'`
	 *
	 * @var string|null
	 */
	protected $_aco = null;

	/**
	 * Import Auth data and fetch user group data
	 */
	protected function _init() {
		parent::_init();
		if ($this->_user = Auth::check('default')) $this->_guest = false;
		if ($this->_acl) {
			$this->_prepareAcl();
			if (!$this->_checkAccess()) {
				throw new AccessDeniedException('You dont\'t have permissions to access here!');
			}
		}
	}

	/**
	 * Prepare `Aco` and `Aro` identifiers to be used for finding Acos and Aros nodes
	 */
	protected function _prepareAcl() {
		$request = $this->request->params;
		$user = $this->_user;
		$guest = $this->_guest;
		$buildAroPath = function($group = null, $path = array()) use(&$buildAroPath) {
			$parent = null;
			$path[] = Inflector::pluralize($group['slug']);
			if ($group['parent_id']) {
				$parent = UserGroups::first(array(
					'conditions' => array('parent_id' => $group['parent_id'])
				));
			}
			if ($parent) {
				return $buildAroPath($parent, $path);
			}
			return join('/',$path);
		};
		$prepareAco = function() use($request) {
			extract($request);
			$library = isset($library) ? $library . '/' : '';
			return $library . 'controllers/' . $controller;
		};
		$prepareAro = function() use($user, $guest, $buildAroPath) {
			if ($guest) return 'guests';
			return 'users/' . $buildAroPath($user['user_group']);
		};
		$this->_aco = $prepareAco();
		$this->_aro = $prepareAro();
	}

	/**
	 * Check Aro's permissions to current Aco
	 *
	 * @return bool
	 */
	protected function _checkAccess() {
		$access = Access::get('acl', $this->_aro, $this->_aco);
		$action = $this->request->params['action'];

		if (is_array($access) &&
			((array_key_exists('*', $access) && $access['*']) ||
			(array_key_exists($action, $access) && $access[$action]))
		) {
			return true;
		}
		return false;
	}

	/**
	 * You can reject any banned user to access with this method.
	 * Call it in your controller `_init()` method to apply it to whole controller or
	 * add it at top of any action that should rejectd banned users!
	 * @throws \li3_usermanager\extensions\security\AccessDeniedException
	 */
	protected function _rejectBanned() {
		if ($this->_user && !$this->_user['group']['active']) {
			throw new AccessDeniedException('You\'ve been banned!');
		}
	}

	/**
	 * Redirect any logged in users from actions or controllers.
	 * You can apply this method on controller (`_init()`) or action level
	 * @param array|string $url `Router::match()` compatible url
	 */
	protected function _rejectLogged($url = array()) {
		!empty($url) || $url = 'li3_usermanager.Users::index';
		if (!$this->_guest) {
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
			'redirect' => 'li3_usermanager.Session::create'
		);
		if ($this->_guest) {
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