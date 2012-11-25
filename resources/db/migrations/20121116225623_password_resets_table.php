<?php

use Phinx\Migration\AbstractMigration;

class PasswordResetsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
    	$resets = $this->table('password_resets', array('id' => false, 'primary_key' => array('user_id')));
		$resets->addColumn('user_id', 'integer', array('default' => null, 'null' => false))
			   ->addColumn('expires', 'datetime')
			   ->addColumn('token', 'string', array('limit' => 100, 'default' => null, 'null' => false))
				->addIndex(array('token'), array('unique' => true))
			   ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
		$this->dropTable('password_resets');
    }
}