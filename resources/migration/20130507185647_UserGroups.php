<?php

namespace li3_usermanager\resources\migration;

class UserGroups extends \li3_migrations\models\Migration {

	protected $_fields = array(
		'id' => array('type' => 'id'),
		'name' => array('type' => 'string', 'limit' => 30, 'default' => null, 'null' => false),
		'slug' => array('type' => 'string', 'limit' => 30, 'default' => null, 'null' => false),
		'description' => array('type' => 'string', 'limit' => 255, 'default' => null, 'null' => true)
	);

	protected $_records = array(
		array('name' => 'Banned', 'slug' => 'banned', 'description' => 'Banned users'),
		array('name' => 'Member', 'slug' => 'member', 'description' => 'Registered users'),
		array('name' => 'Manager', 'slug' => 'manager', 'description' => 'Content managers'),
		array('name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Site administrators')
	);

	protected $_source = 'user_groups';

	public function up() {
		return $this->create() && $this->save();
	}

	public function down() {
		return $this->drop();
	}

}

?>