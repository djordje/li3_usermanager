<?php

/**
 * li3_access configuration file
 */

use lithium\security\Auth;
use li3_access\security\Access;

/**
 * Auth configurations
 * Users authorized trough 'inactive' configuration gets message about inactive account!
 */
Auth::config(array(
	'default' => array(
		'adapter' => 'Form',
		'scope' => array('active' => true),
		'query' => 'firstWithGroup'
	),
	'inactive' => array(
		'adapter' => 'Form',
		'scope' => array('active' => false)
	)
));

/**
 * Access adapters configurations
 * For details se `li3_access` documentation
 */
Access::config(array(
	'acl' => array('adapter' => 'DbAcl'),
	'rules' => array('adapter' => 'Rules')
));

?>