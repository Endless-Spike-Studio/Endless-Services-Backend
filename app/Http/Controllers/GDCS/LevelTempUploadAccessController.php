<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Request as RequestFacade;

class LevelTempUploadAccessController extends Controller
{
    use HasMessage;

    public function create(): RedirectResponse
    {
        /** @var Account $account */
        $account = Request::user('gdcs');
        $maxCount = config('gdcs.max_temp_upload_access_count', 3);

        $count = $account->tempLevelUploadAccesses()
            ->count();

        if ($count >= $maxCount) {
            $this->pushErrorMessage(
                __('messages.temp_level_access.create_too_many')
            );

            return back();
        }

        $account->tempLevelUploadAccesses()
            ->create([
                'ip' => Request::ip()
            ]);

        $this->pushSuccessMessage(
            __('messages.created')
        );

        return back();
    }

    public function delete(int $id): RedirectResponse
    {
        /** @var Account $account */
        $account = RequestFacade::user('gdcs');

        $query = $account->tempLevelUploadAccesses()
            ->whereKey($id);

        if (!$query->exists()) {
            $this->pushErrorMessage(
                __('messages.delete_failed')
            );
        } else {
            $this->pushSuccessMessage(
                __('messages.deleted')
            );

            $query->delete();
        }

        return back();
    }
}
