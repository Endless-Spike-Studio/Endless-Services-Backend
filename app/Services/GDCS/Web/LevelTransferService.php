<?php

namespace App\Services\GDCS\Web;

use App\Enums\GDCS\Game\Parameters\LevelSearchType;
use App\Exceptions\GDCS\WebException;
use App\Exceptions\ResponseException;
use App\Services\Game\AlgorithmService;
use App\Services\Game\ObjectService;
use App\Services\Game\ResponseService;
use App\Services\ProxyService;
use Base64Url\Base64Url;
use Illuminate\Support\Arr;

class LevelTransferService
{
    /**
     * @throws WebException
     */
    public function loadLevelsFromRemote(string $baseUrl, int $userID, int $page = 0): array
    {
        try {
            $response = ProxyService::instance()
                ->asForm()
                ->withUserAgent(null)
                ->post($baseUrl . '/getGJLevels21.php', [
                    'type' => LevelSearchType::USER,
                    'str' => $userID,
                    'page' => $page,
                    'secret' => 'Wmfd2893gb7',
                ])->body();

            ResponseService::check($response);
            $parts = explode('#', $response);
            $levels = explode('|', Arr::get($parts, 0));
            [$total, $offset, $perPage] = explode(':', Arr::get($parts, 3)) ?? [0, 0, AlgorithmService::$perPage];

            return [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => ceil($offset / $perPage),
                'last_page' => ceil($total / $perPage),
                'data' => collect($levels)
                    ->map(function ($level) {
                        $levelObject = ObjectService::split($level, ':');

                        return [
                            'id' => (integer)$levelObject[1],
                            'name' => $levelObject[2],
                            'description' => Base64Url::decode($levelObject[3])
                        ];
                    })
            ];
        } catch (ResponseException) {
            throw new WebException(__('gdcn.tools.error.level_transfer_failed_level_load_failed'));
        }
    }
}
