<?php

namespace App\Http\Requests;

use App\Rules\SellerValidator;
use Illuminate\Foundation\Http\FormRequest;

class CreateSellerFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'username' => ['required', 'string', 'min:3', new SellerValidator()],
            'email' => ['required', 'email', new SellerValidator()],
        ];
    }
}
