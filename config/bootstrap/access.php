<?php

/**
 * li3_access configuration file
 */

use lithium\security\Auth;
use li3_access\security\Access;

/**
 * Auth configurations
 * Users authed trough 'inactive' configuration gets message about inactive account!
 */
Auth::config(array(
	'default' => array(
		'adapter' => 'Form',
		'scope' => array('active' => true)
	),
	'inactive' => array(
		'adapter' => 'Form',
		'scope' => array('active' => false)
	)
));

Access::config(array('acl' => array('adapter' => 'DbAcl')));

?>