<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'nama' => 'required|unique:user,nama|min:3|string',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:8|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute sudah terdaftar.',
            'min' => ':attribute minimal :min karakter.',
            'string' => ':attribute harus berupa teks.',
            'email' => 'Format email tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama Lengkap',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}
