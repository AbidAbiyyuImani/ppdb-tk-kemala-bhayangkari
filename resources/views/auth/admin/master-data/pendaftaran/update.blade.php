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
  <form action="{{ route('update.data-registration', [$pendaftaran->user->slug, $pendaftaran->slug]) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
    @csrf @method('PATCH')
    
    <x-card class="mx-auto">
      <x-slot:header>
        <h1 class="text-2xl font-semibold text-gray-800">Ubah Pendaftaran</h1>
        <p class="text-sm text-gray-500">Perbarui informasi pendaftaran di bawah ini.</p>
      </x-slot:header>
      
      <x-form-group>
        <x-forms.input :old="true" label="Nama Lengkap Peserta Didik" name="nama-anak" :value="$pendaftaran->nama_anak" />
      </x-form-group>
      <x-form-group>
        <x-forms.input :old="true" label="Nama Panggilan" name="nama-panggilan" :value="$pendaftaran->nama_panggilan" />
      </x-form-group>

      <x-form-group>
        <x-forms.select label="Jenis Kelamin" name="jenis-kelamin" :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']" :selected="$pendaftaran->jenis_kelamin" />
      </x-form-group>

      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group class="w-full">
          <x-forms.input :old="true" label="Tempat Lahir" name="tempat-lahir" :value="$pendaftaran->tempat_lahir" />
        </x-form-group>
        <x-form-group class="w-full">
          <x-forms.input :old="true" type="date" label="Tanggal Lahir" name="tanggal-lahir" :value="$pendaftaran->tanggal_lahir" />
        </x-form-group>
      </div>
      <x-form-group>
        <x-forms.input :old="true" label="Agama" name="agama" :value="$pendaftaran->agama" />
      </x-form-group>
      <x-form-group>
        <x-forms.input :old="true" label="Anak Ke" name="anak-ke" :value="$pendaftaran->anak_ke" />
      </x-form-group>
      <x-form-group>
        <x-forms.select label="Status dalam keluarga" name="status-anak" :options="$status_anak" :selected="$pendaftaran->status_anak" />
      </x-form-group>

      <x-form-group>
        <x-forms.select id="status-penanggung-jawab" label="Status Penanggung Jawab" name="status-penanggung-jawab" :options="['orang-tua' => 'Orang Tua', 'wali' => 'Wali']" :selected="old('status-penanggung-jawab', isset($pendaftaran->nama_wali) && $pendaftaran->nama_wali ? 'wali' : 'orang-tua')" />
      </x-form-group>

      <div id="field-orang-tua">
        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Nama Ayah" name="nama-ayah" :value="$pendaftaran->nama_ayah ?? null" />
          </x-form-group>
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Nama Ibu" name="nama-ibu" :value="$pendaftaran->nama_ibu ?? null" />
          </x-form-group>
        </div>
        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Pekerjaan Ayah" name="pekerjaan-ayah" :value="$pendaftaran->pekerjaan_ayah ?? null" />
          </x-form-group>
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Pekerjaan Ibu" name="pekerjaan-ibu" :value="$pendaftaran->pekerjaan_ibu ?? null" />
          </x-form-group>
        </div>
      </div>

      <div id="field-wali" class="hidden">
        <x-form-group>
          <x-forms.input :old="true" label="Nama Wali" name="nama-wali" :value="$pendaftaran->nama_wali ?? null" />
        </x-form-group>
        <x-form-group>
          <x-forms.input :old="true" label="Pekerjaan Wali" name="pekerjaan-wali" :value="$pendaftaran->pekerjaan_wali ?? null" />
        </x-form-group>
      </div>
    </x-card>

    <x-card>
      <x-form-group>
        <x-forms.textarea :old="true" label="Alamat" name="alamat" :value="$pendaftaran->alamat" />
      </x-form-group>
      <x-form-group>
        <x-forms.input :old="true" label="Kelurahan" name="kelurahan" :value="$pendaftaran->kelurahan" />
      </x-form-group>
      <x-form-group>
        <x-forms.input :old="true" label="Nomor Telepon" name="no-telp" :value="$pendaftaran->no_telp" />
      </x-form-group>
      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group class="w-full">
          <x-forms.input :old="true" label="Email" name="email" :value="$pendaftaran->email ?? null" />
        </x-form-group>
        <x-form-group class="w-full">
          <x-forms.input :old="true" label="Nomor Whatsapp" name="no-wa" :value="$pendaftaran->no_wa ?? null" />
        </x-form-group>
      </div>

      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group class="w-full">
          <x-forms.input :old="true" label="Imunisasi vaksin yang pernah diterima" name="imunisasi-vaksin-yang-pernah-diterima" :value="$pendaftaran->imunisasi_vaksin_yang_pernah_diterima" />
        </x-form-group>
        <x-form-group class="w-full">
          <x-forms.input :old="true" label="Penyakit berat yang pernah diderita" name="penyakit-berat-yang-diderita" :value="$pendaftaran->penyakit_berat_yang_diderita" />
        </x-form-group>
      </div>
      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group class="w-full">
          <x-forms.input :old="true" label="Jarak dari tempat tinggal ke sekolah" name="jarak-dari-rumah" :value="$pendaftaran->jarak_dari_rumah" />
        </x-form-group>
        <x-form-group class="w-full">
          <x-forms.input :old="true" label="Golongan Darah" name="golongan-darah" :value="$pendaftaran->golongan_darah" />
        </x-form-group>
      </div>

      <div class="flex flex-col sm:flex-row sm:gap-4">
        <x-form-group>
          <x-forms.file label="Akta Kelahiran" note="file berjenis pdf maks 2MB" name="akta-kelahiran" :existingFilename="$pendaftaran->akta_kelahiran->path_dokumen" />
        </x-form-group>
        <x-form-group>
          <x-forms.file label="Kartu Keluarga" note="file berjenis pdf maks 2MB" name="kartu-keluarga" :existingFilename="$pendaftaran->kartu_keluarga->path_dokumen" />
        </x-form-group>
      </div>
      <x-form-group>
        <x-preview-image id="preview-ktp-orang-tua" :src="$pendaftaran->ktp_orang_tua->path_dokumen" />
        <x-forms.file label="KTP Orang Tua" note="file dapat berjenis jpg,jpeg,png maks 2MB" name="ktp-orang-tua" onChange="previewImage('ktp-orang-tua', 'preview-ktp-orang-tua')" :existingFilename="$pendaftaran->ktp_orang_tua->path_dokumen" />
      </x-form-group>
      <x-form-group>
        <x-preview-image id="preview-pas-foto-peserta-didik" :src="$pendaftaran->pas_foto_peserta_didik->path_dokumen" />
        <x-forms.file label="PAS Foto Peserta Didik" note="file dapat berjenis jpg,jpeg,png maks 2MB" name="pas-foto-peserta-didik" onChange="previewImage('pas-foto-peserta-didik', 'preview-pas-foto-peserta-didik')" :existingFilename="$pendaftaran->pas_foto_peserta_didik->path_dokumen" />
      </x-form-group>
      
      <a href="{{ route('show.data-registration') }}">
        <x-button color="bg-gray-600" class="w-full">Kembali</x-button>
      </a>
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