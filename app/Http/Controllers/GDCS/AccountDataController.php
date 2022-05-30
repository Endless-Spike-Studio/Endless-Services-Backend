<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\AccountDataLoadRequest;
use App\Http\Requests\GDCS\AccountDataSaveRequest;
use App\Http\Requests\GDCS\AccountDataServerAddressGetRequest;
use Illuminate\Support\Facades\Storage;

class AccountDataController extends Controller
{
    public function getDataServerAddress(AccountDataServerAddressGetRequest $request): string
    {
        return $request->getHost();
    }

    public function save(AccountDataSaveRequest $request): int
    {
        $data = $request->validated();

        foreach (config('gdcs.storages.saveData') as $storage) {
            Storage::disk($storage['disk'])->put(str_replace('@', $request->account->id, $storage['format']), $data['saveData']);
        }

        return Response::ACCOUNT_DATA_SAVE_SUCCESS->value;
    }

    public function load(AccountDataLoadRequest $request): int|string
    {
        $data = $request->validated();

        foreach (config('gdcs.storages.saveData') as $storage) {
            $fileName = str_replace('@', $request->account->id, $storage['format']);
            $saveData = Storage::disk($storage['disk'])->get($fileName);

            if (!empty($saveData)) {
                return implode(';', [
                    $saveData,
                    $data['gameVersion'],
                    $data['binaryVersion'],
                    $saveData
                ]);
            }
        }

        return Response::ACCOUNT_DATA_LOAD_FAILED->value;
    }
}
