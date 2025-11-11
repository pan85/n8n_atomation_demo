<?php

declare(strict_types=1);

namespace N8nAutomation\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdScriptRequest extends FormRequest
{
    public function authorize(): bool
    {
        $bearer = $this->bearerToken();

        return $bearer === env('STORE_BEARER_TOKEN');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'reference_script' => 'required|string',
            'outcome_description' => 'required|string',
        ];
    }

    public function getReferenceScript(): string
    {
        return $this->input('reference_script');
    }

    public function getOutcomeDescription(): string
    {
        return $this->input('outcome_description');
    }
}
