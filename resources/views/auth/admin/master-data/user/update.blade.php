<x-layout>
  <x-card class="max-w-lg mx-auto">
    <x-slot:header>
      <h1 class="text-2xl font-semibold text-gray-800">Ubah User</h1>
      <p class="text-sm text-gray-500">Perbarui informasi user di bawah ini.</p>
    </x-slot:header>

    <form action="{{ route('update.data-user', ['user' => $user->slug]) }}" method="post">
      @csrf @method('PATCH')

      <x-form-group>
        <x-forms.input :old="true" label="Nama" name="nama" :value="$user->nama" />
      </x-form-group>
      <x-form-group>
        <x-forms.input :old="true" type="email" label="Email" name="email" :value="$user->email" />
      </x-form-group>
      <x-form-group>
        <x-forms.input type="password" label="Password Baru (Opsional)" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah" />
      </x-form-group>

      <a href="{{ route('show.data-user') }}">
        <x-button color="bg-gray-600">Kembali</x-button>
      </a>
      <x-button type="submit" color="bg-blue-600">Perbarui</x-button>
    </form>
  </x-card>
</x-layout>