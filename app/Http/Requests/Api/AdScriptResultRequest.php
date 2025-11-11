<?php

declare(strict_types=1);

namespace N8nAutomation\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdScriptResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        $bearer = $this->bearerToken();

        return $bearer === env('N8N_BEARER_TOKEN');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'task_id' => 'required|integer|exists:ad_script_tasks,id',
            'new_script' => 'required|string',
            'analysis' => 'required|string',
        ];
    }

    public function getNewScript(): string
    {
        return $this->input('new_script');
    }

    public function getAnalysis(): string
    {
        return $this->input('analysis');
    }

    public function getTaskId(): int
    {
        return $this->input('task_id');
    }
}
