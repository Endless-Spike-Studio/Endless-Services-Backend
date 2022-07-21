<?php

namespace App\Http\Controllers;

use App\Enums\Response;
use App\Exceptions\InvalidResponseException;
use App\Models\GDProxy\CustomSong;
use App\Models\GDProxy\LevelSongReplace;
use App\Models\NGProxy\Song;
use Base64Url\Base64Url;
use GeometryDashChinese\enums\Salts;
use GeometryDashChinese\GeometryDashAlgorithm;
use GeometryDashChinese\GeometryDashObject;
use Illuminate\Http\Request;

class GDProxyController extends Controller
{
    public function process(Request $request): string
    {
        try {
            return $this->preProcessRequest($request)
                ?? $this->processResponse(
                    $request,
                    app('proxy')
                        ->post(
                            config('gdproxy.base_url') . $request->getRequestUri(),
                            $request->all()
                        )->body()
                );
        } catch (InvalidResponseException) {
            return Response::INVALID_RESPONSE->value;
        }
    }

    public function preProcessRequest(Request $request): ?string
    {
        $uri = $request->getRequestUri();
        $data = $request->all();

        if ($uri === '/getGJSongInfo.php') {
            $song = $this->fetchSong($data['songID']);
            if ($song !== null) {
                return $song->object;
            }
        }

        return null;
    }

    protected function fetchSong(int $id): Song|CustomSong|null
    {
        $customSongOffset = config('gdproxy.custom_song_offset', 10000000);

        if ($id >= $customSongOffset) {
            $song = CustomSong::query()
                ->where('id', $id - $customSongOffset)
                ->first();
        } else {
            $song = Song::query()
                ->where('song_id', $id)
                ->first();
        }

        if (empty($song)) {
            return null;
        }

        return $song;
    }

    /**
     * @throws InvalidResponseException
     */
    public function processResponse(Request $request, string $response): ?string
    {
        HelperController::checkResponse($response);

        return match ($request->getRequestUri()) {
            '/getGJLevels21.php' => $this->processLevelSearchSongReplace($response),
            '/downloadGJLevel22.php' => $this->processLevelDownloadSongReplace($response),
            default => $response
        };
    }

    protected function processLevelSearchSongReplace(string $response): string
    {
        $responseParts = explode('#', $response);
        $levels = $responseParts[0];

        $addedSongs = [];
        $processedLevels = [];

        foreach (explode('|', $levels) as $level) {
            $levelObject = GeometryDashObject::split($level, ':');

            $replace = LevelSongReplace::query()
                ->where('level_id', $levelObject[1])
                ->first();

            if (!empty($replace)) {
                $levelObject[12] = 0;
                $levelObject[35] = $replace->song_id;

                $song = $this->fetchSong($replace->song_id);
                if ($song !== null) {
                    $addedSongs[] = $song->object;
                }
            }

            $processedLevels[] = GeometryDashObject::merge($levelObject, ':');
        }

        $responseParts[0] = implode('|', $processedLevels);
        $responseParts[2] = implode('~:~', array_merge(explode('~:~', $responseParts[2]), array_unique($addedSongs)));

        return implode('#', $responseParts);
    }

    protected function processLevelDownloadSongReplace(string $response): string
    {
        $responseParts = explode('#', $response);
        $level = $responseParts[0];

        $levelObject = GeometryDashObject::split($level, ':');
        $levelObject[27] = 'Aw==';

        $replace = LevelSongReplace::query()
            ->where('level_id', $levelObject[1])
            ->first();

        if (!empty($replace)) {
            $levelObject[12] = 0;
            $levelObject[35] = $replace->song_id;

            if (!empty($replace->offset)) {
                $levelObject[4] = $this->processLevelStringSongReplace($levelObject[4], $replace->offset);
                $responseParts[1] = GeometryDashAlgorithm::genLevelDivided($levelObject[4], 40, 39);
            }
        }

        $hash = $this->generateLevelHashString($levelObject);
        $responseParts[0] = GeometryDashObject::merge($levelObject, ':');
        $responseParts[2] = sha1($hash . Salts::LEVEL->value);

        return implode('#', $responseParts);
    }

    protected function processLevelStringSongReplace(string $data, float $offset): string
    {
        $levelData = gzdecode(Base64Url::decode($data));
        $levelDataParts = explode(';', $levelData);

        $levelDataObject = GeometryDashObject::split($levelDataParts[0], ',');
        $levelDataObject['kA13'] = $offset;
        $processedLevelHeader = GeometryDashObject::merge($levelDataObject, ',');

        return Base64Url::encode(
            gzencode(
                str_replace($levelDataParts[0], $processedLevelHeader, $levelData)
            ),
            true
        );
    }

    protected function generateLevelHashString(array $levelObject, int $password = 1): string
    {
        return implode(',', [
            !empty($levelObject[6]) ? $levelObject[6] : 0,
            !empty($levelObject[18]) ? $levelObject[18] : 0,
            !empty($levelObject[17]) ? $levelObject[17] : 0,
            !empty($levelObject[1]) ? $levelObject[1] : 0,
            !empty($levelObject[38]) ? $levelObject[38] : 0,
            !empty($levelObject[19]) ? $levelObject[19] : 0,
            $password,
            !empty($levelObject[41]) ? $levelObject[41] : 0,
        ]);
    }
}
