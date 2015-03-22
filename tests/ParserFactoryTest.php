<?php

namespace Ppap\Tests;

use Ppap\Locale;
use Ppap\ParserFactory;

class ParserFactoryTest extends BaseTest {
	public function testFactoryReturnsInstanceForRegisteredLocale() {
		$parser = ParserFactory::createParser(Locale::ES);

		$this->assertInstanceOf('Ppap\\Parsers\\EsParser', $parser);
	}

	/**
	 * @expectedException			RuntimeException
	 * @expectedExceptionMessage	No parser available for locale 'this locale does not exist'
	 */
	public function testFactoryThrowsExceptionForUnregisteredLocale() {
		$parser = ParserFactory::createParser('this locale does not exist');
	}
}