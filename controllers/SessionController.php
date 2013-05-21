<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\controllers;

use lithium\security\Auth;

class SessionController extends \li3_backend\extensions\controller\ComponentController {

	protected $_acl = false;

	protected function _init() {
		parent::_init();
		$this->response->cache(false);
	}

	/**
	 * Check posted data against database and create session - log in user
	 */
	public function create() {
		$this->_rejectLogged();
		$this->_viewAs('partial-component');
		$inactive = false;
		if ($this->request->data) {
			if (Auth::check('default', $this->request)) {
				return $this->redirect('li3_usermanager.Users::index');
			} elseif (Auth::check('inactive', $this->request)) {
				$inactive = true;
				Auth::clear('inactive');
			}
		}
		return compact('inactive');
	}

	/**
	 * Destroy session - log out user
	 */
	public function destroy() {
		Auth::clear('default');
		return $this->redirect('li3_usermanager.Session::create');
	}

}

?>