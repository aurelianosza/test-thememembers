<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfValidation implements ValidationRule
{
    /**
     * Validador customizado de cpf.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $value))
        {
            $fail(__("validation.cpf.with_pointing"));
        }

        // Remove caracteres não numéricos
        $cpf = preg_replace("/[^0-9]/", "", $value);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            $fail(__("validation.cpf.length")); //mensagem de erro
            return;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match("/(\d)\1{10}/", $cpf)) {
            $fail(__("validation.cpf.invalid"));
            return;
        }

        // Calcula os dígitos verificadores para validação
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $fail(__("validation.cpf.invalid"));
                return;
            }
        }
    }
}
