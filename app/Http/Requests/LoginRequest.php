<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|exists:user,email',
            'password' => 'required|min:8|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong.',
            'min' => ':attribute minimal :min karakter.',
            'email' => 'Format email tidak valid.',
            'exists' => 'Email tidak terdaftar.',
            'string' => ':attribute harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}
