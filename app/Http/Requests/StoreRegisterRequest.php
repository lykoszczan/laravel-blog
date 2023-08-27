<?php

namespace App\Http\Requests;

use App\Rules\PasswordLength;
use App\Rules\UserNameLength;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', new UserNameLength],
            'email' => 'required|email|unique:users',
            'password' => ['required', new PasswordLength],
        ];
    }
}
