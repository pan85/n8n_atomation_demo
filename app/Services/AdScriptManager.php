<?php

declare(strict_types=1);

namespace N8nAutomation\Services;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use N8nAutomation\Contracts\AdScriptManagerInterface;
use N8nAutomation\Models\AdScript;

class AdScriptManager implements AdScriptManagerInterface
{
    public function store(Arrayable $dto): Model
    {
        return AdScript::create($dto->toArray());
    }
}
