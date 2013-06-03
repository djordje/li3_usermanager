<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\models;

class UserActivations extends \lithium\data\Model {

	public $belongsTo = array('Users');

	protected $_meta = array('key' => 'user_id');

	/**
	 * Logic to activate user account
	 *
	 * @param array $conditions
	 * @return bool
	 */
	public static function activate(array $conditions = array()) {
		$self = static::_object();
		if ($activation = $self::first(array('conditions' => $conditions, 'with' => 'Users'))) {
			if (!$activation->user->active) {
				$activation->user->active = 1;
				if ($activation->user->save()) {
					return $activation->delete();
				}
			}
		}
		return false;
	}

}

?>