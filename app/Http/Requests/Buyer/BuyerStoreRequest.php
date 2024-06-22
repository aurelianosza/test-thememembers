<?php

namespace App\Http\Requests\Buyer;

use App\Rules\CpfValidation;
use Illuminate\Foundation\Http\FormRequest;

class BuyerStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name"      => ["required", "string", "max:255"],
            "email"     => ["required", "string", "unique:buyers"],
            "document"  => ["required", "string", "unique:buyers", new CpfValidation], 
        ];
    }
}
