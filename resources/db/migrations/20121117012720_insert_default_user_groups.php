<?php

use Phinx\Migration\AbstractMigration;

class InsertDefaultUserGroups extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
		$this->execute('INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Banned", "banned", "Banned users");');
		$this->execute('INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Member", "member", "Registered users");');
		$this->execute('INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Manager", "manager", "Content managers");');
		$this->execute('INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Admin", "admin", "Site administrators");');
    	$this->execute('INSERT `user_groups` (`name`, `slug`, `description`) VALUES ("Root", "root", "Site owner");');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
		$this->execute('TRUNCATE TABLE `user_groups`;');
    }
}