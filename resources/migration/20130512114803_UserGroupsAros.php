<?php

namespace li3_usermanager\resources\migration;

class UserGroupsAros extends \li3_migrations\models\Migration {

	protected $_records = array(
		array('alias' => 'users'),
		array('alias' => 'members', 'parent_id' => '{node:users}'),
		array('alias' => 'managers', 'parent_id' => '{node:users/members}'),
		array('alias' => 'administrators', 'parent_id' => '{node:users}')
	);

	protected $_model = '\li3_access\security\access\model\db_acl\Aro';

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
			$aro = $model::create();
			$aro->set($record);
			if(!$aro->save()) {
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