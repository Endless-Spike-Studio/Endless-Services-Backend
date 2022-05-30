<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\GeometryDashServer;
use App\Exceptions\InvalidResponseException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\GDCS\AccountLinkCreateRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Str;

class AccountLinkController extends Controller
{
    use HasMessage;

    public function create(AccountLinkCreateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $server = GeometryDashServer::from($data['server']);

        try {
            $response = app('proxy')
                ->post($server->value . '/accounts/loginGJAccount.php', [
                    'userName' => $data['name'],
                    'password' => $data['password'],
                    'udid' => Str::uuid()
                        ->toString(),
                    'secret' => 'Wmfv3899gc9'
                ])->body();

            HelperController::checkResponse($response);
        } catch (InvalidResponseException|Exception) {
            $this->message(__('messages.robtop_now_not_like_you'), ['type' => 'error']);
            return back();
        }

        /** @var Account $account */
        $account = $request->user('gdcs');
        [$accountID, $userID] = explode(',', $response);

        $account->links()
            ->create([
                'server' => $server->value,
                'target_name' => $data['name'],
                'target_account_id' => $accountID,
                'target_user_id' => $userID
            ]);

        $this->message(__('messages.created'), ['type' => 'success']);
        return to_route('gdcs.tools.account.link.list');
    }

    public function delete(int $id): RedirectResponse
    {
        /** @var Account $account */
        $account = RequestFacade::user('gdcs');

        $query = $account->links()
            ->whereKey($id);

        if (!$query->exists()) {
            $this->message(__('messages.delete_failed'), ['type' => 'error']);
        } else {
            $this->message(__('messages.deleted'), ['type' => 'success']);
            $query->delete();
        }

        return back();
    }
}
