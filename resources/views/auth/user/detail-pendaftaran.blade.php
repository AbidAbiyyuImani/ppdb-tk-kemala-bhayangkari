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
  $status = $pendaftaran->status_pendaftaran;
  $color = match($status) {
    'Diterima' => 'bg-green-100 text-green-800 border-green-300',
    'Ditolak' => 'bg-red-100 text-red-800 border-red-300',
    'Diajukan' => 'bg-gray-100 text-gray-800 border-gray-300',
    default => 'bg-gray-100 text-gray-800 border-gray-300',
  };
@endphp
<x-layout>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4 items-start">
    <div class="flex flex-col gap-2 mb-4">
      <img src="{{ asset('storage/' . $pendaftaran->pas_foto_peserta_didik->path_dokumen) }}" alt="{{ $pendaftaran->pas_foto_peserta_didik->nama_dokumen }}" class="mx-auto max-w-3xs rounded-4xl" />
      <img src="{{ asset('storage/' . $pendaftaran->ktp_orang_tua->path_dokumen) }}" alt="{{ $pendaftaran->ktp_orang_tua->nama_dokumen }}" class="mx-auto max-w-3xs rounded-4xl" />
    </div>
    <x-card class="max-w-lg">
      <div class="p-4 rounded-xl border text-center {{ $color }} shadow-sm mb-4">
          <h2 class="text-lg font-semibold text-gray-800">Status Pendaftaran: {{ $status }}</h2>
      
          @if (!empty($pendaftaran->verifikasi->catatan))
              <div class="mt-2 text-sm text-gray-500">
                  <p>{{ $pendaftaran->verifikasi->catatan }}</p>
              </div>
          @endif
      </div>    
      <form action="{{ route('update.detail-pendaftaran', [$pendaftaran->user->slug, $pendaftaran->slug]) }}" method="post" enctype="multipart/form-data">
        @csrf @method('PATCH')

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

        <x-button class="w-full" id="edit-toggle" :disabled="$status === 'Diterima' ? true : false">Ubah Data</x-button>
        <x-button type="submit" class="w-full" id="save-button" disabled>Simpan</x-button>
      </form>
    </x-card>
  </div>
</x-layout>
<script>
  $(document).ready(function () {
    let isEditing = false;

    $('#edit-toggle').on('click', function (event) {
      event.preventDefault();
      isEditing = !isEditing;

      $('input, textarea, select').prop('disabled', !isEditing);
      $('.h-files').toggleClass('hidden', !isEditing);
      $('#save-button').prop('disabled', !isEditing);

      if (isEditing) {
        $(this).removeClass('bg-yellow-500').addClass('bg-red-500').text('Batalkan');
      } else {
        $(this).removeClass('bg-red-500').addClass('bg-yellow-500').text('Ubah Data');
      }
    });

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