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

	protected $_records = array();

	protected $_source = 'users';

	public function up() {
		return $this->create();
	}

	public function down() {
		return $this->drop();
	}

}

?>