<?php

declare(strict_types=1);

namespace N8nAutomation\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use N8nAutomation\Models\AdScript;

interface AdScriptManagerInterface
{
    public function store(Arrayable $dto): Model;

    public function storeResult(AdScript $adScript, string $newScript, string $analysis): Model;

    public function markAsFailed(AdScript $adScript, string $message): void;
}
