<?php

use lithium\net\http\Router;

/**
 * Url prefix
 */
$umPrefix = '/';
$user = 'user';
$users = $user . 's';

/**
 * SessionController routes
 */
Router::connect($umPrefix . 'login', 'li3_usermanager.Session::create');
Router::connect($umPrefix . 'logout', 'li3_usermanager.Session::destroy');

/**
 * UsersController routes
 */
Router::connect($umPrefix . $user, 'li3_usermanager.Users::index');
if (LI3_UM_EnableUserRegistration) {
	Router::connect("{$umPrefix}{$user}/register", 'li3_usermanager.Users::add');
}
Router::connect(
	"{$umPrefix}{$user}/activate/{:token}/{:username}", 'li3_usermanager.Users::activate'
);
Router::connect("{$umPrefix}{$user}/edit-details", 'li3_usermanager.Users::editDetails');
Router::connect("{$umPrefix}{$user}/change-email", 'li3_usermanager.Users::changeEmail');
Router::connect("{$umPrefix}{$user}/change-password", 'li3_usermanager.Users::changePassword');
Router::connect("{$umPrefix}{$user}/reset-password", 'li3_usermanager.Users::requestResetPassword');
Router::connect(
	"{$umPrefix}{$user}/reset-password/{:token}/{:username}", 'li3_usermanager.Users::resetPassword'
);

/**
 * ManageUsersController routes
 */
Router::connect(
	"{$umPrefix}manage/{$users}/{:action}/{:id}",
	array('controller' => 'li3_usermanager.ManageUsers', 'id' => null)
);

?>