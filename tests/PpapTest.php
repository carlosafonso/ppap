<?php

namespace Ppap\Tests;

use Ppap\NormalizerInterface;
use Ppap\Ppap;

class PpapTest extends BaseTest {
	public function testInstanceShouldUseProvidedNormalizer() {
		$ppap = new Ppap(new DummyNormalizer);

		$this->assertInstanceOf('Ppap\\Tests\\DummyNormalizer', $ppap->getNormalizer());
	}

	public function testInstanceShouldUseDefaultNormalizerIfNoneIsGiven() {
		$ppap = new Ppap;

		$this->assertInstanceOf('Ppap\\DefaultNormalizer', $ppap->getNormalizer());
	}
}

class DummyNormalizer implements NormalizerInterface {
	public function normalize($address) {}
}