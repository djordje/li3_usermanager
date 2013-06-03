<?php

namespace li3_usermanager\resources\migration;

class UserGroupsAcos extends \li3_migrations\models\Migration {

	protected $_records = array(
		array('alias' => 'li3_usermanager'),
		array('alias' => 'controllers', 'parent_id' => '{node:li3_usermanager}'),
		array('alias' => 'ManageUsers', 'parent_id' => '{node:li3_usermanager/controllers}'),
		array('alias' => 'Session', 'parent_id' => '{node:li3_usermanager/controllers}'),
		array('alias' => 'Users', 'parent_id' => '{node:li3_usermanager/controllers}')
	);

	protected $_model = '\li3_access\security\access\model\db_acl\Aco';

	protected function _findParentId($record) {
		$model = $this->_model;
		if (!is_int($record['parent_id']) &&
			preg_match('/\{node\:[a-zA-z0-9\_\-\/]*\}/', $record['parent_id']))
		{
			$find = explode(':', substr($record['parent_id'], 1, -1));
			$find = reset($model::node(end($find)));
			return $find['id'];
		}
		return $record['parent_id'];
	}

	public function up() {
		$model = $this->_model;
		foreach ($this->_records as $record) {
			if (isset($record['parent_id'])) {
				$record['parent_id'] = $this->_findParentId($record);
			}
			$aco = $model::create();
			$aco->set($record);
			if(!$aco->save()) {
				return false;
			}
		}
		return true;
	}

	public function down() {
		$model = $this->_model;
		foreach (array_reverse($this->_records) as $record) {
			if (isset($record['parent_id'])) {
				$record['parent_id'] = $this->_findParentId($record);
			}
			$find = $model::first(array('conditions' => $record));
			if ($find && !$find->delete()) {
				return false;
			}
		}
		return true;
	}

}

?>