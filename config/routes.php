<?php

use lithium\net\http\Router;

/**
 * Usermanager URL prefix and library
 */
$um = '/user';

$umUrl = function($url) {
	return (array) $url + array('library' => 'li3_usermanager');
};

/**
 * Plugin routes
 */
Router::connect('/login', $umUrl('Session::create'));
Router::connect('/logout', $umUrl('Session::destroy'));
Router::connect($um, $umUrl('Users::index'));
if (LI3_UM_EnableUserRegistration) {
	Router::connect("{$um}/register", $umUrl('Users::add'));
}
Router::connect("{$um}/activate/{:token}/{:username}", $umUrl('Users::activate'));
Router::connect("{$um}/edit/details", $umUrl('Users::editDetails'));
Router::connect("{$um}/edit/change-email", $umUrl('Users::changeEmail'));
Router::connect("{$um}/edit/change-password", $umUrl('Users::changePassword'));
Router::connect("{$um}/edit/reset-password", $umUrl('Users::requestResetPassword'));
Router::connect(
	"{$um}/edit/reset-password/{:token}/{:username}", $umUrl('Users::resetPassword')
);
Router::connect("{$um}/manage/{:action}/{:id}", $umUrl(array(
	'controller' => 'ManageUsers', 'id' => null
)));

?>