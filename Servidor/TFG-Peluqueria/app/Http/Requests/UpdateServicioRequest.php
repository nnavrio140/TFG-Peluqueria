<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServicioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->isAdminOrEmploye()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_servicio' => 'required|string|max:255',
            'descripcion_corta' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'duracion' => 'required|integer',
            'imagen' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'nombre_servicio.required' => 'El nombre del servicio es obligatorio.',
            'nombre_servicio.string' => 'El nombre del servicio debe ser una cadena de texto.',
            'nombre_servicio.max' => 'El nombre del servicio no debe exceder los 255 caracteres.',

            'descripcion_corta.required' => 'La descripción corta es obligatoria.',
            'descripcion_corta.string' => 'La descripción corta debe ser una cadena de texto.',
            'descripcion_corta.max' => 'La descripción corta no debe exceder los 150 caracteres.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',

            'duracion.required' => 'La duración es obligatoria.',
            'duracion.integer' => 'La duración debe ser un número entero.',

            'imagen.image' => 'El archivo debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'imagen.max' => 'La imagen no debe pesar más de 4 MB.',
        ];
    }
}