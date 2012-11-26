<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\models;

class PasswordResets extends \lithium\data\Model {

	public $belongsTo = array('Users');

	protected $_meta = array('key' => 'user_id');

}

?>