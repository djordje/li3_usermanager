<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\models;

class PasswordResets extends \lithium\data\Model {

	/**
	 * Return when password reset token exists in DB
	 *
	 * @see li3_usermanager\models\Users::requestPasswordReset()
	 */
	const RESET_TOKEN_EXISTS = 0;

	/**
	 * Return when generate new password reset token
	 *
	 * @see li3_usermanager\models\Users::requestPasswordReset()
	 */
	const GENERATED_NEW_RESET_TOKEN = 1;

	public $belongsTo = array('Users');

	protected $_meta = array('key' => 'user_id');

	/**
	 * Logic to get password reset with user relationship
	 *
	 * @param array $conditions
	 * @return bool
	 */
	public static function getResetUser(array $conditions = array()) {
		$self = static::_object();
		$time = new \DateTime();
		$expires = clone $time;
		if ($reset = $self::first(array('conditions' => $conditions, 'with' => 'Users'))) {
			$expires->modify($reset->expires);
			if ($expires <= $time) {
				$reset->delete();
				return false;
			}
			return $reset;
		}
		return false;
	}

}

?>