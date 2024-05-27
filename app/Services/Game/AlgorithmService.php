<?php

namespace App\Services\Game;

use App\Enums\GDCS\Game\Algorithm\Salts;
use Base64Url\Base64Url;

class AlgorithmService extends BaseGameService
{
	public static function encode(string $data, string $key, bool $usePadding = true, bool $sha1 = true): string
	{
		return Base64Url::encode(static::xor($sha1 ? sha1($data) : $data, $key), $usePadding);
	}

	public static function xor(string $data, string $key): string
	{
		$dataChars = str_split($data);
		$keyChars = str_split($key);

		$data = array_map('ord', $dataChars);
		$key = array_map('ord', $keyChars);

		$result = null;
		foreach ($data as $i => $v) {
			$keyCount = count($key);
			$result .= chr($v ^ $key[$i % $keyCount]);
		}

		return $result;
	}

	public static function decode(string $data, string $key): string
	{
		return static::xor(Base64Url::decode($data), $key);
	}

	public static function genLevelDivided(string $data, int $size, int $limit): string
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

		return sha1($hash . Salts::LEVEL->value);
	}

	public static function genPage(int $page, int $total, int $perPage = null): string
	{
		if ($perPage === null) {
			$perPage = static::$perPage;
		}

		return implode(':', [$total, (--$page * $perPage), $perPage]);
	}
}
