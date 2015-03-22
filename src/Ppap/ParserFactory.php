<?php

namespace Ppap;

use RuntimeException;

use Ppap\Locale;
use Ppap\Parsers\EsParser;

class ParserFactory {
	public static function createParser($locale = null) {
		switch ($locale)
		{
			case Locale::ES:
				return new EsParser();
			default:
				throw new RuntimeException("No parser available for locale '{$locale}'");
		}
	}
}