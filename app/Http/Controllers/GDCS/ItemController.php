<?php

namespace App\Http\Controllers\GDCS;

use App\Enums\GDCS\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\ItemLikeRequest;
use App\Http\Requests\GDCS\ItemRestoreRequest;
use App\Models\GDCS\AccountComment;
use App\Models\GDCS\Level;
use App\Models\GDCS\LevelComment;
use App\Models\GDCS\LikeRecord;

class ItemController extends Controller
{
    public function like(ItemLikeRequest $request): int
    {
        $data = $request->validated();

        switch ($data['type']) {
            case 1:
                $item = Level::query()
                    ->findOrFail($data['itemID']);
                break;
            case 2:
                $item = LevelComment::query()
                    ->findOrFail($data['itemID']);
                break;
            case 3:
                $item = AccountComment::query()
                    ->findOrFail($data['itemID']);
                break;
            default:
                return Response::LIKE_FAILED_INVALID_TYPE->value;
        }

        $ip = $request->ip();
        $userID = $request->user?->id;

        $record = LikeRecord::query()
            ->where('type', $data['type'])
            ->where('item_id', $data['itemID'])
            ->where('ip', $ip)
            ->where('user_id', $userID);

        if (!$record->exists()) {
            $record = new LikeRecord();
            $record->type = $data['type'];
            $record->item_id = $data['itemID'];
            $record->ip = $ip;
            $record->user_id = $userID;
            $record->save();

            if (!empty($data['like'])) {
                $item->likes++;
            } else {
                $item->likes--;
            }

            $item->save();
            return Response::LIKE_SUCCESS->value;
        }

        return Response::LIKE_FAILED->value;
    }

    public function restore(ItemRestoreRequest $request): Response
    {
        $request->validated();
        return Response::ITEM_RESTORE_FAILED;
    }
}
