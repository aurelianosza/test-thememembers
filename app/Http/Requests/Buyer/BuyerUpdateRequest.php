<?php

namespace App\Http\Requests\Buyer;

use App\Rules\CpfValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuyerUpdateRequest extends FormRequest
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
            "name"      => ["required", "string", "max:255"],
            "email"     => ["required", "string", Rule::unique("buyers", "email")->ignore($this->route('buyer')->id)],
            "document"  => ["required", "string", Rule::unique("buyers", "document")->ignore($this->route('buyer')->id), new CpfValidation], 
        ];
    }
}
