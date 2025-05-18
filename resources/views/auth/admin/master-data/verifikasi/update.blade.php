<x-layout>
  <x-card class="max-w-lg mx-auto">
    <x-slot:header>
      <h1 class="text-2xl font-semibold text-gray-800">Ubah Verifikasi</h1>
      <p class="text-sm text-gray-500">Perbarui informasi Verifikasi di bawah ini.</p>
    </x-slot:header>

    <form action="{{ route('update.data-verification', [$pendaftaran->slug, $verifikasi->slug]) }}" method="POST">
      @csrf @method('PATCH')

      <x-form-group>
        <x-forms.select label="Nama Pendaftar" name="pendaftar" :options="$pendaftaranList" :selected="$pendaftaran->slug" />
      </x-form-group>
      <x-form-group id="form-group-kelas">
        <x-forms.select label="Kelas" name="kelas" :options="$kelasList" :selected="$pendaftaran->kelas?->slug" />
      </x-form-group>
      <x-form-group>
        <x-forms.textarea label="Catatan" name="catatan" row="3" :value="$verifikasi->catatan" />
      </x-form-group>
      <x-form-group>
        <x-forms.select label="Hasil Verifikasi" name="hasil_verifikasi" :options="['Diterima' => 'Diterima', 'Ditolak' => 'Ditolak']" :selected="$verifikasi->hasil_verifikasi" />
      </x-form-group>

      <a href="{{ route('show.data-verification') }}">
        <x-button color="bg-gray-600">Kembali</x-button>
      </a>
      <x-button type="submit" color="bg-blue-600">Perbarui</x-button>
    </form>
  </x-card>
</x-layout>
<script>
  $(document).ready(function () {
    function toggleKelasField() {
      const hasil = $('[name="hasil_verifikasi"]').val();
      if (hasil === 'Ditolak') {
        $('#form-group-kelas').addClass('hidden');
      } else {
        $('#form-group-kelas').removeClass('hidden');
      }
    }

    toggleKelasField();

    $('[name="hasil_verifikasi"]').on('change', toggleKelasField);
  });
</script>