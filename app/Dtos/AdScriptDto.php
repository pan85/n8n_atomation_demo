<?php

declare(strict_types=1);

namespace N8nAutomation\Dtos;

use Illuminate\Contracts\Support\Arrayable;
use N8nAutomation\Enums\AdScriptStatus;

readonly class AdScriptDto implements Arrayable
{
    public function __construct(
        public string $referenceScript,
        public string $outcomeDescription,
        public AdScriptStatus $status,
    ) {
    }

    public function toArray(): array
    {
        return [
            'reference_script' => $this->referenceScript,
            'outcome_description' => $this->outcomeDescription,
            'status' => $this->status->value,
        ];
    }
}
