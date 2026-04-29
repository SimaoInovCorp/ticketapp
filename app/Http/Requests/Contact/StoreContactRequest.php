<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOperator();
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ\',\.\s-]+$/u'],
            'email'           => ['nullable', 'email', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:20', 'regex:/^\+?\d{9,15}$/'],
            'mobile'          => ['nullable', 'string', 'max:20', 'regex:/^\+?\d{9,15}$/'],
            'contact_role_id' => ['nullable', 'exists:contact_roles,id'],
            'internal_notes'  => ['nullable', 'string', 'max:1000'],
            'entity_ids'      => ['required', 'array', 'min:1'],
            'entity_ids.*'    => ['exists:entities,id'],
        ];
    }
}
