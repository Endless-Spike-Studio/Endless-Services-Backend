<?php

namespace App\Http\Controllers\GDCS\Web;

use App\Exceptions\GDCS\WebException;
use App\Exceptions\ResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\AccountLinkToolCreateRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\AccountLink;
use App\Services\Game\ResponseService;
use App\Services\ProxyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccountLinkToolController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function create(AccountLinkToolCreateRequest $request)
    {
        $data = $request->validated();

        try {
            $response = ProxyService::instance()
                ->asForm()
                ->withUserAgent(null)
                ->post($data['server'] . '/accounts/loginGJAccount.php', [
                    'userName' => $data['name'],
                    'password' => $data['password'],
                    'udid' => Str::uuid()
                        ->toString(),
                    'secret' => 'Wmfv3899gc9'
                ])->body();

            ResponseService::check($response);
        } catch (ResponseException) {
            throw new WebException(__('gdcn.tools.error.account_link_create_failed_login_failed'));
        }

        $account = Auth::guard('gdcs')->user();
        [$accountID, $userID] = explode(',', $response);

        $link = $account->links()
            ->firstOrCreate([
                'server' => $data['server'],
                'target_account_id' => $accountID,
                'target_user_id' => $userID,
            ], [
                'target_name' => $data['name']
            ]);

        if (!$link->wasRecentlyCreated) {
            throw new WebException(__('gdcn.tools.error.account_link_create_failed_already_exists'));
        }

        $this->pushSuccessMessage(__('gdcn.tools.action.account_link_create_success'));
        return to_route('gdcs.tools.account.link.index');
    }

    /**
     * @throws WebException
     */
    public function delete(AccountLink $link)
    {
        $currentAccountID = Auth::guard('gdcs')->id();

        if ($link->account->id === $currentAccountID) {
            $link->delete();
            $this->pushSuccessMessage(__('gdcn.tools.action.account_link_delete_success'));
        } else {
            throw new WebException(__('gdcn.tools.error.account_link_delete_failed_not_owner'));
        }

        return back();
    }
}
