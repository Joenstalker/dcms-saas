<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only owner can add users to their tenant
        return $this->user() && $this->user()->isOwner();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->where('tenant_id', $this->user()->tenant_id);
                }),
            ],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:dentist,assistant',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered in your clinic.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'role.required' => 'Please select a role.',
            'role.in' => 'Role must be either dentist or assistant.',
        ];
    }
}
