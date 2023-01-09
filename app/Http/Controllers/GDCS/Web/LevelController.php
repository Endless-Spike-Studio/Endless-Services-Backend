<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Exceptions\GDCS\WebException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\LevelEditRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Level;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function edit(Level $level, LevelEditRequest $request)
    {
        $data = $request->validated();
        $account = Auth::guard('gdcs')->user();

        if (!$account->user->can('edit', $level)) {
            throw new WebException(__('gdcn.tools.error.level_edit_failed_permission_denied'));
        }

        $level->update($data);
        $this->pushSuccessMessage(__('gdcn.tools.action.level_edit_success'));

        return back();
    }
}
