<?php

namespace li3_usermanager\resources\migration;

class UserGroups extends \li3_migrations\models\Migration {

	protected $_fields = array(
		'id' => array('type' => 'id'),
		'name' => array('type' => 'string', 'limit' => 30, 'default' => null, 'null' => false),
		'slug' => array('type' => 'string', 'limit' => 30, 'default' => null, 'null' => false),
		'description' => array('type' => 'string', 'limit' => 255, 'default' => null, 'null' => true),
		'parent_id' => array('type' => 'integer', 'length' => 10, 'null' => true),
		'active' => array('type' => 'boolean', 'default' => true, 'null' => true),
		'lft' => array('type' => 'integer', 'length' => 10, 'null' => true),
		'rght' => array('type' => 'integer', 'length' => 10, 'null' => true)
	);

	protected $_records = array(
		array('name' => 'Banned', 'slug' => 'banned', 'description' => 'Banned users', 'active' => false),
		array('name' => 'Member', 'slug' => 'member', 'description' => 'Registered users'),
		array('name' => 'Manager', 'slug' => 'manager', 'description' => 'Content managers', 'parent_id' => '{slug:member}'),
		array('name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Site administrators')
	);

	protected $_model = '\li3_usermanager\models\UserGroups';

	protected function _findParentId($record) {
		$model = $this->_model;
		if (!is_int($record['parent_id']) &&
			preg_match('/\{slug\:[a-zA-z0-9\_\-\/]*\}/', $record['parent_id']))
		{
			$find = explode(':', substr($record['parent_id'], 1, -1));
			$find = $model::first(array('conditions' => array($find[0] => $find[1])));
			return $find->id;
		}
		return $record['parent_id'];
	}

	public function up() {
		if (!$this->create()) {
			return false;
		}
		$model = $this->_model;
		foreach ($this->_records as $record) {
			if (isset($record['parent_id'])) {
				$record['parent_id'] = $this->_findParentId($record);
			}
			$group = $model::create();
			$group->set($record);
			if(!$group->save()) {
				return false;
			}
		}
		return true;
	}

		public function down() {
		return $this->drop();
	}

}

?>