<?php

declare(strict_types=1);

namespace N8nAutomation\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface AdScriptManagerInterface
{
    public function store(Arrayable $dto);
}
