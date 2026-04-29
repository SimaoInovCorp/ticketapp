<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'subject'            => ['required', 'string', 'max:255'],
            'inbox_id'           => ['required', 'integer', 'exists:inboxes,id'],
            'ticket_type_id'     => ['nullable', 'integer', 'exists:ticket_types,id'],
            'entity_id'          => ['nullable', 'integer', 'exists:entities,id'],
            'operator_id'        => ['nullable', 'integer', 'exists:users,id'],
            'knowledge_emails'   => ['nullable', 'array', 'max:5'],
            'knowledge_emails.*' => ['email', 'max:255'],
            'message'            => ['required', 'string', 'min:10', 'max:5000'],
            'attachments'        => ['nullable', 'array', 'max:10'],
            'attachments.*'      => ['file', 'max:10240', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,zip'],
        ];

        if ($this->user()->isOperator()) {
            $rules['contact_id'] = ['required', 'integer', 'exists:contacts,id'];
        } else {
            $rules['contact_id'] = ['nullable', 'integer', 'exists:contacts,id'];
        }

        return $rules;
    }
}
