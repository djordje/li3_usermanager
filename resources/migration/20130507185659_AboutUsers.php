<?php

namespace li3_usermanager\resources\migration;

class AboutUsers extends \li3_migrations\models\Migration {

	protected $_fields = array(
		'user_id' => array('type' => 'id'),
		'fullname' => array('type' => 'string', 'limit' => 105, 'default' => null, 'null' => true),
		'homepage' => array('type' => 'string', 'limit' => 105, 'default' => null, 'null' => true),
		'about' => array('type' => 'text', 'default' => null, 'null' => true)
	);

	protected $_records = array(
		array('user_id' => 1)
	);

	protected $_source = 'about_users';

	public function up() {
		return $this->create();
	}

	public function down() {
		return $this->drop();
	}

}

?>