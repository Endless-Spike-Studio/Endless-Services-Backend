<?php

namespace App\Http\Controllers\GDCS\Web\Tools;

use App\Exceptions\GDCS\WebException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\Tools\CustomSongCreateFromLinkRequest;
use App\Http\Requests\GDCS\Web\Tools\CustomSongCreateFromNeteaseRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use App\Models\GDCS\CustomSong;
use App\Services\ProxyService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CustomSongController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function createFromLink(CustomSongCreateFromLinkRequest $request)
    {
        $data = $request->validated();

        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        $query = CustomSong::query()
            ->where('download_url', $data['link']);

        if ($query->exists()) {
            throw new WebException(
                __('gdcn.tools.error.custom_song_create_failed_already_exists_with_id', [
                    'id' => config('gdcn.game.custom_song_offset') + $query->value('id')
                ])
            );
        }

        $headers = ProxyService::instance()
            ->asForm()
            ->get($data['link'])
            ->headers();

        if (!Arr::has($headers['Content-Type'], 0) || !Arr::has($headers['Content-Length'], 0)) {
            throw new WebException(__('gdcn.tools.error.custom_song_create_failed_request_error'));
        }

        $types = explode('/', $headers['Content-Type'][0]);
        if (!Arr::has($types, 0) || $types[0] !== 'audio') {
            throw new WebException(__('gdcn.tools.error.custom_song_create_failed_content_invalid'));
        }

        $account->uploadedCustomSongs()
            ->create([
                'name' => $data['name'],
                'artist_name' => $data['artist_name'],
                'size' => sprintf('%.2f', $headers['Content-Length'][0] / 1024 / 1024),
                'download_url' => $data['link']
            ]);

        $this->pushSuccessMessage(__('gdcn.tools.action.custom_song_create_success'));
        return back();
    }

    /**
     * @see https://music.163.com
     * @throws WebException
     */
    public function createFromNetease(CustomSongCreateFromNeteaseRequest $request)
    {
        $data = $request->validated();
        $downloadUrl = "https://music.163.com/song/media/outer/url?id={$data['music_id']}.mp3";

        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        $query = CustomSong::query()
            ->where('download_url', $downloadUrl);

        if ($query->exists()) {
            throw new WebException(
                __('gdcn.tools.error.custom_song_create_failed_already_exists_with_id', [
                    'id' => config('gdcn.game.custom_song_offset') + $query->value('id')
                ])
            );
        }

        $songInfo = ProxyService::instance()
            ->asForm()
            ->get("https://music.163.com/api/song/detail/?ids=[{$data['music_id']}]")
            ->json('songs.0');

        if (empty($songInfo)) {
            throw new WebException(__('gdcn.tools.error.custom_song_create_failed_target_music_not_found'));
        }

        $account->uploadedCustomSongs()
            ->create([
                'name' => $songInfo['name'],
                'artist_name' => collect($songInfo['artists'])
                    ->pluck('name')
                    ->implode(' / '),
                'size' => sprintf('%.2f', $songInfo['lMusic']['size'] / 1024 / 1024),
                'download_url' => $downloadUrl
            ]);

        $this->pushSuccessMessage(__('gdcn.tools.action.custom_song_create_success'));
        return back();
    }
}
