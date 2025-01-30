<?php

namespace App\GeometryDash\Services;

use App\GeometryDash\Enums\GeometryDashCommentType;
use App\GeometryDash\Enums\GeometryDashSalts;
use App\GeometryDash\Enums\GeometryDashXorKeys;
use Base64Url\Base64Url;

readonly class GeometryDashCheckService
{
	public function generateAccountComment(string $userName, string $comment): string
	{
		return $this->generateComment($userName, $comment, 0, 0, GeometryDashCommentType::ACCOUNT);
	}

	protected function generateComment(string $userName, string $comment, int $levelId, int $percent, GeometryDashCommentType $type): string
	{
		return $this->generate([
			$userName,
			$comment,
			$levelId,
			$percent,
			$type->value
		], GeometryDashSalts::COMMENT_CHK, GeometryDashXorKeys::COMMENT_INTEGRITY);
	}

	protected function generate(array $data, GeometryDashSalts $salt, GeometryDashXorKeys $key, bool $base64 = true)
	{
		$result = app(GeometryDashAlgorithmService::class)->xor(sha1(implode('', $data) . $salt->value), $key->value);

		return $base64 ? Base64Url::encode($result, true) : $result;
	}

	public function generateLevelComment(string $userName, string $comment, int $levelId, int $percent): string
	{
		return $this->generateComment($userName, $comment, $levelId, $percent, GeometryDashCommentType::LEVEL);
	}
}