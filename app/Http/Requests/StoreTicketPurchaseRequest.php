<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketPurchaseRequest extends FormRequest
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
            'event_id' => 'required|exists:events,id',
            'email' => 'required|email',
            'surname' => 'required|min:2',
            'other_names' => 'required|min:2',
            'phone' => 'required|size:12',
            'tickets' => 'required|array|min:1',
            'tickets.*.ticket_id' => 'required',
            'tickets.*.number_of_tickets' => 'required|integer'
        ];
    }
}
