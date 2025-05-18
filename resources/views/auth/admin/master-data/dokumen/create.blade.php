<x-layout>
  <x-card class="max-w-lg mx-auto">
    <x-slot:header>
      <h1 class="text-2xl font-semibold text-gray-800">Tambah Dokumen</h1>
      <p class="text-sm text-gray-500">Silakan isi form berikut untuk menambah dokumen baru.</p>
    </x-slot:header>

    <form action="{{ route('store.data-document') }}" method="POST" enctype="multipart/form-data">
      @csrf @method('POST')

      <x-form-group>
        <x-forms.select label="Nama Dokumen" name="nama_dokumen" :options="$typeList" />
      </x-form-group>
      <x-form-group>
        <x-forms.select label="Nama Pendaftar" name="nama_pendaftar" :options="$pendaftaranList" />
      </x-form-group>
      <x-form-group>
        <x-preview-image id="img-preview-dokumen" />
        <x-forms.file label="Dokumen" name="dokumen" onChange="previewImage('dokumen', 'img-preview-dokumen')" />
      </x-form-group>

      <a href="{{ route('show.data-document') }}">
        <x-button color="bg-gray-600">Kembali</x-button>
      </a>
      <x-button type="submit" color="bg-blue-600">Tambah</x-button>
    </form>
  </x-card>
</x-layout>