<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\extensions\command;

use li3_usermanager\models\Users;
use li3_usermanager\models\UserGroups;

class Usermanager extends \lithium\console\Command {

	public function run($command = null) {
		$command = "_{$command}";
		if (method_exists($this,$command)) {
			$this->{$command}();
		}
	}

	/**
	 * Promote user to root if no root in user table
	 * `li3 usermanager root <username>`
	 */
	protected function _root() {
		$root = Users::first(array(
			'conditions' => array(
				'UserGroups.slug' => 'root'
			),
			'with' => 'UserGroups'
		));
		if ($root) {
			$output = array(
				array('ID', 'Username'),
				array('---', '----------'),
				array($root->id, $root->username)
			);
			$this->error('Root user already exists!');
			$this->columns($output);
			return false;
		}
		$username = $this->request->args[0];
		$user = Users::first(array(
			'conditions' => compact('username')
		));
		if (!$user) {
			$this->error("There is not user with username: {$username}");
		}
		$root = UserGroups::first(array('conditions' => array('slug' => 'root')));
		$user->user_group_id = $root->id;
		$user->save();
		return true;
	}

}

?>