<?php

namespace li3_usermanager\tests\cases\extensions\util;

use li3_usermanager\extensions\util\Token;

class TokenTest extends \lithium\test\Unit {

	public function testTokenGenerate() {
		$options = array(
			'prefix' => 123456,
			'salt' => 'us3rmanag3r'
		);
		$email = 'li3test@djordjekovacevic.com';
		$expected = '006956d2bc0127492315a10d7432858c6e6ed4eb24860dfcbec93dd1eb7e4627';
		$this->assertEqual($expected, Token::generate($email, $options));

		$options['salt'] = 'userm4n4ger';
		$expected = '0658b7bded39eae35cfb996e3d2b759526e6a35fea9b9ecd75ff20ca4eb71c69';
		$this->assertEqual($expected, Token::generate($email, $options));

		$options['type'] = 'md5';
		$expected = '853a77ca78669592b7175a15083b4347';
		$this->assertEqual($expected, Token::generate($email, $options));

		$options['prefix'] = 'li3';
		$expected = 'e4b7e045f0b80cc96904190d90b84934';
		$this->assertEqual($expected, Token::generate($email, $options));
	}

}

?>