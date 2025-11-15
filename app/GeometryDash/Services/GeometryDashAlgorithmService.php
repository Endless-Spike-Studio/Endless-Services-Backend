<?php

namespace App\GeometryDash\Services;

use App\GeometryDash\Enums\GeometryDashSalts;

class GeometryDashAlgorithmService
{
	public function xor(string $data, string $key): string
	{
		$dataChars = str_split($data);

		$data = array_map('ord', $dataChars);

		$keyChars = str_split($key);

		$key = array_map('ord', $keyChars);

		$keyLength = count($key);

		$result = '';

		foreach ($data as $i => $v) {
			$result .= chr($v ^ $key[$i % $keyLength]);
		}

		return $result;
	}

	public function generateGjp2(string $password): string
	{
		return sha1($password . GeometryDashSalts::GJP2->value);
	}

	public function generateLevelDivided(string $data, int $size, int $limit): string
	{
		$hash = 'aaaaa';
		$dataLength = strlen($data);
		$divided = (int)($dataLength / $size);

		$times = 0;
		for ($offset = 0; $offset < $dataLength; $offset += $divided) {
			if ($times > $limit) {
				break;
			}

			$hash[$times] = $data[$offset];
			$times++;
		}

		return sha1($hash . GeometryDashSalts::LEVEL->value);
	}
}