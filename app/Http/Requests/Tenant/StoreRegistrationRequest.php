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
                'regex:/^[a-zA-Z0-9\s\-\'\.&]+$/u', // Allow letters, numbers, spaces, hyphens, apostrophes, periods, and ampersands
            ],
            'subdomain' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'alpha_dash',
                'unique:tenants,slug',
                'regex:/^[a-z0-9\-_]+$/', // Only lowercase, numbers, hyphens, underscores
            ],
            'address' => [
                'nullable',
                'string',
                'max:500',
                'min:5',
            ],

            // Owner Information
            'owner_name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z\s\-\'\.]+$/u', // Allow letters, spaces, hyphens, apostrophes, periods
            ],
            'email' => [
                'required',
                'email:rfc,dns', // Strict email validation with DNS check
                'max:255',
                'unique:users,email',
                'unique:tenants,email',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'min:10',
                'regex:/^[\+]?[0-9\s\-\(\)]+$/', // Allow numbers, spaces, hyphens, parentheses, and optional +
                'unique:tenants,phone',
            ],

            // Login Credentials
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', // At least one lowercase, one uppercase, one number
            ],

            // Terms
            'terms' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            // Clinic Name
            'clinic_name.required' => 'Clinic name is required.',
            'clinic_name.min' => 'Clinic name must be at least 3 characters.',
            'clinic_name.max' => 'Clinic name cannot exceed 255 characters.',
            'clinic_name.regex' => 'Clinic name contains invalid characters. Only letters, numbers, spaces, hyphens, apostrophes, periods, and ampersands are allowed.',

            // Subdomain
            'subdomain.required' => 'Subdomain is required.',
            'subdomain.min' => 'Subdomain must be at least 3 characters.',
            'subdomain.max' => 'Subdomain cannot exceed 255 characters.',
            'subdomain.alpha_dash' => 'Subdomain can only contain letters, numbers, dashes, and underscores.',
            'subdomain.unique' => 'This subdomain is already taken. Please choose another one.',
            'subdomain.regex' => 'Subdomain must be lowercase and can only contain letters, numbers, hyphens, and underscores.',

            // Address
            'address.min' => 'Address must be at least 5 characters if provided.',
            'address.max' => 'Address cannot exceed 500 characters.',

            // Owner Name
            'owner_name.required' => 'Owner name is required.',
            'owner_name.min' => 'Owner name must be at least 2 characters.',
            'owner_name.max' => 'Owner name cannot exceed 255 characters.',
            'owner_name.regex' => 'Owner name contains invalid characters. Only letters, spaces, hyphens, apostrophes, and periods are allowed.',

            // Email
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'email.unique' => 'This email address is already registered. Please use a different email or try logging in.',

            // Phone
            'phone.min' => 'Phone number must be at least 10 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.regex' => 'Phone number contains invalid characters. Only numbers, spaces, hyphens, parentheses, and optional + are allowed.',
            'phone.unique' => 'This phone number is already registered. Please use a different phone number.',

            // Password
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password cannot exceed 255 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',

            // Terms
            'terms.required' => 'You must accept the terms and conditions.',
            'terms.accepted' => 'You must accept the terms and conditions.',
        ];
    }

    public function attributes(): array
    {
        return [
            'clinic_name' => 'clinic name',
            'subdomain' => 'subdomain',
            'address' => 'address',
            'owner_name' => 'owner name',
            'email' => 'email address',
            'phone' => 'phone number',
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
            'terms' => 'terms and conditions',
        ];
    }
}
