<?php

/**
 * @copyright Copyright 2012, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\models;

class AboutUsers extends \lithium\data\Model {

	public $belongsTo = array('Users');

	protected $_meta = array('key' => 'user_id');

	public $validates = array(
		'fullname' => array(
			'lengthBetween',
			'min' => 0, 'max' => 105, 'required' => false,
			'message' => 'Your name is too long'
		),
		'homepage' => array(
			array(
				'lengthBetween',
				'min' => 0, 'max' => 105, 'skipEmpty' => true, 'required' => false,
				'message' => 'Link is too long'
			),
			array('url', 'skipEmpty' => true, 'required' => false, 'message' => 'Must be valid URL')
		),
		'about' => array(
			'lengthBetween',
			'min' => 0, 'max' => 500, 'skipEmpty' => true, 'required' => false,
			'message' => 'You can enter maximum 500 characters'
		)
	);

}

?>