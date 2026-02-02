<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //Permitir a administradores y empleados y propiertarios de la cita
        if($this->user()->isAdminOrEmploye() || $this->user()->id == $this->cita->user_id){
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
           'fecha' => 'required|date',
           'hora_inicio' => 'required',
           'user_id' => 'required|exists:usuarios,id',
           'id_empleado' => 'required|exists:empleados,id',
           'id_servicio' => 'required|exists:servicios,id',
           'id_estado' => 'required|exists:estados,id',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'id_empleado.required' => 'El empleado es obligatorio.',
            'id_empleado.exists' => 'El empleado seleccionado no existe.',
            'id_servicio.required' => 'El servicio es obligatorio.',
            'id_servicio.exists' => 'El servicio seleccionado no existe.',
            'id_estado.required' => 'El estado es obligatorio.',
            'id_estado.exists' => 'El estado seleccionado no existe.',
        ];
    }
}
