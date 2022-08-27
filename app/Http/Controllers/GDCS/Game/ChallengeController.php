<?php

namespace App\Http\Controllers\GDCS\Game;

use App\Enums\GDCS\Game\Algorithm\Keys;
use App\Enums\GDCS\Game\Algorithm\Salts;
use App\Enums\Response;
use App\Exceptions\GeometryDashChineseServerException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\Game\ChallengeFetchRequest;
use App\Http\Traits\GameLog;
use App\Models\GDCS\Challenge;
use App\Services\Game\AlgorithmService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ChallengeController extends Controller
{
    use GameLog;

    /**
     * @throws GeometryDashChineseServerException
     */
    public function fetch(ChallengeFetchRequest $request): string
    {
        $data = $request->validated();

        $currentTime = now();
        $challenges = Challenge::query()
            ->whereDate('created_at', $currentTime)
            ->get();

        $count = $challenges->count();
        $items = config('gdcn.game.challenges');

        if ($count < 3) {
            for ($i = 0; $i < 3 - $count; $i++) {
                try {
                    $item = Arr::random($items);

                    $challenge = new Challenge();
                    $challenge->type = $item['id'];
                    $challenge->collect = random_int($item['collect']['min'], $item['collect']['max']);
                    $challenge->name = $this->generateName($item, $challenge->collect);
                    $challenge->reward = round($item['reward']['give'] * $challenge->collect / $item['reward']['every']);
                    $challenge->save();

                    $challenges->push($challenge);
                } catch (Exception $e) {
                    throw new GeometryDashChineseServerException(__('gdcn.game.error.challenge_generate_failed_unexpected_exception'), log_context: [
                        'message' => $e->getMessage()
                    ], game_response: Response::GAME_CHALLENGE_GENERATE_FAILED_UNEXPECTED_EXCEPTION->value);
                }
            }
        }

        $challenge = implode(':', [
            Str::random(5),
            $request->user->id,
            AlgorithmService::decode(substr($data['chk'], 5), Keys::CHALLENGES->value),
            $request->user->udid,
            $request->user->uuid,
            $currentTime->secondsUntilEndOfDay(),
            $this->generateInfo($challenges[0]),
            $this->generateInfo($challenges[1]),
            $this->generateInfo($challenges[2]),
        ]);

        $this->logGame(__('gdcn.game.action.challenge_fetch_success'));
        $result = AlgorithmService::encode($challenge, Keys::CHALLENGES->value, sha1: false);
        return Str::random(5) . $result . '|' . sha1($result . Salts::CHALLENGE->value);
    }

    protected function generateName(array $config, int $collect): string
    {
        $result = value(static function () use ($config, $collect) {
            if ($collect >= 5) {
                return $config['name'] . ' collector';
            }

            return implode(' ', array_fill(0, 3, Str::plural($config['name']) . '!'));
        });


        if (count($config['names']) <= 0) {
            return $result;
        }

        return Arr::random([
            $result,
            Arr::random($config['names'])
        ]);
    }

    protected function generateInfo(Challenge $challenge): string
    {
        return implode(',', [
            $challenge->id,
            $challenge->type,
            $challenge->collect,
            $challenge->reward,
            $challenge->name,
        ]);
    }
}
