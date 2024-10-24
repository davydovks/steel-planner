<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Str;

class StorePartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $input = $this->all();
        $input['position'] = Str::replace("\xc2\xa0", '', $input['position']);
        $input['weight'] = Str::replace("\xc2\xa0", '', $input['weight']);
        $input['weight'] = Str::replace(",", '.', $input['weight']);
        if (strlen($input['weight'])) {
            $input['weight'] = round((float) $input['weight'] * 1000);
        }
        $this->replace($input);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'position' => ['required', 'numeric', 'max:2000000000', Rule::unique('parts')
                ->where(fn(Builder $query) => $query->where('order_id', $this->order_id))
                ->ignore($this->part)
            ],
            'order_id' => 'required|exists:orders,id',
            'weight' => 'required|integer|numeric|gt:0',
            'profile' => 'required|string'
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
            'position.max' => __('validation.max.numeric_f'),
        ];
    }
}
