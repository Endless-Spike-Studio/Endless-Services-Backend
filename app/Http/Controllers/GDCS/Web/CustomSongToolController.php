<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Exceptions\StorageException;
use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\CustomSongToolCreateUsingFileRequest;
use App\Http\Requests\GDCS\Web\CustomSongToolCreateUsingLinkRequest;
use App\Http\Requests\GDCS\Web\CustomSongToolCreateUsingNeteaseRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\CustomSong;
use App\Models\GDCS\Level;
use App\Services\Game\CustomSongService;
use App\Services\ProxyService;
use App\Services\Storage\CustomSongStorageService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomSongToolController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function delete(CustomSong $song)
    {
        $account = Auth::guard('gdcs')->user();

        if ($song->account->isNot($account)) {
            throw new WebException(__('gdcn.tools.error.custom_song_delete_failed_not_owner'));
        }

        $alreadyInUse = Level::query()
            ->where('song_id', CustomSongService::$offset + $song->id)
            ->exists();

        if ($alreadyInUse) {
            throw new WebException(__('gdcn.tools.error.custom_song_delete_failed_in_use'));
        }

        $song->delete();
        $this->pushSuccessMessage(__('gdcn.tools.action.custom_song_delete_success'));

        return back();
    }

    /**
     * @throws WebException
     */
    public function createUsingLink(CustomSongToolCreateUsingLinkRequest $request)
    {
        $data = $request->validated();
        $account = Auth::guard('gdcs')->user();
        $req = ProxyService::instance()
            ->get($data['link']);

        $response = $req->body();
        $size = strlen($response) / 1024 / 1024;
        $contentType = $req->header('Content-Type');
        $contentLength = $req->header('Content-Length');

        if (Str::contains($data['link'], [163, 126, 'netease'])) {
            throw new WebException(__('gdcn.tools.error.custom_song_create_failed_netease_detected'));
        }

        if (empty($contentType) || empty($contentLength)) {
            throw new WebException(__('gdcn.tools.error.custom_song_create_failed_invalid_response_headers'));
        }

        if (Arr::get(explode('/', $contentType), 0) !== 'audio') {
            throw new WebException(__('gdcn.tools.error.custom_song_create_failed_invalid_content_type'));
        }

        $song = $account->uploadedCustomSongs()
            ->create([
                'name' => $data['name'],
                'artist_name' => $data['artist_name'],
                'size' => $size,
                'download_url' => $data['link']
            ]);

        $this->pushSuccessMessage(
            __('gdcn.tools.action.custom_song_create_success_with_id', [
                'id' => CustomSongService::$offset + $song->id
            ])
        );

        return to_route('gdcs.tools.song.custom.uploaded');
    }

    /**
     * @throws WebException
     * @throws StorageException
     */
    public function createUsingFile(CustomSongToolCreateUsingFileRequest $request, CustomSongStorageService $storage)
    {
        $data = $request->validated();
        $file = $request->file('file');
        $account = Auth::guard('gdcs')->user();

        if ($file->guessExtension() !== 'mp3') {
            throw new WebException(__('gdcn.tools.error.custom_song_create_failed_invalid_content_type'));
        }

        $song = $account->uploadedCustomSongs()
            ->create([
                'name' => $data['name'],
                'artist_name' => $data['artist_name'],
                'size' => $file->getSize() / 1024 / 1024,
                'download_url' => null
            ]);

        $content = $file->getContent();
        $data = ['id' => $song->id];
        $storage->put($data, $content);

        $song->update([
            'download_url' => $storage->url($data)
        ]);

        $this->pushSuccessMessage(
            __('gdcn.tools.action.custom_song_create_success_with_id', [
                'id' => CustomSongService::$offset + $song->id
            ])
        );

        return to_route('gdcs.tools.song.custom.uploaded');
    }

    /**
     * @throws WebException
     */
    public function createUsingNetease(CustomSongToolCreateUsingNeteaseRequest $request)
    {
        $data = $request->validated();
        $account = Auth::guard('gdcs')->user();
        $link = "https://music.163.com/song/media/outer/url?id={$data['music_id']}.mp3";

        $query = CustomSong::query()
            ->whereDownloadUrl($link);

        if ($query->exists()) {
            throw new WebException(
                __('gdcn.tools.error.custom_song_create_failed_already_exists_with_id', [
                    'id' => CustomSongService::$offset + $query->value('id')
                ])
            );
        }

        $songInfo = ProxyService::instance()
            ->get("https://music.163.com/api/song/detail/?ids=[{$data['music_id']}]")
            ->json('songs.0');

        if (empty($songInfo)) {
            throw new WebException(__('gdcn.tools.error.custom_song_create_failed_music_info_fetch_failed'));
        }

        $song = $account->uploadedCustomSongs()
            ->create([
                'name' => $songInfo['name'],
                'artist_name' => collect($songInfo['artists'])
                    ->pluck('name')
                    ->implode(' / '),
                'size' => sprintf('%.2f', $songInfo['lMusic']['size'] / 1024 / 1024),
                'download_url' => $link,
            ]);

        $this->pushSuccessMessage(
            __('gdcn.tools.action.custom_song_create_success_with_id', [
                'id' => CustomSongService::$offset + $song->id
            ])
        );

        return to_route('gdcs.tools.song.custom.uploaded');
    }
}
