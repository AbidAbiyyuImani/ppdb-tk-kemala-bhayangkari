<x-layout>
  <x-card class="max-w-lg mx-auto">
    <x-slot:header>
      <h1 class="text-2xl font-semibold text-gray-800">Ubah Notifikasi</h1>
      <p class="text-sm text-gray-500">Perbarui informasi notifikasi di bawah ini.</p>
    </x-slot:header>

    <form action="{{ route('update.data-notification', [$notifikasi->user->slug, $notifikasi->slug]) }}" method="POST">
      @csrf @method('PATCH')

      <x-form-group>
        <x-forms.select label="Untuk" name="penerima" :selected="$notifikasi->user->id_user" :options="$users" />
      </x-form-group>
      <x-form-group>
        <x-forms.input :old="true" label="Judul" name="judul" :value="$notifikasi->judul" />
      </x-form-group>
      <x-form-group>
        <x-forms.textarea :old="true" label="Pesan" name="pesan" rows="2" :value="$notifikasi->isi_pesan"></x-forms.textarea>
      </x-form-group>
      
      <a href="{{ route('show.data-notification') }}">
        <x-button color="bg-gray-600">Kembali</x-button>
      </a>
      <x-button type="submit" color="bg-blue-600">Perbarui</x-button>
    </form>
  </x-card>
</x-layout>