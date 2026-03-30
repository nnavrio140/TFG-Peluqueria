<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    /**
     * Verifica si el usuario tiene permiso para crear o editar servicios.
     * Solo admin o empleado pueden gestionar servicios.
     */
    public function authorize(): bool
    {
         if($this->user()->isAdminOrEmploye()){
            return true;
        }
        return false;
    }

    /**
     * Reglas de validación para crear o actualizar un servicio.
     */
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_servicio' => 'required|string|max:50',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'duracion' => 'required|integer',
        ];
    }
    /**
     * Mensajes personalizados para los errores de validación del servicio.
     */
    public function messages()
    {
        return [
            'nombre_servicio.required' => 'El nombre del servicio es obligatorio.',
            'nombre_servicio.string' => 'El nombre del servicio debe ser una cadena de texto.',
            'nombre_servicio.max' => 'El nombre del servicio no debe exceder los 50 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
            'duracion.required' => 'La duración es obligatoria.',
            'duracion.integer' => 'La duración debe ser un número entero.',
        ];
    }
}
