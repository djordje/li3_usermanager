<?php

namespace li3_usermanager\resources\migration;

class UserGroupsPermissions extends \li3_migrations\models\Migration {

	protected $_records = array(
		// administrators have full access
		array('users/administrators', 'li3_usermanager', array('*')),
		// enable members and subgroups to change account data
		array('users/members', 'li3_usermanager/controllers/Users', array('index', 'editDetails', 'changeEmail', 'changePassword')),
		// enable guests to create account, activate, request and reset password if have valid account
		array('guests', 'li3_usermanager/controllers/Users', array('add', 'activate', 'requestResetPassword', 'resetPassword'))
	);

	protected $_model = '\li3_access\security\access\model\db_acl\Permission';

	protected function _getId($ref, $node) {
		$res = reset($node::node($ref));
		return $res['id'];
	}

	public function up() {
		$model = $this->_model;
		foreach ($this->_records as $record) {
			$record[3] = (isset($record[3])) ? $record[3] : 1;
			if (!$model::allow($record[0], $record[1], $record[2], $record[3])) {
				return false;
			}
		}
		return true;
	}

	public function down() {
		$model = $this->_model;
		$aro = '\li3_access\security\access\model\db_acl\Aro';
		$aco = '\li3_access\security\access\model\db_acl\Aco';

		foreach (array_reverse($this->_records) as $record) {
			$find = $model::first(array('conditions' => array(
				'aro_id' => $this->_getId($record[0], $aro),
				'aco_id' => $this->_getId($record[1], $aco)
			)));
			if ($find && !$find->delete()) {
				return false;
			}
		}

		return true;
	}

}

?>