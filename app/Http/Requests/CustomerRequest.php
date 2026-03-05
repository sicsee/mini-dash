<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            "email"=> 'required|email|string',
            "phone" => ['required', 'string', 'regex:/^\d{10,11}$/'],

        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'name.string' => 'O nome deve ser um texto válido',

            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Deve ser um email válido',
            'email.string' => 'O valor deve ser um texto válido ',

            'phone.required' => 'O telefone é obrigatório.',
            'phone.regex' => 'Digite um telefone válido com DDD (ex: 11987654321).',
        ];
    }
}
