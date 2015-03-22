<?php

namespace Ppap;

use Ppap\ParserFactory;

/**
 * Ppap: a postal address parser.
 *
 * @author	Carlos Afonso <carlos.afonso.perez@gmail.com>
 */
class Ppap {
	private $normalizer;

	public function __construct(NormalizerInterface $normalizer = null) {
		if ($normalizer === null)
		{
			$normalizer = new DefaultNormalizer;
		}

		$this->normalizer = $normalizer;
	}

	public function parse($address, $locale) {
		$parser = ParserFactory::createParser($locale);
		return $parser->parse($this->normalizer->normalize($address));
	}

	public function getNormalizer() {
		return $this->normalizer;
	}
}