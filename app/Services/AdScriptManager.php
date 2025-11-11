<?php

declare(strict_types=1);

namespace N8nAutomation\Services;

use Illuminate\Contracts\Support\Arrayable;
use N8nAutomation\Contracts\AdScriptManagerInterface;
use N8nAutomation\Enums\AdScriptStatus;
use N8nAutomation\Models\AdScript;

class AdScriptManager implements AdScriptManagerInterface
{
    public function store(Arrayable $dto): AdScript
    {
        return AdScript::create($dto->toArray());
    }

    public function storeResult(AdScript $adScript, string $newScript, string $analysis): AdScript
    {
        $adScript->update([
            'new_script' => $newScript,
            'analysis' => $analysis,
            'status' => AdScriptStatus::COMPLETED,
        ]);

        return $adScript->fresh();
    }

    public function markAsFailed(AdScript $adScript, string $message): void
    {
        $adScript->update([
            'status' => AdScriptStatus::FAILED,
            'error_message' => $message,
        ]);
    }
}
