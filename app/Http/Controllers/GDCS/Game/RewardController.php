<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Enums\GDCS\Game\Algorithm\Salts;
use App\Enums\GDCS\Game\Parameters\RewardType;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\RewardFetchRequest;
use App\Http\Traits\GameLog;
use App\Services\Game\AlgorithmService;
use Exception;
use GeometryDashChinese\GeometryDashAlgorithm;
use Illuminate\Support\Str;

class RewardController extends Controller
{
    use GameLog;

    public function fetch(RewardFetchRequest $request): string
    {
        $data = $request->validated();
        $reward = $request->user->reward;
        $config = config('gdcn.game.rewards');

        try {
            $smallChestReward = implode(',', [
                random_int($config['small']['orbs']['min'], $config['small']['orbs']['max']),
                random_int($config['small']['diamonds']['min'], $config['small']['diamonds']['max']),
                random_int($config['small']['shards']['min'], $config['small']['shards']['max']),
                random_int($config['small']['keys']['min'], $config['small']['keys']['max']),
            ]);
        } catch (Exception) {
            $smallChestReward = '0,0,0,0';
        }

        $smallChestRemainTime = 0;
        if (!empty($reward->small_time)) {
            $time = $reward->small_time->addSeconds($config['small']['wait']);

            if ($time->isFuture()) {
                $smallChestReward = '0,0,0,0';
                $smallChestRemainTime = $time->diffInSeconds();
            }
        }

        try {
            $bigChestReward = implode(',', [
                random_int($config['big']['orbs']['min'], $config['big']['orbs']['max']),
                random_int($config['big']['diamonds']['min'], $config['big']['diamonds']['max']),
                random_int($config['big']['shards']['min'], $config['big']['shards']['max']),
                random_int($config['big']['keys']['min'], $config['big']['keys']['max']),
            ]);
        } catch (Exception) {
            $bigChestReward = '0,0,0,0';
        }

        $bigChestRemainTime = 0;
        if (!empty($reward->big_time)) {
            $time = $reward->big_time->addSeconds($config['big']['wait']);

            if ($time->isFuture()) {
                $bigChestRemainTime = $time->diffInSeconds();
                $bigChestReward = '0,0,0,0';
            }
        }

        switch ($data['rewardType']) {
            case RewardType::SMALL:
                $smallChestRemainTime = $config['small']['wait'];
                $bigChestReward = '0,0,0,0';

                $reward->update([
                    'small_time' => now(),
                    'small_count' => ++$reward->small_count
                ]);
                break;
            case RewardType::BIG:
                $bigChestRemainTime = $config['big']['wait'];
                $smallChestReward = '0,0,0,0';

                $reward->update([
                    'big_time' => now(),
                    'big_count' => ++$reward->big_count
                ]);
                break;
            case RewardType::LIST:
            default:
                $smallChestReward = '0,0,0,0';
                $bigChestReward = '0,0,0,0';
                break;
        }

        $reward = implode(':', [
            Str::random(5),
            $request->user->id,
            AlgorithmService::decode(substr($data['chk'], 5), Keys::REWARD->value),
            $request->user->udid,
            $request->user->uuid,
            $smallChestRemainTime,
            $smallChestReward,
            $reward->small_count,
            $bigChestRemainTime,
            $bigChestReward,
            $reward->big_count,
            $data['rewardType'],
        ]);

        $this->logGame(__('gdcn.game.action.reward_fetch_success'));
        $result = GeometryDashAlgorithm::encode($reward, Keys::REWARD->value, sha1: false);
        return Str::random(5) . $result . '|' . sha1($result . Salts::REWARD->value);
    }
}
