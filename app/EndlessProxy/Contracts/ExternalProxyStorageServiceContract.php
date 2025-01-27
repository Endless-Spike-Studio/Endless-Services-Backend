<?php

namespace App\EndlessProxy\Contracts;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface ExternalProxyStorageServiceContract
{
	public function download(): StreamedResponse;
}