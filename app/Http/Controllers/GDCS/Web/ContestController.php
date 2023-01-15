<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Enums\GDCS\Game\ContestRule;
use App\Exceptions\GDCS\WebException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\ContestSubmitRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Contest;
use App\Models\GDCS\ContestParticipant;
use App\Models\GDCS\Level;
use Illuminate\Support\Facades\Auth;

class ContestController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function submit(Contest $contest, ContestSubmitRequest $request)
    {
        $data = $request->validated();

        $account = Auth::guard('gdcs')->user();
        $level = Level::findOrFail($data['levelID']);
        $rules = $contest->rules();

        if ($level->creator->isNot($account->user)) {
            throw new WebException(__('gdcn.dashboard.error.contest_submit_failed_not_level_owner'));
        }

        if ($rules->contains(ContestRule::EDITABLE)) {
            $contest->participants()
                ->updateOrCreate([
                    'account_id' => $account->id
                ], [
                    'level_id' => $level->id
                ]);
        } else {
            $accountAlreadySubmitted = $contest->participants()
                ->where('account_id', $account->id)
                ->exists();

            if ($rules->contains(ContestRule::UNIQUE_ACCOUNT) && $accountAlreadySubmitted) {
                throw new WebException(__('gdcn.dashboard.error.contest_submit_failed_account_already_submitted'));
            }

            $levelAlreadySubmitted = $contest->participants()
                ->where('level_id', $data['levelID'])
                ->exists();

            if ($rules->contains(ContestRule::UNIQUE_LEVEL) && $levelAlreadySubmitted) {
                throw new WebException(__('gdcn.dashboard.error.contest_submit_failed_level_already_submitted'));
            }

            $levelAlreadySubmittedInOtherContest = ContestParticipant::query()
                ->whereNot('contest_id', $contest->id)
                ->where('level_id', $level->id)
                ->exists();

            if ($rules->contains(ContestRule::UNIQUE_LEVEL_CONTEST) && $levelAlreadySubmittedInOtherContest) {
                throw new WebException(__('gdcn.dashboard.error.contest_submit_failed_level_already_submitted_in_other_contest'));
            }

            $contest->participants()
                ->create([
                    'account_id' => $account->id,
                    'level_id' => $level->id
                ]);
        }

        $this->pushSuccessMessage(__('gdcn.dashboard.action.contest_submit_success'));
        return back();
    }
}
