<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
        $rules = [
            'quantity' => 'required|integer|min:0',
        ];

        // No update, o stock já está na URL; product_id só é obrigatório no store.
        if (! $this->route('stock')) {
            $rules['product_id'] = ['required', 'exists:products,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'O campo produto é obrigatório.',

            'quantity.required' => 'O campo quantidade é obrigatório.',
            'quantity.integer' => 'O valor deve ser inteiro.',
            'quantity.min' => 'Mínimo 1 carácter.',
        ];
    }
}
