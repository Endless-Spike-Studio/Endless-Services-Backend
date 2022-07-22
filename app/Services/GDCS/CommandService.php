<?php

namespace App\Services\GDCS;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class CommandService
{
    protected string $name;

    protected array $parameters = [];

    protected array $arguments = [];

    protected array $options = [];

    protected array $disabledCommands = [];

    protected array $booleanMapper = [
        'false' => false,
        'true' => true,
        'no' => false,
        'yes' => true,
        'off' => false,
        'on' => true,
        '0' => false,
        '1' => true,
    ];

    public function __construct(
        protected string  $token,
        protected Account $account,
        protected ?Level  $level = null
    )
    {
        if ($this->isValid()) {
            $this->parse();
        }
    }

    public function isValid(): bool
    {
        return Str::startsWith(
            $this->token,
            config('gdcs.command.start', '!')
        );
    }

    protected function parse(): void
    {
        $commandStart = config('gdcs.command.start', '!');
        $argumentStart = config('command.argument_start_with', '--');
        $argumentDelimiter = config('command.argument_delimiter', '=');
        $optionStart = config('command.option_start_with', '-');

        $parts = explode(' ', $this->token);
        $sign = array_shift($parts);
        $this->name = Str::replace($commandStart, '', $sign);

        foreach ($parts as $part) {
            if (Str::startsWith($part, $argumentStart)) {
                if (!Str::contains($part, $argumentDelimiter)) {
                    $this->options[] = Str::replace($argumentStart, '', $part);
                    continue;
                }

                [$key, $value] = explode(config('command.argument_delimiter', '='), $part);
                $realKey = Str::replace($argumentStart, '', $key);
                $this->arguments[$realKey] = $value;
            } elseif (Str::startsWith($part, $optionStart)) {
                $this->options[] = Str::replace($optionStart, '', $part);
            } else {
                $this->parameters[] = $part;
            }
        }
    }

    public function execute(): string
    {
        $unavailableCommands = array_merge(['__construct'], $this->disabledCommands);

        return 'temp_98978399_' . (!Str::contains($this->name, $unavailableCommands) && method_exists($this, $this->name) ? App::call([$this, $this->name]) : 'Command not found');
    }
}
