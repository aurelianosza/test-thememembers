<?php

namespace App\Http\Requests\Product;

use App\Rules\CpfValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "code"          => ["required", "string", "max:16", "unique:products,code"],
            "name"          => ["required", "string", "max:64"],
            "description"   => ["present",  "string", "max:255"],
            "price"         => ["required", "numeric","gt:0", "lt:99999"]
        ];
    }

    public function productPayload()
    {
        return [
            "code"          => $this->code,
            "name"          => $this->name,
            "description"   => $this->description,
            "price"         => $this->price,
        ];
    }

    public function attributes()
    {
        return [
            "code"      => __("validation.attributes.product_code"),
            "price"     => __("validation.attributes.price"),
        ];
    }
}
