<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\extensions\util;

use lithium\util\String;

/**
 * Helper class for generating token that will be used for user activation and password reset links.
 */
class Token extends \lithium\core\StaticObject {

	/**
	 * Generate hashed and salted token from `'prefix'` and `md5` hashed `$email` value
	 * @param $email string User email that will be used as base for secret token
	 * @param array $options Supported options:
	 *        - `'prefix'` _string|int_ If not passed this method will generate random int from
	 *          `100000` to `999999`. Hashed email will be prefixed with value of this option.
	 *          Example: `'prefix_value' . md5($email)`
	 *        - All other options are same as `lithium\util\String::hash()`
	 * @return string Hashed prefixed email salted and hashed again
	 * @see lithium\util\String::hash()
	 */
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