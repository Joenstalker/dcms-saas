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
            'pricing_plan_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $plan = \App\Models\PricingPlan::find($value);
                    if (!$plan) {
                        $fail('The selected subscription plan is invalid.');
                    } elseif (!$plan->is_active) {
                        $fail('The selected subscription plan is no longer available.');
                    }
                },
            ],
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
