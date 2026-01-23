<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class SelectPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pricing_plan_id' => 'required|exists:pricing_plans,id',
        ];
    }

    public function messages(): array
    {
        return [
            'pricing_plan_id.required' => 'Please select a subscription plan.',
            'pricing_plan_id.exists' => 'The selected plan is invalid.',
        ];
    }
}
