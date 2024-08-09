<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
            'name' => "required",
            'email' => "required|email|unique:users",
            "password" => "required"
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo electronico es Obligatorio',
            'email.email' => 'El correo no es valido'
        ];
    }
}
