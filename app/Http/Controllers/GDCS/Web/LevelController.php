<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Exceptions\GDCS\WebException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\LevelEditRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
            throw new WebException(__('gdcn.dashboard.error.level_edit_failed_permission_denied'));
        }

        $level->update($data);
        $this->pushSuccessMessage(__('gdcn.dashboard.action.level_edit_success'));

        return back();
    }

    /**
     * @throws WebException
     */
    public function delete(Level $level)
    {
        $account = Auth::guard('gdcs')->user();
        $policy = Gate::forUser($account->user)->inspect('delete', $level);

        if ($policy->denied()) {
            throw new WebException(
                __('gdcn.dashboard.error.level_delete_failed_with_reason', [
                    'reason' => $policy->message()
                ])
            );
        }

        $level->delete();
        $this->pushSuccessMessage(__('gdcn.dashboard.action.level_delete_success'));

        return to_route('gdcs.dashboard.home');
    }
}
