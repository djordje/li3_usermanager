<?php

namespace li3_usermanager\resources\migration;

class PasswordResets extends \li3_migrations\models\Migration {

	protected $_fields = array(
		'user_id' => array('type' => 'id'),
		'expires' => array('type' => 'datetime'),
		'token' => array('type' => 'string', 'limit' => 100, 'default' => null, 'null' => false)
	);

	protected $_records = array();

	protected $_model = '\li3_usermanager\models\PasswordResets';

	public function up() {
		return $this->create();
	}

	public function down() {
		return $this->drop();
	}

}

?>