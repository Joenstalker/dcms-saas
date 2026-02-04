<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Clinic Information
            'clinic_name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z0-9\s\-\'\.&]+$/u',
                function ($attribute, $value, $fail) {
                    $normalizedName = strtolower(trim($value));
                    $exists = \App\Models\Tenant::where('name', $normalizedName)->exists();
                    if ($exists) {
                        $fail('A clinic with this name already exists. Please choose a different name.');
                    }
                },
            ],
            'desired_subdomain' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'alpha_dash',
                'regex:/^[a-z0-9\-]+$/', // Only lowercase, numbers, hyphens per requirement
                function ($attribute, $value, $fail) {
                    $normalizedSubdomain = strtolower(trim($value));
                    $exists = \App\Models\Tenant::where('slug', $normalizedSubdomain)->exists();
                    if ($exists) {
                        $fail('That clinic URL is already taken. Try a different one 💡');
                    }
                },
            ],
            'address' => [
                'nullable',
                'string',
                'max:500',
                'min:5',
            ],
            'city' => [
                'required',
                'string',
                'max:100',
            ],
            'state_province' => [
                'required',
                'string',
                'max:100',
            ],

            // Owner Information
            'full_name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z\s\-\'\.]+$/u',
                function ($attribute, $value, $fail) {
                    $normalizedName = strtolower(trim($value));
                    $exists = \App\Models\User::where('name', $normalizedName)->exists();
                    if ($exists) {
                        $fail('This owner name is already registered. Please use a different name.');
                    }
                },
            ],
            'email' => [
                'required',
                config('app.env') === 'local' ? 'email' : 'email:rfc,dns',
                'max:255',
                function ($attribute, $value, $fail) {
                    $normalizedEmail = strtolower(trim($value));
                    $existsInUsers = \App\Models\User::where('email', $normalizedEmail)->exists();
                    $existsInTenants = \App\Models\Tenant::where('email', $normalizedEmail)->exists();
                    if ($existsInUsers || $existsInTenants) {
                        $fail('This email is already registered. Try logging in instead.');
                    }
                },
            ],
            'phone_number' => [
                'nullable',
                'string',
                'max:20',
                'min:10',
                'regex:/^[\+]?[0-9\s\-\(\)]+$/',
                function ($attribute, $value, $fail) {
                    if (empty($value)) return;
                    $normalizedPhone = preg_replace('/[\s\-\(\)]/', '', $value);
                    $exists = \App\Models\Tenant::whereNotNull('phone')
                        ->get()
                        ->filter(function ($tenant) use ($normalizedPhone) {
                            $tenantPhone = preg_replace('/[\s\-\(\)]/', '', $tenant->phone ?? '');
                            return $tenantPhone === $normalizedPhone;
                        })
                        ->isNotEmpty();

                    if ($exists) {
                        $fail('This phone number is already registered. Please use a different phone number.');
                    }
                },
            ],

            // Login Credentials
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            ],

            // Terms
            'terms_accepted' => 'required|accepted',
            
            // Pricing Plan
            // Pricing Plan
            'pricing_plan_id' => [
                'nullable',
                \Illuminate\Validation\Rule::requiredIf($this->has('pricing_plan_id')),
                'exists:pricing_plans,id'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'clinic_name.required' => 'Clinic name is required.',
            'clinic_name.min' => 'Clinic name must be at least 3 characters.',
            'clinic_name.regex' => 'Clinic name contains invalid characters.',
            'desired_subdomain.required' => 'Clinic URL is required.',
            'desired_subdomain.regex' => 'Clinic URL must be lowercase and can only contain letters, numbers, and hyphens.',
            'full_name.required' => 'Owner name is required.',
            'email.required' => 'Email address is required.',
            'password.confirmed' => 'Your passwords don’t match. Let’s fix that 😊',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions.',
        ];
    }

    public function attributes(): array
    {
        return [
            'clinic_name' => 'clinic name',
            'desired_subdomain' => 'clinic URL',
            'address' => 'address',
            'full_name' => 'full name',
            'email' => 'email address',
            'phone_number' => 'phone number',
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
            'terms_accepted' => 'terms and conditions',
            'state_province' => 'state/province',
        ];
    }
}
