<x-layout>
  <x-card class="max-w-md mx-auto">
    {{-- <x-slot:header>
      <h1 class="text-2xl font-semibold text-center">Register</h1>
      <p class="text-sm text-center">Daftar untuk membuat akun baru</p>
    </x-slot:header> --}}

    <form action="{{ route('register') }}" method="post">
      @csrf @method('POST')
      
      <x-form-group>
        <x-forms.input label="Nama Lengkap" type="text" name="nama"/>
      </x-form-group>
      <x-form-group>
        <x-forms.input label="Email" type="email" name="email"/>
      </x-form-group>
      <x-form-group>
        <x-forms.input label="Password" type="password" name="password"/>
      </x-form-group>

      <x-button type="submit" class="w-full">Register</x-button>
      <a href="{{ route('show.login') }}" class="text-blue-600 underline">Sudah mempunyai akun? masuk sekarang!</a>
    </form>
  </x-card>
</x-layout>