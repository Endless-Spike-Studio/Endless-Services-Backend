<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountFailedLogApiController extends Controller
{
    use HasMessage;

    public function clear(Request $request): RedirectResponse
    {
        $request->user('gdcs')
            ->failedLogs()
            ->delete();

        $this->pushSuccessMessage(
            __('messages.deleted')
        );

        return back();
    }
}
