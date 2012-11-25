<?php

use lithium\security\Auth;

/**
 * li3_usermanager global configuration
 *
 * `RequireUserActivation` If true newly created users get mail with activation token, and account
 * stay inactive until activation.
 * `DefaultUserGroup` Default 2 (member), this is group that will be applied to all new users.
 */
define('LI3_UM_RequireUserActivation', true);
define('LI3_UM_DefaultUserGroup', 2);

/**
 * Auth configurations
 * Users authed trough 'inactive' configuration gets message about inactive account!
 * Configurations named 'banned', 'member', 'manager', 'admin', 'root' can be used with
 * 'li3_access' AuthRbac adapter.
 */
Auth::config(array(
	'default' => array(
		'adapter' => 'Form',
		'scope' => array('active' => true)
	),
	'inactive' => array(
		'adapter' => 'Form',
		'scope' => array('active' => false)
	),
	'banner' => array(
		'adapter' => 'Form',
		'scope' => array('active' => true, 'user_group_id' => 1)
	),
	'member' => array(
		'adapter' => 'Form',
		'scope' => array('active' => true, 'user_group_id' => 2)
	),
	'manager' => array(
		'adapter' => 'Form',
		'scope' => array('active' => true, 'user_group_id' => 3)
	),
	'admin' => array(
		'adapter' => 'Form',
		'scope' => array('active' => true, 'user_group_id' => 4)
	),
	'root' => array(
		'adapter' => 'Form',
		'scope' => array('active' => true, 'user_group_id' => 5)
	)
));

?>