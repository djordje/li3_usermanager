<?php

namespace li3_usermanager\resources\migration;

class Users extends \li3_migrations\models\Migration {

	protected $_fields = array(
		'id' => array('type' => 'id'),
		'username' => array('type' => 'string', 'limit' => 10, 'default' => null, 'null' => false),
		'password' => array('type' => 'string', 'limit' => 105, 'default' => null, 'null' => false),
		'email' => array('type' => 'string', 'limit' => 105, 'default' => null, 'null' => false),
		'active' => array('type' => 'boolean', 'default' => null),
		'user_group_id' => array('type' => 'integer', 'default' => null, 'null' => false)
	);

	protected $_records = array(
		array(
			'id' => 1, 'username' => 'root',
			'password' => '$2a$10$A5YGQOVpu3QJBse.TVYhweLpKDxG.o9FDlXlYh7gS4pWIpB9pIk2e',
			'email' => 'root@localhost', 'active' => 1, 'user_group_id' => 4
		)
	);

	protected $_model = '\li3_usermanager\models\Users';

	public function up() {
		return $this->save();
	}

	public function down() {
		return $this->drop();
	}

}

?>