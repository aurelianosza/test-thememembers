<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class BuyerFactory extends Factory
{
    public static function generateRandomValidCpf() : string
    {
        
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
    
        $cpf = implode('', $cpf);    
        
        return  substr($cpf, 0, 3) . '.' .
                substr($cpf, 3, 3) . '.' .
                substr($cpf, 6, 3) . '-' .
                substr($cpf, 9, 2);
    }

    public function definition(): array
    {
        return [
            "name"      => fake()->name(),
            "document"  => self::generateRandomValidCpf(),
            "email"     => fake()->safeEmail()
        ];
    }
}
