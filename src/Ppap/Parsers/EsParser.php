<?php

namespace Ppap\Parsers;

use Ppap\Address;

class EsParser extends Parser {
	public function parse($raw) {
		$address = new Address;

		/*
		 * Zip-code, and optionally province.
		 */
		if (preg_match('/\d{4,5}/', $raw, $m))
		{
			$address->zip = str_pad($m[0], 5, '0', STR_PAD_LEFT);
			$raw = str_replace($address->zip, '', $raw);

			$provinceCode = substr($address->zip, 0, 2);
			if (array_key_exists($provinceCode, $this->provincesByCode()))
			{
				$address->province = $this->provincesByCode()[$provinceCode];
				// TODO: should delete province from raw address
			}
		}

		/*
		 * Way type and name
		 */
		$regex = '/^(?:' . $this->wayTypesRegex() . '?\s+)?' . $this->prepositionsRegex() . '?([^,\-0-9]+)/i';
		if (preg_match($regex, $raw, $m))
		{
			$wayType = trim($m[1]);
			$wayName = trim($m[3]);
			
			if (array_key_exists($wayType, $this->wayTypes()))
			{
				$address->wayType = $this->wayTypes()[$wayType];
			}
			else
			{
				$address->wayType = 'CL';
			}
			
			$address->wayName = $wayName;
			
			$raw = str_replace($m[0], '', $raw);
		}

		/*
		 * Way number
		 */
		if (preg_match('/^(?:[,\-\s]+)?(?:LOCAL\s+)?([0-9]+|S\/N|SN)\s*/', $raw, $m))
		{
			$wayNumber = $m[1];
			if (! is_numeric($wayNumber))
			{
				$wayNumber = null;
			}

			$address->wayNumber = $wayNumber;

			$raw = str_replace($m[0], '', $raw);
		}

		/*
		 * Town and province
		 */
		if (preg_match('/([A-Z][A-Z\s]+)([,\-(]([A-Z\s]+)[\-)]?)?/i', $raw, $m))
		{
			$town = trim($m[1]);
			$address->town = $town;

			// province, only if not found already
			if ($address->province !== null)
			{
				$province = trim($m[3]);
				// ...
			}

			$raw = str_replace($m[0], '', $raw);
		}

		/*
		 * Rest of details
		 */
		if (preg_match('/[,\-]*([A-Z0-9ºª\s]+)/i', $raw, $m))
		{
			$details = str_replace(['º', 'ª'], '', trim($m[1]));
			$address->details = $details;
		}

		return $address;
	}

	private function provincesByCode() {
		return [
			'10' => 'CACERES'
		];
	}

	private function wayTypes() {
		return [
			'CL'			=> 'CL',
			'CALLE'			=> 'CL',
			'C\\'			=> 'CL',
			'C/'			=> 'CL'
		];
	}

	private function wayTypesRegex() {
		$regex = implode('|', array_keys($this->wayTypes()));
		$regex = str_replace(['\\', '/'], ['\\\\', '\/'], $regex);
		
		return "({$regex})";
	}

	private function prepositions() {
		return ['DE LOS', 'DE LAS', 'DE LA', 'DEL', 'DE'];
	}

	private function prepositionsRegex() {
		$regex = implode('\s+|', $this->prepositions()) . '\s+';
		return "({$regex})";
	}
}