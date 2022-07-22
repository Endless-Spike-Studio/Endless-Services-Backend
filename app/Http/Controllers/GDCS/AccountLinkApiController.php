<?php

namespace App\Http\Controllers\GDCS;

use App\Exceptions\InvalidResponseException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\GDCS\AccountLinkCreateRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Str;

class AccountLinkApiController extends Controller
{
    use HasMessage;

    public function create(AccountLinkCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $response = app('proxy')
                ->post($data['server'] . '/accounts/loginGJAccount.php', [
                    'userName' => $data['name'],
                    'password' => $data['password'],
                    'udid' => Str::uuid()
                        ->toString(),
                    'secret' => 'Wmfv3899gc9',
                ])->body();

            HelperController::checkResponse($response);
        } catch (InvalidResponseException) {
            $this->pushErrorMessage(
                __('messages.robtop_now_not_like_you')
            );

            return back();
        }

        /** @var Account $account */
        $account = $request->user('gdcs');
        [$accountID, $userID] = explode(',', $response);

        $account->links()
            ->create([
                'server' => $data['server'],
                'target_name' => $data['name'],
                'target_account_id' => $accountID,
                'target_user_id' => $userID,
            ]);

        $this->pushSuccessMessage(
            __('messages.created')
        );

        return to_route('gdcs.tools.account.link.list');
    }

    public function delete(AccountLink $link): RedirectResponse
    {
        /** @var Account $account */
        $account = RequestFacade::user('gdcs');

        if ($link->account_id === $account->id) {
            $this->pushSuccessMessage(
                __('messages.deleted')
            );

            $link->delete();
        } else {
            abort(404);
        }

        return back();
    }
}
