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
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4 items-start">
    <div class="flex flex-col gap-2 mb-4">
      <img src="{{ asset('storage/' . $pendaftaran->pas_foto_peserta_didik->path_dokumen) }}" alt="{{ $pendaftaran->pas_foto_peserta_didik->nama_dokumen }}" class="mx-auto max-w-3xs rounded-4xl" />
      <img src="{{ asset('storage/' . $pendaftaran->ktp_orang_tua->path_dokumen) }}" alt="{{ $pendaftaran->ktp_orang_tua->nama_dokumen }}" class="mx-auto max-w-3xs rounded-4xl" />
    </div>
    <x-card class="max-w-lg">
      <form>
        <x-form-group>
          <x-forms.input :old="true" label="Nama Peserta Didik" name="nama-anak" :value="$pendaftaran->nama_anak" disabled />
        </x-form-group>
        <x-form-group>
          <x-forms.input :old="true" label="Nama Panggilan" name="nama-panggilan" :value="$pendaftaran->nama_panggilan" disabled />
        </x-form-group>

        <x-form-group>
          <x-forms.select label="Jenis Kelamin" name="jenis-kelamin" :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']" :selected="$pendaftaran->jenis_kelamin" disabled />
        </x-form-group>

        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Tempat Lahir" name="tempat-lahir" :value="$pendaftaran->tempat_lahir" disabled />
          </x-form-group>
          <x-form-group class="w-full">
            <x-forms.input :old="true" type="date" label="Tanggal Lahir" name="tanggal-lahir" :value="$pendaftaran->tanggal_lahir" disabled />
          </x-form-group>
        </div>
        <x-form-group>
          <x-forms.input :old="true" label="Agama" name="agama" :value="$pendaftaran->agama" disabled />
        </x-form-group>
        <x-form-group>
          <x-forms.input :old="true" label="Anak Ke" name="anak-ke" :value="$pendaftaran->anak_ke" disabled />
        </x-form-group>
        <x-form-group>
          <x-forms.select label="Status dalam keluarga" name="status-anak" :options="$status_anak" :selected="$pendaftaran->status_anak" disabled />
        </x-form-group>

        <x-form-group>
          <x-forms.select id="status-penanggung-jawab" label="Status Penanggung Jawab" name="status-penanggung-jawab" :options="['orang-tua' => 'Orang Tua', 'wali' => 'Wali']" :selected="old('status-penanggung-jawab', isset($pendaftaran->nama_wali) && $pendaftaran->nama_wali ? 'wali' : 'orang-tua')" disabled />
        </x-form-group>
  
        <div id="field-orang-tua">
          <div class="flex flex-col sm:flex-row sm:gap-4">
            <x-form-group class="w-full">
              <x-forms.input :old="true" label="Nama Ayah" name="nama-ayah" :value="$pendaftaran->nama_ayah ?? null" disabled />
            </x-form-group>
            <x-form-group class="w-full">
              <x-forms.input :old="true" label="Nama Ibu" name="nama-ibu" :value="$pendaftaran->nama_ibu ?? null" disabled />
            </x-form-group>
          </div>
          <div class="flex flex-col sm:flex-row sm:gap-4">
            <x-form-group class="w-full">
              <x-forms.input :old="true" label="Pekerjaan Ayah" name="pekerjaan-ayah" :value="$pendaftaran->pekerjaan_ayah ?? null" disabled />
            </x-form-group>
            <x-form-group class="w-full">
              <x-forms.input :old="true" label="Pekerjaan Ibu" name="pekerjaan-ibu" :value="$pendaftaran->pekerjaan_ibu ?? null" disabled />
            </x-form-group>
          </div>
        </div>
  
        <div id="field-wali" class="hidden">
          <x-form-group>
            <x-forms.input :old="true" label="Nama Wali" name="nama-wali" :value="$pendaftaran->nama_wali ?? null" disabled />
          </x-form-group>
          <x-form-group>
            <x-forms.input :old="true" label="Pekerjaan Wali" name="pekerjaan-wali" :value="$pendaftaran->pekerjaan_wali ?? null" disabled />
          </x-form-group>
        </div>

        <x-form-group>
          <x-forms.textarea :old="true" label="Alamat" name="alamat" :value="$pendaftaran->alamat" disabled />
        </x-form-group>
        <x-form-group>
          <x-forms.input :old="true" label="Kelurahan" name="kelurahan" :value="$pendaftaran->kelurahan" disabled />
        </x-form-group>
        <x-form-group>
          <x-forms.input :old="true" label="Nomor Telepon" name="no-telp" :value="$pendaftaran->no_telp" disabled />
        </x-form-group>
        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Email" name="email" :value="$pendaftaran->email ?? null" disabled />
          </x-form-group>
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Nomor Whatsapp" name="no-wa" :value="$pendaftaran->no_wa ?? null" disabled />
          </x-form-group>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Imunisasi vaksin yang pernah diterima" name="imunisasi-vaksin-yang-pernah-diterima" :value="$pendaftaran->imunisasi_vaksin_yang_pernah_diterima" disabled />
          </x-form-group>
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Penyakit berat yang pernah diderita" name="penyakit-berat-yang-diderita" :value="$pendaftaran->penyakit_berat_yang_diderita" disabled />
          </x-form-group>
        </div>
        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Jarak dari tempat tinggal ke sekolah" name="jarak-dari-rumah" :value="$pendaftaran->jarak_dari_rumah" disabled />
          </x-form-group>
          <x-form-group class="w-full">
            <x-forms.input :old="true" label="Golongan Darah" name="golongan-darah" :value="$pendaftaran->golongan_darah" disabled />
          </x-form-group>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-4">
          <x-form-group>
            <x-forms.file label="Akta Kelahiran" note="file berjenis pdf maks 2MB" name="akta-kelahiran" :existingFilename="$pendaftaran->akta_kelahiran->path_dokumen" disabled />
          </x-form-group>
          <x-form-group>
            <x-forms.file label="Kartu Keluarga" note="file berjenis pdf maks 2MB" name="kartu-keluarga" :existingFilename="$pendaftaran->kartu_keluarga->path_dokumen" disabled />
          </x-form-group>
        </div>
        <x-form-group>
          <x-preview-image id="preview-ktp-orang-tua" />
          <x-forms.file label="KTP Orang Tua" note="file dapat berjenis jpg,jpeg,png maks 2MB" name="ktp-orang-tua" onChange="previewImage('ktp-orang-tua', 'preview-ktp-orang-tua')" :existingFilename="$pendaftaran->ktp_orang_tua->path_dokumen" disabled />
        </x-form-group>
        <x-form-group>
          <x-preview-image id="preview-pas-foto-peserta-didik" />
          <x-forms.file label="PAS Foto Peserta Didik" note="file dapat berjenis jpg,jpeg,png maks 2MB" name="pas-foto-peserta-didik" onChange="previewImage('pas-foto-peserta-didik', 'preview-pas-foto-peserta-didik')" :existingFilename="$pendaftaran->pas_foto_peserta_didik->path_dokumen" disabled />
        </x-form-group>
      </form>
      <form action="{{ route('store.verification', [$pendaftaran->slug]) }}" method="post" class="mt-4">
        @csrf @method('POST')
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Verifikasi Pendaftaran</h2>
  
        <x-form-group id="form-group-kelas">
          <x-forms.select label="Kelas" name="kelas" :options="$kelasList" />
        </x-form-group>
        <x-form-group>
          <x-forms.textarea :old="true" label="Catatan" name="catatan" row="3" value="Pendaftaran anda berhasil/gagal diverifikasi, periksa data atau dokumen yang anda berikan./anda dapat melakukan daftar ulang ke sekolah." />
        </x-form-group>
        <x-form-group>
          <x-forms.select label="Hasil Verifikasi" name="hasil_verifikasi" :options="['Diterima' => 'Diterima', 'Ditolak' => 'Ditolak']" />
        </x-form-group>
  
        <x-button type="submit" class="w-full">Verifikasi</x-button>
      </form>
    </x-card>
  </div>
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

    function toggleKelasField() {
      const hasil = $('[name="hasil_verifikasi"]').val();
      if (hasil === 'Ditolak') {
        $('#form-group-kelas').addClass('hidden');
      } else {
        $('#form-group-kelas').removeClass('hidden');
      }
    }

    toggleFields();
    toggleKelasField();

    $('#status-penanggung-jawab').on('change', toggleFields);
    $('[name="hasil_verifikasi"]').on('change', toggleKelasField);
  });
</script>