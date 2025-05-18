<x-layout>
  <x-card class="max-w-md mx-auto" headerClass="flex justify-between items-center">
    <x-slot:header>
      <h1 class="text-lg sm:text-xl font-semibold text-gray-800">Profil Pengguna</h1>
      <form action="{{ route('logout') }}" method="post">
        @csrf @method('POST')
        <x-button type="submit" class="px-2" color="bg-red-500">
          <i class="fas fa-sign-out"></i>
        </x-button>
      </form>
    </x-slot:header>

    <form action="{{ route('update.profile') }}" method="post" disabled>
      @csrf @method('PATCH')

      <x-form-group>
        <x-forms.input :old="true" label="Nama" name="nama" value="{{ $user['nama'] }}" disabled/>
      </x-form-group>
      <x-form-group>
        <x-forms.input :old="true" label="Email" name="email" value="{{ $user['email'] }}" disabled/>
      </x-form-group>

      <x-form-group>
        <x-button type="button" id="edit-toggle" color="bg-yellow-500">Ubah</x-button>
        <x-button type="submit" id="save-button" color="bg-blue-600" class="w-full" disabled>Simpan</x-button>
      </x-form-group>
    </form>
  </x-card>
</x-layout>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const editToggle = document.getElementById('edit-toggle');
    const inputs = document.querySelectorAll('input');
    const saveBtn = document.getElementById('save-button');

    let isEditing = false;

    editToggle.addEventListener('click', () => {
      isEditing = !isEditing;

      inputs.forEach(input => {
        input.disabled = !isEditing;
      });

      saveBtn.disabled = !isEditing;
      
      if (isEditing) {
        editToggle.classList.replace('bg-yellow-500', 'bg-red-500');
        editToggle.textContent = 'Batalkan';
      } else {
        editToggle.classList.replace('bg-red-500', 'bg-yellow-500');
        editToggle.textContent = 'Ubah';
      }
    });
  });
</script>