<?php

namespace App\Http\Controllers\GDCS\Web\Tools;

use App\Exceptions\GDCS\WebException;
use App\Exceptions\ResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Web\Tools\AccountLinkCreateRequest;
use App\Http\Traits\HasMessage;
use App\Models\GDCS\Account;
use App\Models\GDCS\AccountLink;
use App\Services\Game\ResponseService;
use App\Services\ProxyService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccountLinkController extends Controller
{
    use HasMessage;

    /**
     * @throws WebException
     */
    public function create(AccountLinkCreateRequest $request)
    {
        $data = $request->validated();

        $request = ProxyService::instance()
            ->asForm()
            ->withUserAgent(null)
            ->post($data['server'] . '/accounts/loginGJAccount.php', [
                'userName' => $data['name'],
                'password' => $data['password'],
                'udid' => Str::uuid()->toString(),
                'secret' => 'Wmfv3899gc9'
            ]);

        try {
            if (!$request->ok()) {
                throw new WebException(__('gdcn.tools.error.account_link_create_failed_request_error'));
            }

            $response = $request->body();
            ResponseService::check($response);
            $dataArray = explode(',', $response);

            if (!Arr::has($dataArray, [0, 1])) {
                throw new WebException(__('gdcn.tools.error.account_link_create_failed_response_error'));
            }

            /** @var Account $account */
            $account = Auth::guard('gdcs')->user();
            [$accountId, $userId] = $dataArray;

            $alreadyLinked = $account->links()
                ->where('server', $data['server'])
                ->where(function ($query) use ($userId, $accountId) {
                    $query->orWhere([
                        'target_account_id' => $accountId,
                        'target_user_id' => $userId
                    ]);
                })->exists();

            if ($alreadyLinked) {
                throw new WebException(__('gdcn.tools.error.account_link_create_failed_already_linked'));
            }

            $account->links()
                ->create([
                    'server' => $data['server'],
                    'target_name' => $data['name'],
                    'target_account_id' => $accountId,
                    'target_user_id' => $userId
                ]);

            $this->pushSuccessMessage(__('gdcn.tools.action.account_link_create_success'));
        } catch (ResponseException) {
            throw new WebException(__('gdcn.tools.error.account_link_create_failed_response_error'));
        }

        return back();
    }

    /**
     * @throws WebException
     */
    public function delete(AccountLink $link)
    {
        /** @var Account $account */
        $account = Auth::guard('gdcs')->user();

        if ($link->account->isNot($account)) {
            throw new WebException(__('gdcn.tools.error.account_link_delete_failed_not_owner'));
        }

        $link->delete();
        $this->pushSuccessMessage(__('gdcn.tools.action.account_link_delete_success'));

        return back();
    }
}
