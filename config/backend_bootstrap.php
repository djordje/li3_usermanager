<?php

/**
 * Add `ManageUsers::index` to Components backend menu
 */
li3_backend\models\NavBar::addBackendLink(array(
	'title' => 'Manage users',
	'url' => array('li3_usermanager.ManageUsers::index', 'backend' => true)
));

?>