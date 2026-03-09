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
                // 1. Ensure email is unique within the tenant's staff (mongodb connection)
                Rule::unique('mongodb.users', 'email'),
                // 2. Ensure email is unique within the central database (mongodb_central connection)
                // This prevents adding the owner or any other central account as staff
                Rule::unique('mongodb_central.users', 'email'),
            ],
            'role' => 'required|string|in:dentist,assistant',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered in your clinic.',
            'role.required' => 'Please select a role.',
            'role.in' => 'Role must be either dentist or assistant.',
            'status.required' => 'Status is required.',
        ];
    }
}
