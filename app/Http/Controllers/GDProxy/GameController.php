<?php

namespace App\Http\Controllers\GDProxy;

use App\Exceptions\NewGroundsProxyException;
use App\Services\Game\SongService;
use App\Services\ProxyService;
use Illuminate\Http\Request;

class GameController
{
    /**
     * @throws NewGroundsProxyException
     */
    public function proxy(Request $request)
    {
        $uri = $request->getRequestUri();
        $data = $request->all();

        if ($uri === '/getGJSongInfo.php') {
            return (new SongService)->find($data['songID'])->object;
        }

        return ProxyService::instance()
            ->asForm()
            ->withUserAgent(null)
            ->post(rtrim(config('gdcn.proxy.base'), '/') . $uri, $data)
            ->body();
    }
}
