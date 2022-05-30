<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\CustomSongLinkCreateRequest;
use App\Http\Requests\GDCS\CustomSongNeteaseCreateRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use App\Models\GDCS\CustomSong;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomSongController extends Controller
{
    use HasMessage;

    public function createLink(CustomSongLinkCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var Account $account */
        $account = Request::user('gdcs');

        $query = CustomSong::query()
            ->whereDownloadUrl($data['link']);

        if ($query->exists()) {
            $this->message(__('messages.custom_song.already_exist_with_id', [
                'id' => $query->getKey() + config('gdcs.custom_song_offset')
            ]), ['type' => 'error']);

            return back();
        }

        /** @var PendingRequest $proxy */
        $proxy = app('proxy');
        $req = $proxy->get($data['link']);

        if (Str::contains($data['link'], [163, 126, 'netease'])) {
            $this->message(__('messages.custom_song.netease_link_found'), ['type' => 'error']);
            return to_route('gdcs.tools.song.custom.create.netease');
        }

        $contentType = $req->header('Content-Type');
        if (explode('/', $contentType)[0] !== 'audio') {
            $this->message(__('messages.custom_song.invalid_link'), ['type' => 'error']);
            return back();
        }

        $response = $req->body();
        $size = strlen($response) / 1024 / 1024;

        $account->uploadedCustomSongs()
            ->create([
                'name' => $data['name'],
                'artist_name' => $data['artist_name'],
                'size' => $size,
                'download_url' => $data['link']
            ]);

        $this->message(__('messages.created'), ['type' => 'success']);
        return to_route('gdcs.tools.song.custom.list');
    }


    public function createNetease(CustomSongNeteaseCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var Account $account */
        $account = Request::user('gdcs');
        $link = "https://music.163.com/song/media/outer/url?id={$data['music_id']}.mp3";

        $query = CustomSong::query()
            ->whereDownloadUrl($link);

        if ($query->exists()) {
            $this->message(__('messages.custom_song.already_exist_with_id', [
                'id' => $query->getKey() + config('gdcs.custom_song_offset')
            ]), ['type' => 'error']);
            return back();
        }

        /** @var PendingRequest $proxy */
        $proxy = app('proxy');

        $songInfo = $proxy->get("https://music.163.com/api/song/detail/?ids=[{$data['music_id']}]")
            ->json('songs.0');

        if ($songInfo === null) {
            $this->message(__('messages.custom_song.not_found'), ['type' => 'error']);
            return back();
        }

        $account->uploadedCustomSongs()
            ->create([
                'name' => $songInfo['name'],
                'artist_name' => collect($songInfo['artists'])
                    ->map(function ($artist) {
                        return $artist['name'];
                    })->implode(' / '),
                'size' => sprintf('%.2f', $songInfo['lMusic']['size'] / 1024 / 1024),
                'download_url' => $link
            ]);

        $this->message(__('messages.created'), ['type' => 'success']);
        return to_route('gdcs.tools.song.custom.list');
    }

    public function delete(int $id): RedirectResponse
    {
        /** @var Account $account */
        $account = Request::user('gdcs');

        $query = $account->uploadedCustomSongs()
            ->whereKey($id);

        if (!$query->exists()) {
            $this->message(__('messages.custom_song.not_found'), ['type' => 'error']);
            return back();
        }

        $query->delete();
        $this->message(__('messages.deleted'), ['type' => 'success']);

        return back();
    }

    public function download(int $id)
    {
        foreach (config('gdcs.storages.customSongData') as $storage) {
            $disk = Storage::disk($storage['disk']);
            $path = str_replace('@', $id, $storage['format']);

            if ($disk->exists($path)) {
                return $disk->download($path);
            }
        }

        abort(404);
    }
}
