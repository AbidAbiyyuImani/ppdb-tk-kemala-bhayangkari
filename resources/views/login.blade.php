<x-layout>
  <x-card class="max-w-md mx-auto">
    {{-- <x-slot:header>
      <h1 class="text-2xl font-semibold text-center">Login</h1>
      <p class="text-sm text-center">Masuk untuk melanjutkan</p>
    </x-slot:header> --}}

    <form action="{{ route('login') }}" method="post">
      @csrf @method('POST')
      
      <x-form-group>
        <x-forms.input label="Email" type="email" name="email"/>
      </x-form-group>
      <x-form-group>
        <x-forms.input label="Password" type="password" name="password"/>
      </x-form-group>
      <x-input-group>
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember Me</label>
      </x-input-group>
      
      <x-button type="submit" class="w-full">Login</x-button>
      <a href="{{ route('show.register') }}" class="text-blue-600 underline">Belum mempunyai akun? daftar sekarang!</a>
    </form>
  </x-card>
</x-layout>