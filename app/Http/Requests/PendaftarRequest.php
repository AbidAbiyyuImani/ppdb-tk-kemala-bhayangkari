<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftarRequest extends FormRequest
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
    public function rules()
    {
        return [
            'nama_peserta_didik' => 'required|string',
            'nama_panggilan' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nama_orang_tua' => 'required|string',
            'no_kk' => 'required|numeric',
            'foto_ktp_orang_tua' => 'nullable|image|max:10240',
            'foto_peserta_didik' => 'nullable|image|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'image' => ':attribute harus berupa gambar.',
            'max' => ':attribute tidak boleh lebih dari :max kilobyte.',
        ];
    }

    public function attributes()
    {
        return [
            'nama_peserta_didik' => 'Nama Peserta Didik',
            'nama_panggilan' => 'Nama Panggilan',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'nama_orang_tua' => 'Nama Orang Tua',
            'no_kk' => 'Nomor Kartu Keluarga',
            'foto_ktp_orang_tua' => 'Foto KTP Orang Tua',
            'foto_peserta_didik' => 'PAS Foto Peserta Didik',
        ];
    }
}
