<?php

namespace Ppap\Tests;

//use Ppap\ParserFactory;
use Ppap\Ppap;

class ParserTest extends BaseTest {
	private $parsers;

	public function __construct() {
		$parsersFile = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'parsers.json';
		if (($parsers = file_get_contents($parsersFile)) === false) {
			$this->fail("Failed to open file '{$parsersFile}'");
		}

		$this->parsers = json_decode($parsers)->parsers;
	}

	public function testParserShouldBreakDownRawAddressesIntoComponents() {
		foreach ($this->parsers as $locale => $cases)
		{
			//$parser = ParserFactory::createParser($locale);
			$parser = new Ppap;

			foreach ($cases as $case)
			{
				if (! is_array($case->raw))
				{
					$case->raw = [$case->raw];
				}

				foreach ($case->raw as $raw)
				{
					//$address = $parser->parse($raw);
					$address = $parser->parse($raw, $locale);

					$this->assertInstanceOf('Ppap\\Address', $address);
					$this->assertAddressesMatch($case->expected, $address, $raw);
				}
			}
		}
	}

	private function assertAddressesMatch($expected, $address, $raw) {
		$attrs = get_object_vars($address);
		foreach ($attrs as $attr => $val)
		{
			$lcattr = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $attr));
			$expectedValue = $expected->{$lcattr};
			$this->assertEquals($expectedValue, $val, "Expected '{$attr}' to be '{$expectedValue}', but got '{$val}' instead for raw address '{$raw}'");
		}
	}
}