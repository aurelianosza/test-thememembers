<?php

namespace App\Http\Requests;

use App\Rules\CpfValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
        // dd(array_keys(config('payments.gateways')));

        return [
            "amount"            => ["required", "numeric", "gt:0"],
            "payment_method"    => ["required", Rule::in(array_keys(config('payments.gateways')))],
            "products"          => ["required", "array", "min:1"], //Aceita apenas produtos existentes
            "products.*.code"   => ["required", "exists:products,code"],
            "products.*.amount" => ["required", "gt:0"],
            "buyer_document"    => ["required", "string", new CpfValidation, "exists:buyers,document"], 
        ];
    }

    public function attributes()
    {
        return [
            "amount"            => __("validation.attributes.money"),
            "products"          => __("validation.attributes.products_list"),
            "products.*.code"   => __("validation.attributes.product_code"),
            "products.*.code"   => __("validation.attributes.item_amount"),
        ];
    }
}
