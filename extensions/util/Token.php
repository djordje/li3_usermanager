<?php

namespace li3_usermanager\extensions\util;

use lithium\util\String;

/**
 * Helper class for generating token that will be used for user activation and password reset links.
 */
class Token extends \lithium\core\StaticObject {

	public static function generate($email, array $options = array()) {
		$options += array(
			'prefix' => null,
			'salt' => LI3_UM_TokenSalt,
			'type' => 'sha256'
		);
		$prefix = ($options['prefix']) ? $options['prefix'] : mt_rand(100000, 999999);
		unset($options['prefix']);
		return String::hash($prefix . md5($email), $options);
	}

}

?>