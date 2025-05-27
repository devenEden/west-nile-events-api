<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'event_id' => ['required'],
            'tickets' =>  ['required', 'array', 'min:1'],
            'tickets.*.type' => ['required', 'string'],
            'tickets.*.event_id' => ['required', 'numeric'],
            'tickets.*.price' => ['numeric'],
            'tickets.*.capacity' => ['required', 'numeric'],
            'tickets.*.is_free' => ['boolean'],
        ];
    }
}
