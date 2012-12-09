<?php

use lithium\security\Auth;
use lithium\core\Libraries;

/**
 * Merge options passed to library configuration with default options.
 * This options are assigned to defines bellow!
 */
$LI3_UM_Options = Libraries::get('li3_usermanager', 'options');
is_array($LI3_UM_Options) || $LI3_UM_Options = array();
$LI3_UM_Options += array(
	'requireUserActivation' => true,
	'defaultUserGroup' => 2,
	'tokenSalt' => 'AoJMd;sd5622',
	'activationEmailFrom' => null,
	'passwordResetEmailFrom' => null,
	'emailFrom' => 'no-replay@example.com',
	'enableUserRegistration' => true
);
if (!$LI3_UM_Options['activationEmailFrom']) {
	$LI3_UM_Options['activationEmailFrom'] = $LI3_UM_Options['emailFrom'];
}
if (!$LI3_UM_Options['passwordResetEmailFrom']) {
	$LI3_UM_Options['passwordResetEmailFrom'] = $LI3_UM_Options['emailFrom'];
}

/**
 * li3_usermanager global configuration
 *
 * Don't change this defines, pass your options as library config.
 *
 * Example:
 * {{{
 * 	Libraries::add('li3_usermanager', array('options' => array(
 * 		'enableUserRegistration' => false
 * 	));
 * }}}
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
define('LI3_UM_RequireUserActivation', $LI3_UM_Options['requireUserActivation']);
define('LI3_UM_DefaultUserGroup', $LI3_UM_Options['defaultUserGroup']);
define('LI3_UM_TokenSalt', $LI3_UM_Options['tokenSalt']);
define('LI3_UM_ActivationEmailFrom', $LI3_UM_Options['activationEmailFrom']);
define('LI3_UM_PasswordResetEmailFrom', $LI3_UM_Options['passwordResetEmailFrom']);
define('LI3_UM_EnableUserRegistration', $LI3_UM_Options['enableUserRegistration']);

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