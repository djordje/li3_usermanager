<?php

use lithium\security\Auth;

/**
 * li3_usermanager global configuration
 *
 * `RequireUserActivation` If true newly created users get mail with activation token, and account
 * stay inactive until activation.
 * `DefaultUserGroup` Default 2 (member), this is group that will be applied to all new users.
 * `LI3_UM_TokenSalt` String that will be used as salt for generating activation and password
 * reset tokens.
 * `LI3_UM_ActivationEmailFrom` Email that'll be shown in email from for activation token
 * `LI3_UM_PasswordResetEmailFrom` Email that'll be shown in email from for password reset token
 * `LI3_UM_EnableUserRegistration` Boolean to enable control over user registration
 */
define('LI3_UM_RequireUserActivation', true);
define('LI3_UM_DefaultUserGroup', 2);
define('LI3_UM_TokenSalt', 'AoJMd;sd5622');
define('LI3_UM_ActivationEmailFrom', 'no-replay@example.com');
define('LI3_UM_PasswordResetEmailFrom', LI3_UM_ActivationEmailFrom);
define('LI3_UM_EnableUserRegistration', true);

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