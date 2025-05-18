@php
  $status_anak = [
    'anak-kandung' => 'Anak Kandung',
    'anak-tiri' => 'Anak Tiri',
    'anak-angkat' => 'Anak Angkat',
    'anak-asuh' => 'Anak Asuh',
    'anak-angkat-siri' => 'Anak Angkat Siri',
    'anak-tiri-siri' => 'Anak Tiri Siri',
    'anak-dalam-perwalian' => 'Anak Dalam Perwalian',
    'lainnya' => 'Lainnya'
  ];
@endphp
<x-layout>
  <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-md">
    <p class="font-semibold">Kesulitan dalam mengisi formulir?</p>
    <p>Jika pendaftaran online terasa membingungkan, silakan <a download="Formulir Pendaftaran" href="{{ asset('storage/formulir_pendaftaran_pg.pdf') }}" class="underline text-blue-600 hover:text-blue-800">unduh formulir dalam bentuk PDF</a> untuk dicetak dan diisi secara manual, <a download="Detail Pendaftaran" href="{{ asset('storage/detail_pendaftaran_pg.pdf') }}" class="underline text-blue-600 hover:text-blue-800">unduh informasi pendaftaran untuk lebih detail.</a></p>
    <p class="mt-2">Hubungi kami melalui WhatsApp untuk bantuan lebih lanjut: <a href="https://wa.me/6285162603794" target="_blank" class="text-blue-600 underline font-semibold">+62 812-3456-7890</a></p>
  </div>
  <form action="{{ route('store.pendaftaran') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
    @csrf @method('POST')

    <x-card class="mx-auto">
      <x-form-group>
        <x-forms.input label="Nama Lengkap Peserta Didik" name="nama-anak" />
      </x-form-group>
      <x-form-group>
        <x-forms.input label="Nama Panggilan" name="nama-panggilan" />
      </x-form-group>

      <x-form-group>
        <x-forms.select label="Jenis Kelamin" name="jenis-kelamin" :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']" />
      </x-form-group>
      
      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group class="w-full">
          <x-forms.input label="Tempat Lahir" name="tempat-lahir" />
        </x-form-group>
        <x-form-group class="w-full">
          <x-forms.input type="date" label="Tanggal Lahir" name="tanggal-lahir" />
        </x-form-group>
      </div>
      <x-form-group>
        <x-forms.input label="Agama" name="agama" />
      </x-form-group>
      <x-form-group>
        <x-forms.input label="Anak Ke" name="anak-ke" />
      </x-form-group>
      <x-form-group>
        <x-forms.select label="Status dalam keluarga" name="status-anak" :options="$status_anak" />
      </x-form-group>

      <x-form-group>
        <x-forms.select id="status-penanggung-jawab" label="Status Penanggung Jawab" name="status-penanggung-jawab" :options="['orang-tua' => 'Orang Tua', 'wali' => 'Wali']" :selected="old('status-penanggung-jawab', 'orang-tua')" />
      </x-form-group>

      <div id="field-orang-tua">
        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group class="w-full">
            <x-forms.input label="Nama Ayah" name="nama-ayah" />
          </x-form-group>
          <x-form-group class="w-full">
            <x-forms.input label="Nama Ibu" name="nama-ibu" />
          </x-form-group>
        </div>
        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group class="w-full">
            <x-forms.input label="Pekerjaan Ayah" name="pekerjaan-ayah" />
          </x-form-group>
          <x-form-group class="w-full">
            <x-forms.input label="Pekerjaan Ibu" name="pekerjaan-ibu" />
          </x-form-group>
        </div>
      </div>

      <div id="field-wali" class="hidden">
        <x-form-group>
          <x-forms.input label="Nama Wali" name="nama-wali" />
        </x-form-group>
        <x-form-group>
          <x-forms.input label="Pekerjaan Wali" name="pekerjaan-wali" />
        </x-form-group>
      </div>
    </x-card>

    <x-card>
      <x-form-group>
        <x-forms.textarea label="Alamat" name="alamat" />
      </x-form-group>
      <x-form-group>
        <x-forms.input label="Kelurahan" name="kelurahan" />
      </x-form-group>
      <x-form-group>
        <x-forms.input label="Nomor Telepon" name="no-telp" />
      </x-form-group>
      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group class="w-full">
          <x-forms.input label="Email" name="email" />
        </x-form-group>
        <x-form-group class="w-full">
          <x-forms.input label="Nomor Whatsapp" name="no-wa" />
        </x-form-group>
      </div>
      
      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group class="w-full">
          <x-forms.input label="Imunisasi vaksin yang pernah diterima" name="imunisasi-vaksin-yang-pernah-diterima" />
        </x-form-group>
        <x-form-group class="w-full">
          <x-forms.input label="Penyakit berat yang pernah diderita" name="penyakit-berat-yang-diderita" />
        </x-form-group>
      </div>
      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group class="w-full">
          <x-forms.input label="Jarak dari tempat tinggal ke sekolah" name="jarak-dari-rumah" />
        </x-form-group>
        <x-form-group class="w-full">
          <x-forms.input label="Golongan Darah" name="golongan-darah" />
        </x-form-group>
      </div>

      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group>
          <x-forms.file label="Akta Kelahiran" note="file berjenis pdf maks 2MB" name="akta-kelahiran" />
        </x-form-group>
        <x-form-group>
          <x-forms.file label="Kartu Keluarga" note="file berjenis pdf maks 2MB" name="kartu-keluarga" />
        </x-form-group>
      </div>
      <x-form-group>
        <x-preview-image id="preview-ktp-orang-tua" />
        <x-forms.file label="KTP Orang Tua" note="file dapat berjenis jpg,jpeg,png maks 2MB" name="ktp-orang-tua" onChange="previewImage('ktp-orang-tua', 'preview-ktp-orang-tua')" />
      </x-form-group>
      <x-form-group>
        <x-preview-image id="preview-pas-foto-peserta-didik" />
        <x-forms.file label="PAS Foto Peserta Didik" note="file dapat berjenis jpg,jpeg,png maks 2MB" name="pas-foto-peserta-didik" onChange="previewImage('pas-foto-peserta-didik', 'preview-pas-foto-peserta-didik')" />
      </x-form-group>
      
      <x-button type="submit" class="w-full">Upload Data</x-button>
    </x-card>
  </form>
</x-layout>
<script>
  $(document).ready(function () {
    function toggleFields() {
      const value = $('#status-penanggung-jawab').val();
      if (value === 'wali') {
        $('#field-orang-tua').addClass('hidden');
        $('#field-wali').removeClass('hidden');
      } else {
        $('#field-orang-tua').removeClass('hidden');
        $('#field-wali').addClass('hidden');
      }
    }

    $('#status-penanggung-jawab').on('change', toggleFields);

    toggleFields();
  });
</script>