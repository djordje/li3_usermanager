<?php

use Phinx\Migration\AbstractMigration;

class AboutUsersTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
	public function up()
	{
		$about = $this->table('about_users', array('id' => false, 'primary_key' => array('user_id')));
		$about->addColumn('user_id', 'integer', array('default' => null, 'null' => false))
			  ->addColumn('fullname', 'string', array('limit' => 105, 'default' => null, 'null' => true))
			  ->addColumn('homepage', 'string', array('limit' => 105, 'default' => null, 'null' => true))
			  ->addColumn('about', 'text', array('default' => null, 'null' => true))
			  ->save();
	}

    /**
     * Migrate Down.
     */
    public function down()
    {
		$this->dropTable('about_users');
    }
}