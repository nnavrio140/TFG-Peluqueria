<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
/**
 * Elimina la contraseña del request si está vacía antes de validar.
 * Si el usuario deja el campo de contraseña vacío
 * Al editar su perfil, no se cambia la contraseña en la base de datos.
 * Es como que se ejecuta un "if" antes de la validación.
 */
    protected function prepareForValidation(): void
    {
        if (empty($this->password)) {
            $this->request->remove('password');
            $this->request->remove('password_confirmation');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:usuarios,email,' . $this->route('usuario')->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string'   => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max'      => 'El campo nombre no debe exceder los 255 caracteres.',
            'email.required'  => 'El campo email es obligatorio.',
            'email.email'     => 'El campo email debe ser una dirección de correo electrónico válida.',
            'email.unique'    => 'El email ya está en uso por otro usuario.',
            'role_id.required'  => 'El campo rol es obligatorio.',
            'role_id.exists'    => 'El rol seleccionado no es válido.',
            'password.string' => 'El campo contraseña debe ser una cadena de texto.',
            'password.min'    => 'El campo contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ];
    }
}
