<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TurmaRequest extends FormRequest
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
            "professor_id" => 'required',
            "curso_id" => 'required',
            "grau" => 'required|max:25',
            "horario" => 'required|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            "professor_id.required" => 'Informe o professor da turma.',
            "curso_id.required" => 'Informe o curso relacionado a turma.',
            "grau.required" => 'Informe o grau da turma.',
            "grau.max" => 'O grau deve ter no máximo 25 caracteres',
            "horario.required" => 'Informe o horário da turma',
            "horario.max" => 'O horário deve ter no máximo 5 caracteres',

        ];
    }
}
