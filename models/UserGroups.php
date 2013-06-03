<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\models;

class UserGroups extends \li3_behaviors\data\model\Behaviorable {

	protected $_actsAs = array('Tree');

	public $hasMany = array('Users');

}

?>