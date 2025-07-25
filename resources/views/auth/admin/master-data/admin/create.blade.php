<x-layout>
  <x-card class="max-w-lg mx-auto">
    <x-slot:header>
      <h1 class="text-2xl font-semibold text-gray-800">Tambah Admin</h1>
      <p class="text-sm text-gray-500">Silakan isi form berikut untuk menambah admin baru.</p>
    </x-slot:header>

    <form action="{{ route('store.data-admin') }}" method="post">
      @csrf @method('POST')

      <x-form-group>
        <x-forms.input label="Nama" name="nama" />
      </x-form-group>
      <x-form-group>
        <x-forms.input type="email" label="Email" name="email" />
      </x-form-group>
      <x-form-group>
        <x-forms.input type="password" label="Password" name="password" />
      </x-form-group>
      
      <a href="{{ route('show.data-admin') }}">
        <x-button color="bg-gray-600">Kembali</x-button>
      </a>
      <x-button type="submit" color="bg-blue-600">Tambah</x-button>
    </form>
  </x-card>
</x-layout>