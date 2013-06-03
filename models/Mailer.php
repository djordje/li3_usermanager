<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_usermanager\models;

use li3_swiftmailer\mailer\Transports;
use li3_swiftmailer\mailer\Message;

/**
 * Class Mailer is mail data container that wrap `li3_swiftmailer` logic to send mails
 */
class Mailer {

	/**
	 * Mail data
	 *
	 * @var array
	 */
	public static $_data = array(
		'subject' => '',
		'from' => '',
		'to' => '',
		'body' => ''
	);

	/**
	 * Prepare data  with `Swift_Message`
	 *
	 * @return mixed
	 */
	protected static function prepareData() {
		$message = Message::newInstance();
		$message->setSubject(static::$_data['subject']);
		$message->setFrom(static::$_data['from']);
		$message->setTo(array(static::$_data['to']));
		$message->setBody(static::$_data['body']);
		return $message;
	}

	/**
	 * Send message with `Swift_MailTransport`
	 *
	 * @return mixed
	 */
	public static function send() {
		$mailer = Transports::adapter('default');
		return $mailer->send(static::prepareData());
	}

}

?>