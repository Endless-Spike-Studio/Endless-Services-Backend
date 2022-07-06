<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\DailyChestFetchRequest;
use Exception;
use GDCN\GDAlgorithm\enums\Keys;
use GDCN\GDAlgorithm\enums\Salts;
use GDCN\GDAlgorithm\GDAlgorithm;
use Illuminate\Support\Str;

class DailyChestController extends Controller
{
    public function fetch(DailyChestFetchRequest $request): string
    {
        $data = $request->validated();
        $reward = $request->user->reward;

        $rewardConfig = config('gdcs.reward');

        try {
            $smallChestReward = implode(',', [
                random_int(...$rewardConfig['small']['orbs']),
                random_int(...$rewardConfig['small']['diamonds']),
                random_int(...$rewardConfig['small']['shards']),
                random_int(...$rewardConfig['small']['keys']),
            ]);
        } catch (Exception) {
            $smallChestReward = '0,0,0,0';
        }

        $smallChestRemainTime = 0;
        if (! empty($reward->small_time)) {
            $time = $reward->small_time->addSeconds($rewardConfig['small']['wait']);

            if ($time->isFuture()) {
                $smallChestReward = '0,0,0,0';
                $smallChestRemainTime = $time->diffInSeconds();
            }
        }

        try {
            $bigChestReward = implode(',', [
                random_int(...$rewardConfig['big']['orbs']),
                random_int(...$rewardConfig['big']['diamonds']),
                random_int(...$rewardConfig['big']['shards']),
                random_int(...$rewardConfig['big']['keys']),
            ]);
        } catch (Exception) {
            $bigChestReward = '0,0,0,0';
        }

        $bigChestRemainTime = 0;
        if (! empty($reward->big_time)) {
            $time = $reward->big_time->addSeconds($rewardConfig['big']['wait']);

            if ($time->isFuture()) {
                $bigChestRemainTime = $time->diffInSeconds();
                $bigChestReward = '0,0,0,0';
            }
        }

        switch ($data['rewardType']) {
            case 0:
                $smallChestReward = '0,0,0,0';
                $bigChestReward = '0,0,0,0';
                break;
            case 1:
                $smallChestRemainTime = $rewardConfig['small']['wait'];
                $bigChestReward = '0,0,0,0';

                $reward->small_time = now();
                $reward->small_count++;
                $reward->save();
                break;
            case 2:
                $bigChestRemainTime = $rewardConfig['big']['wait'];
                $smallChestReward = '0,0,0,0';

                $reward->big_time = now();
                $reward->big_count++;
                $reward->save();
                break;
        }

        $reward = implode(':', [
            Str::random(5),
            $request->user->id,
            GDAlgorithm::decode(substr($data['chk'], 5), Keys::REWARD->value),
            $data['udid'],
            $data['accountID'] ?? 0,
            $smallChestRemainTime,
            $smallChestReward,
            $reward->small_count,
            $bigChestRemainTime,
            $bigChestReward,
            $reward->big_count,
            $data['rewardType'],
        ]);

        $rewardString = GDAlgorithm::encode($reward, Keys::REWARD->value, sha1: false);

        return Str::random(5).$rewardString.'|'.sha1($rewardString.Salts::REWARD->value);
    }
}
