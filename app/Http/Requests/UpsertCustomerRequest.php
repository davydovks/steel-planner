<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertCustomerRequest extends FormRequest
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
            'name' => ['required', Rule::unique('customers')->ignore($this->customer), 'max:150'],
            'customer_type_id' => 'required|exists:customer_types,id',
            'address' => 'nullable|max:255',
            'TIN' => ['nullable', Rule::unique('customers')->ignore($this->customer), 'max:20'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => __('validation.unique_n'),
            'name.max' => __('validation.max.string_n'),
        ];
    }
}
