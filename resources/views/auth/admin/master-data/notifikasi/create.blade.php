<x-layout>
  <x-card class="max-w-lg mx-auto">
    <x-slot:header>
      <h1 class="text-2xl font-semibold text-gray-800">Tambah Notifikasi</h1>
      <p class="text-sm text-gray-500">Silakan isi form berikut untuk menambah notifikasi baru.</p>
    </x-slot:header>
    
    <form action="{{ route('store.data-notification') }}" method="POST">
      @csrf @method('POST')

      <x-form-group>
        <x-forms.select label="Untuk" name="penerima" :options="$users"  />
      </x-form-group>
      <x-form-group>
        <x-forms.input label="Judul" name="judul" />
      </x-form-group>
      <x-form-group>
        <x-forms.textarea label="Pesan" name="pesan" rows="2" />
      </x-form-group>

      <a href="{{ route('show.data-notification') }}">
        <x-button color="bg-gray-600">Kembali</x-button>
      </a>
      <x-button type="submit" color="bg-blue-600">Tambah</x-button>
    </form>
  </x-card>
</x-layout>