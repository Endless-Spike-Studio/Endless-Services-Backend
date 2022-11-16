<?php

namespace App\Providers;

use App\Exceptions\GDCS\WebException;
use App\Models\GDCS\Account;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Request::macro('context', function () {
            /** @var Request $this */

            $parameters = [];
            foreach ($this->all() as $key => $value) {
                if (in_array($key, ['gjp', 'password'], true)) {
                    $length = strlen($value);
                    $parameters[$key] = str_repeat('*', $length);
                    continue;
                }

                $parameters[$key] = Str::limit($value);
            }

            return [
                'ip' => $this->ip(),
                'url' => $this->fullUrl(),
                'data' => $parameters
            ];
        });

        RateLimiter::for('gdcs_remote', function () {
            /** @var Account|null $account */
            $account = Auth::guard('gdcs')->user();
            $key = 'gdcs_remote:' . (empty($account) ? Request::ip() : $account->id);

            return Limit::perHour(50)
                ->by($key)
                ->response(function () {
                    throw new WebException(
                        __('gdcn.tools.error.too_many_request', [
                            'seconds' => Arr::get(func_get_arg(1), 'Retry-After', '未知')
                        ])
                    );
                });
        });

        Schema::defaultStringLength(191);
    }
}
