<?php

namespace App\Http\Requests\Entity;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOperator();
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ0-9\',\.\s&()\/-]+$/u'],
            'nif'            => ['nullable', 'string', 'max:20', 'regex:/^\d{9}$/'], // 9 digits for PT NIF
            'email'          => ['nullable', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:20', 'regex:/^\+?\d{9,15}$/'],
            'mobile'         => ['nullable', 'string', 'max:20', 'regex:/^\+?\d{9,15}$/'],
            'website'        => ['nullable', 'url', 'max:255'],
            'internal_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
