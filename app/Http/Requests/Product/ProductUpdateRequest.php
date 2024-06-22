<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            "code"          => ["required", "string", "max:255"],
            "name"          => ["required", "string", "max:255"],
            "description"   => ["present",  "string", "max:255"],
            "price"         => ["required", "gt:0", "numeric", "max:99999"]
        ];
    }

    public function productPayload()
    {
        return [
            "name"          => $this->name,
            "description"   => $this->description,
            "price"         => $this->price,
        ];
    }
}
