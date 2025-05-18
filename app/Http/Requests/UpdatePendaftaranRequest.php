<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePendaftaranRequest extends FormRequest
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
        $statusPenanggungJawab = $this->input('status-penanggung-jawab');

        return [
            'nama-anak' => ['required', 'string'],
            'nama-panggilan' => ['required', 'string'],
            'jenis-kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tempat-lahir' => ['required', 'string'],
            'tanggal-lahir' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(5)->toDateString()],
            'agama' => ['required', 'string'],
            'anak-ke' => ['required', 'numeric'],
            'status-anak' => ['required', Rule::in([
                'anak-kandung', 'anak-tiri', 'anak-angkat', 'anak-asuh', 'anak-angkat-siri', 'anak-tiri-siri', 'anak-dalam-perwalian', 'lainnya'
            ])],
            'status-penanggung-jawab' => ['required', Rule::in(['orang-tua', 'wali'])],

            'nama-ayah' => [$statusPenanggungJawab === 'wali' ? 'nullable' : 'required', 'string'],
            'nama-ibu' => [$statusPenanggungJawab === 'wali' ? 'nullable' : 'required', 'string'],
            'pekerjaan-ayah' => [$statusPenanggungJawab === 'wali' ? 'nullable' : 'required', 'string'],
            'pekerjaan-ibu' => [$statusPenanggungJawab === 'wali' ? 'nullable' : 'required', 'string'],

            'nama-wali' => [$statusPenanggungJawab === 'orang-tua' ? 'nullable' : 'required', 'string'],
            'pekerjaan-wali' => [$statusPenanggungJawab === 'orang-tua' ? 'nullable' : 'required', 'string'],

            'alamat' => ['required', 'string'],
            'kelurahan' => ['required', 'string'],
            'no-telp' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'no-wa' => ['nullable', 'string'],
            'imunisasi-vaksin-yang-pernah-diterima' => ['required', 'string'],
            'penyakit-berat-yang-diderita' => ['required', 'string'],
            'jarak-dari-rumah' => ['required', 'string'],
            'golongan-darah' => ['required', 'string'],

            'akta-kelahiran' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'kartu-keluarga' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'ktp-orang-tua' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'pas-foto-peserta-didik' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ':attribute wajib diisi.',
            '*.email' => ':attribute harus berupa alamat email yang valid.',
            '*.mimes' => ':attribute harus berupa file dengan tipe: :values.',
            '*.max' => 'Ukuran berkas :attribute tidak boleh lebih dari :max kilobyte.',
            '*.before' => ':attribute tidak boleh diisi dengan tanggal setelah hari ini.',
            '*.before_or_equal' => ':attribute menunjukkan usia minimal harus 5 tahun.',
            '*.in' => 'Pilihan untuk :attribute tidak valid.',

            'status-penanggung-jawab.in' => 'Status penanggung jawab harus bernilai "orang tua" atau "wali".',
            'jenis-kelamin.in' => 'Jenis kelamin harus dipilih antara "Laki-laki" atau "Perempuan".',
            'status-anak.in' => 'Status anak tidak sesuai pilihan yang tersedia.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama-anak' => 'Nama peserta didik',
            'nama-panggilan' => 'Nama panggilan',
            'jenis-kelamin' => 'Jenis kelamin',
            'tempat-lahir' => 'Tempat lahir',
            'tanggal-lahir' => 'Tanggal lahir',
            'agama' => 'Agama',
            'anak-ke' => 'Anak ke',
            'status-anak' => 'Status dalam keluarga',
            'status-penanggung-jawab' => 'Status penanggung jawab',
            'nama-ayah' => 'Nama ayah',
            'nama-ibu' => 'Nama ibu',
            'pekerjaan-ayah' => 'Pekerjaan ayah',
            'pekerjaan-ibu' => 'Pekerjaan ibu',
            'nama-wali' => 'Nama wali',
            'pekerjaan-wali' => 'Pekerjaan wali',
            'alamat' => 'Alamat',
            'kelurahan' => 'Kelurahan',
            'no-telp' => 'Nomor telepon',
            'email' => 'Email',
            'no-wa' => 'Nomor whatsapp',
            'imunisasi-vaksin-yang-pernah-diterima' => 'Imunisasi vaksin yang pernah diterima',
            'penyakit-berat-yang-diderita' => 'Penyakit berat yang diderita',
            'jarak-dari-rumah' => 'Jarak dari tempat tinggal ke sekolah',
            'golongan-darah' => 'Golongan darah',
            'akta-kelahiran' => 'Akta kelahiran',
            'kartu-keluarga' => 'Kartu keluarga',
            'ktp-orang-tua' => 'KTP orang tua',
            'pas-foto-peserta-didik' => 'PAS foto peserta didik',
        ];
    }
}
