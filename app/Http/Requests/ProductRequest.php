<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            "name"=> "required|max:255|string",
            "price"=> 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'name.string' => 'O nome deve ser um texto válido',

            'price.required' => 'O campo preço é obrigatório.',
            'price.min:10' => 'O valor deve ter no minimo 1 caracteres.',
            'price.numeric' => 'O valor deve ser um número ',
        ];
    }
}
