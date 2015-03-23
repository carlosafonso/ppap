<?php

namespace Ppap;

class DefaultNormalizer implements NormalizerInterface {
	public function normalize($address) {
		$normalizedAddress = $this->clean($address);
		$normalizedAddress = strtoupper($normalizedAddress);
		return $normalizedAddress;
	}

	public function clean($str, $cleanWhitespace = false) {

		$r = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
		$r = preg_replace('/[?]*/', '', $r);

		if ($cleanWhitespace)
		{
			$r = $this->trimWhitespace($r, '_');
		}

		return $r;
	}

	public function ascii($str) {
		return preg_replace('/[^(\x20-\x7F)]*/', '', $str);
	}

	public function trimWhitespace($str, $replacement = '_') {
		return preg_replace('/\s+/', $replacement, $str);
	}

	public function random($length) {

		if (! is_numeric($length))
			$length = intval($length);

		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$chars_len = strlen($chars);

		$r = '';
		for ($i = 0; $i < $length; $i++)
		{
			$r .= substr($chars, rand(0, $chars_len - 1), 1);
		}

		return $r;
	}
}
