<?php

use Phinx\Migration\AbstractMigration;

class UserGroupsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
		$groups = $this->table('user_groups');
		$groups->addColumn('name', 'string', array('limit' => 30, 'default' => null, 'null' => false))
			   ->addColumn('slug', 'string', array('limit' => 30, 'default' => null, 'null' => false))
			   ->addColumn('description', 'string', array('limit' => 255, 'default' => null, 'null' => true))
			   ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
		$this->dropTable('user_groups');
    }
}