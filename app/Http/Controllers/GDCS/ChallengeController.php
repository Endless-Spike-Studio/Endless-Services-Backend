<?php

namespace App\Http\Controllers\GDCS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GDCS\ChallengeFetchRequest;
use App\Models\GDCS\Challenge;
use Carbon\Carbon;
use Exception;
use GDCN\GDAlgorithm\enums\Keys;
use GDCN\GDAlgorithm\enums\Salts;
use GDCN\GDAlgorithm\GDAlgorithm;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ChallengeController extends Controller
{
    public function fetchAll(ChallengeFetchRequest $request): string
    {
        $data = $request->validated();

        $types = [1 => 'orbs', 2 => 'coins', 3 => 'stars'];
        $config = config('gdcs.challenge');
        $challenges = Challenge::query()
            ->whereDate('created_at', now())
            ->get();

        $count = $challenges->count();
        if ($count < 3) {
            for ($i = 0; $i < 3 - $count; $i++) {
                try {
                    $_types = array_keys($types);
                    $challenge = new Challenge();
                    $challenge->type = Arr::random($_types);

                    $type = $types[$challenge->type];
                    $_config = $config[$type];

                    $challenge->name = Arr::random($_config['names']);
                    $challenge->collect = random_int(...$_config['collect']);
                    [$every, $reward] = $_config['reward'];
                    $challenge->reward = round($reward * $challenge->collect / $every);
                    $challenge->save();

                    $challenges->push($challenge);
                } catch (Exception) {
                    $i--;
                    continue;
                }
            }
        }

        $challenge = implode(':', [
            Str::random(5),
            $data['uuid'],
            GDAlgorithm::decode(substr($data['chk'], 5), Keys::CHALLENGES->value),
            $data['udid'],
            $data['accountID'] ?? 0,
            app(Carbon::class)->secondsUntilEndOfDay(),
            $this->generateChallengeInfo($challenges[0]),
            $this->generateChallengeInfo($challenges[1]),
            $this->generateChallengeInfo($challenges[2])
        ]);

        $challengeString = GDAlgorithm::encode($challenge, Keys::CHALLENGES->value, sha1: false);
        return Str::random(5) . $challengeString . '|' . sha1($challengeString . Salts::CHALLENGE->value);
    }

    protected function generateChallengeInfo(Challenge $challenge): string
    {
        return implode(',', [
            $challenge->id,
            $challenge->type,
            $challenge->collect,
            $challenge->reward,
            $challenge->name
        ]);
    }
}
