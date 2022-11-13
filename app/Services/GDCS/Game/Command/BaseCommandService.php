<?php

namespace App\Services\GDCS\Game\Command;

use App\Models\GDCS\Account;
use App\Models\GDCS\Level;

class BaseCommandService
{
    protected bool $valid = false;
    protected string $name = '';
    protected int $time = 98978399;
    protected array $arguments = [];
    protected array $parameters = [];
    protected array $internalFunctions = [];

    public function __construct(
        protected string  $data,
        protected Account $account,
        protected ?Level  $level = null
    )
    {
        $commandStart = config('gdcn.game.command.start', '!');
        $argumentStart = config('gdcn.game.command.argument.start', ':');
        $argumentDelimiter = config('gdcn.game.command.argument.delimiter', '=');

        if (str_starts_with($this->data, $commandStart)) {
            $parts = explode(' ', $this->data);
            $this->name = ltrim($parts[0], $commandStart);

            foreach ($parts as $part) {
                if (str_starts_with($part, $argumentStart)) {
                    [$key, $value] = explode($argumentDelimiter, $part);
                    $this->arguments[$key] = $value;
                } else {
                    $this->parameters[] = $part;
                }
            }

            $this->valid = true;
        }
    }

    public function valid(): bool
    {
        return $this->valid;
    }

    public function execute(): string
    {
        $unavailableCommands = array_merge(['__construct', 'valid', 'formatMessage', 'execute'], $this->internalFunctions);

        if (!$this->valid || in_array($this->name, $unavailableCommands, true)) {
            return $this->formatMessage(__('gdcn.game.command.unavailable'));
        }

        if (!method_exists($this, $this->name)) {
            return $this->formatMessage(__('gdcn.game.command.not_found'));
        }

        return $this->formatMessage($this->{$this->name}(...$this->parameters));
    }

    protected function formatMessage(string $value): string
    {
        return 'temp_' . $this->time . '_' . $value;
    }
}
