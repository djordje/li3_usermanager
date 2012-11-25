<?php

use lithium\net\http\Router;

/**
 * Usermanager URL prefix and library
 */
$um = '/user';
$umLib = array('library' => 'li3_usermanager');

/**
 * Plugin routes
 */
Router::connect('/login', $umLib + array('Session::create'));
Router::connect('/logout', $umLib + array('Session::destroy'));
Router::connect($um, $umLib + array('Users::index'));
Router::connect("{$um}/register", $umLib + array('Users::add'));
Router::connect("{$um}/activate/{:token}/{:username}", $umLib + array('Users::activate'));
Router::connect("{$um}/edit/details", $umLib + array('Users::editDetails'));
Router::connect("{$um}/edit/change-email", $umLib + array('Users::changeEmail'));
Router::connect("{$um}/edit/change-password", $umLib + array('Users::changePassword'));
Router::connect("{$um}/edit/reset-password", $umLib + array('Users::requestResetPassword'));
Router::connect(
	"{$um}/edit/reset-password/{:token}/{:username}", $umLib + array('Users::resetPassword')
);
Router::connect("{$um}/manage/{:action}/{:id}", $umLib + array(
	'controller' => 'ManageUsers', 'id' => null
));

?>