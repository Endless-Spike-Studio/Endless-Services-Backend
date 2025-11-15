<?php

namespace App\EndlessProxy\Contracts;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface ExternalProxyStorageServiceContract
{
	public function fetch(): bool;

	public function download(): StreamedResponse;
}