@php
  $menus = [
    ['route' => 'show.data-admin', 'icon' => 'fas fa-user-group', 'label' => 'Data Admin'],
    ['route' => 'show.data-user', 'icon' => 'fas fa-users', 'label' => 'Data User'],
    ['route' => 'show.data-registration', 'icon' => 'fas fa-file-lines', 'label' => 'Data Pendaftaran'],
    ['route' => 'show.data-document', 'icon' => 'fas fa-file', 'label' => 'Data Dokumen'],
    ['route' => 'show.data-verification', 'icon' => 'fas fa-file-circle-check', 'label' => 'Data Verifikasi'],
    ['route' => 'show.data-notification', 'icon' => 'fas fa-bell', 'label' => 'Data Notifikasi'],
  ];
@endphp

<x-layout>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @foreach ($menus as $menu)
      <a href="{{ route($menu['route']) }}">
        <x-card class="flex flex-col items-center justify-center p-6 space-y-3 text-center transition duration-300 transform bg-white shadow-md hover:shadow-lg hover:-translate-y-1 hover:scale-[1.02] text-gray-800 hover:text-green-600">
          <i class="{{ $menu['icon'] }} text-3xl"></i>
          <p class="text-lg font-semibold">{{ $menu['label'] }}</p>
        </x-card>
      </a>
    @endforeach
  </div>
</x-layout>