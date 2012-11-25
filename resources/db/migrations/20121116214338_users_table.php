<?php

use Phinx\Migration\AbstractMigration;

class UsersTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
    	$users = $this->table('users');
		$users->addColumn('username', 'string', array('limit' => 10, 'default' => null, 'null' => false))
			  ->addColumn('password', 'string', array('limit' => 105, 'default' => null, 'null' => false))
			  ->addColumn('email', 'string', array('limit' => 105, 'default' => null, 'null' => false))
			  ->addColumn('active', 'boolean', array('default' => null))
			  ->addColumn('user_group_id', 'integer', array('default' => null, 'null' => false))
			  ->addIndex(array('username', 'email'), array('unique' => true))
			  ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
		$this->dropTable('users');
    }
}