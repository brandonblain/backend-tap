<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Mensajes de error
     */
    public function messages(): array
    {
        return [
            'username.required' => 'El correo electrónico (username) es obligatorio.',
            'username.email' => 'Por favor, introduce un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }
}