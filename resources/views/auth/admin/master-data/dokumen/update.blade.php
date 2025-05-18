<x-layout>
  <x-card class="max-w-lg mx-auto">
    <x-slot:header>
      <h1 class="text-2xl font-semibold text-gray-800">Ubah Dokumen</h1>
      <p class="text-sm text-gray-500">Perbarui informasi dokumen di bawah ini.</p>
    </x-slot:header>

    <form action="{{ route('update.data-document', [$pendaftaran?->slug ?? 'default', $dokumen->slug]) }}" method="POST" enctype="multipart/form-data">
      @csrf @method('PATCH')

      @if ($pendaftaran !== null && $pendaftaran->slug !== null)
        <x-form-group>
          <x-forms.select :old="true" label="Nama Dokumen" name="nama_dokumen" :options="$typeList" :selected="$dokumen->nama_dokumen" />
        </x-form-group>
        <x-form-group>
          <x-forms.select :old="true" label="Nama Pendaftar" name="nama_pendaftar" :options="$pendaftaranList" :selected="$pendaftaran->slug" />
        </x-form-group>
      @else
        <div class="text-gray-800 mb-4">
          <p><strong>Nama Dokumen:</strong> {{ $dokumen->nama_dokumen }}</p>
          <p><strong>Dokumen digunakan untuk aplikasi.</strong></p>
          <p><strong>Lihat dokumen</strong> <a href="{{ asset('storage/' . $dokumen->path_dokumen) }}" target="_blank" class="underline text-blue-600 hover:text-blue-800">klik disini.</a></p>
        </div>
      @endif

      <x-form-group>
        <x-preview-image id="img-preview-dokumen" :src="$dokumen->path_dokumen" />
        <x-forms.file label="Dokumen" name="dokumen" onChange="previewImage('dokumen', 'img-preview-dokumen')" :existingFilename="$dokumen->path_dokumen" />
      </x-form-group>

      <a href="{{ route('show.data-document') }}">
        <x-button color="bg-gray-600">Kembali</x-button>
      </a>
      <x-button type="submit" color="bg-blue-600">Perbarui</x-button>
    </form>
  </x-card>
</x-layout>