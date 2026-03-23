<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfValido implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Limpa o CPF (remove pontuação se houver)
        $cpf = preg_replace('/\D/', '', $value);

        // Verifica se tem 11 dígitos ou se é uma sequência repetida (111.111...)
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Cálculo matemático dos dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $fail('O CPF informado é inválido.');
                return;
            }
        }
    }
}
