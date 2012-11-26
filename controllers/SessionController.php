<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\controllers;

use lithium\security\Auth;

class SessionController extends \li3_usermanager\extensions\controllers\AccessController{

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
	 * Check posted data against database and create session - log in user
	 */
	public function create() {
		$this->_rejectLogged();
		$inactive = false;
		if ($this->request->data) {
			if (Auth::check('default', $this->request)) {
				return $this->redirect(array('library' => 'li3_usermanager', 'Users::index'));
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
		foreach(Auth::config() as $name => $config) {
			Auth::clear($name);
		}
		return $this->redirect(array('library' => 'li3_usermanager', 'Session::create'));
	}

}

?>