<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class TicketRequest extends FormRequest
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
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => ['required', Rule::in(['Technical', 'Billing', 'General'])],
            'priority' => ['required', Rule::in(['Low', 'Medium', 'High'])],
            'attachment' => 'nullable|string',
            'status' => ['required', Rule::in(['Open', 'In-progress', 'Resolved', 'Closed'])],
        ];
    }
}
