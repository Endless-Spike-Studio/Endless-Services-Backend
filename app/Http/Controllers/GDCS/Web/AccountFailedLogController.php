<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountFailedLogController extends Controller
{
    public function clear(Request $request): RedirectResponse
    {
        $request->user('gdcs')
            ->failedLogs()
            ->delete();

        return back();
    }
}
