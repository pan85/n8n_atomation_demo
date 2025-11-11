<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use N8nAutomation\Enums\AdScriptStatus;
use N8nAutomation\Models\AdScript;

/**
 * @extends Factory<AdScript>
 */
class AdScriptFactory extends Factory
{
    protected $model = AdScript::class;

    public function definition(): array
    {
        return [
            'reference_script' => $this->faker->text(),
            'outcome_description' => $this->faker->text(),
            'new_script' => null,
            'analysis' => null,
            'status' => $this->faker->randomElement(AdScriptStatus::cases()),
            'error_message' => null,
        ];
    }

    public function setReferenceScript(string $referenceScript): self
    {
        return $this->state(fn() => ['reference_script' => $referenceScript]);
    }

    public function setOutcomeDescription(string $outcomeDescription): self
    {
        return $this->state(fn() => ['outcome_description' => $outcomeDescription]);
    }

    public function setStatus(AdScriptStatus $status): self
    {
        return $this->state(fn() => ['status' => $status]);
    }

    public function setError(string $errorMessage): self
    {
        return $this->state(fn() => ['error_message' => $errorMessage]);
    }
}
