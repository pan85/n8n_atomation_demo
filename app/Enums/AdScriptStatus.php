<?php

declare(strict_types=1);

namespace N8nAutomation\Enums;

enum AdScriptStatus: int
{
    case PENDING = 1;

    case COMPLETED = 2;

    case FAILED = 3;

    public function getStatusName(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
        };
    }

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, AdScriptStatus::cases());
    }
}
