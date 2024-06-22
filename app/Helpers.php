<?php

// create me a php function to generate valids cpf numbers
if(!function_exists('generate_random_valid_cpf'))
{
    function  generate_random_valid_cpf() : string {
        $cpf = [];
    
        // Generate first 9 digits
        for ($i = 0; $i < 9; $i++) {
            $cpf[] = rand(0, 9);
        }
    
        // Calculate the first check digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $firstCheckDigit = 11 - ($sum % 11);
        if ($firstCheckDigit >= 10) {
            $firstCheckDigit = 0;
        }
        $cpf[] = $firstCheckDigit;
    
        // Calculate the second check digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $secondCheckDigit = 11 - ($sum % 11);
        if ($secondCheckDigit >= 10) {
            $secondCheckDigit = 0;
        }
        $cpf[] = $secondCheckDigit;
    
        // Convert the array to a string
        $cpfString = implode('', $cpf);
        return $cpfString;
    
    }
}
