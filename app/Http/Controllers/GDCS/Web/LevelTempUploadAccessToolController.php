<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\TempLevelUploadAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LevelTempUploadAccessToolController extends Controller
{
    use HasMessage;

    public function create()
    {
        Auth::guard('gdcs')
            ->user()
            ->tempLevelUploadAccesses()
            ->create([
                'ip' => Request::ip()
            ]);

        $this->pushSuccessMessage(__('gdcn.tools.action.level_temp_upload_access_create_success'));
        return to_route('gdcs.tools.level.temp_upload_access.index');
    }

    /**
     * @throws WebException
     */
    public function delete(TempLevelUploadAccess $access)
    {
        $currentAccountID = Auth::guard('gdcs')->id();

        if ($access->account_id === $currentAccountID) {
            $access->delete();
            $this->pushSuccessMessage(__('gdcn.tools.action.level_temp_upload_access_delete_success'));
        } else {
            throw new WebException(__('gdcn.tools.error.level_temp_upload_access_delete_failed_not_owner'));
        }

        return back();
    }
}
