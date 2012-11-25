<?php

use Phinx\Migration\AbstractMigration;

class UserActivationsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
		$activations = $this->table('user_activations', array('id' => false, 'primary_key' => array('user_id')));
		$activations->addColumn('user_id', 'integer', array('default' => null, 'null' => false))
					->addColumn('token', 'string', array('limit' => 100, 'default' => null, 'null' => false))
					->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
		$this->dropTable('user_activations');
    }
}