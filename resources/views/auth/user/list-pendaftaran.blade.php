<x-layout>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
    @forelse ($pendaftaran as $data)
      @php
        $status = $data->status_pendaftaran;
        $color = match($status) {
          'Diterima' => 'bg-green-100 text-green-800 border-green-300',
          'Ditolak' => 'bg-red-100 text-red-800 border-red-300',
          'Diajukan' => 'bg-gray-100 text-gray-800 border-gray-300',
          default => 'bg-gray-100 text-gray-800 border-gray-300',
        };

        $pj = $data->nama_wali ? $data->nama_wali : "$data->nama_ayah, $data->nama_ibu"
      @endphp

      <a href="{{ route('show.detail-pendaftaran', [$data->user->slug, $data->slug]) }}">
        <x-card class="border {{ $color }} p-4 rounded-xl shadow hover:shadow-md transition">
          <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold">{{ $data->nama_anak }}</h2>
            <span class="text-xs px-2 py-1 border rounded-full {{ $color }}">
              {{ $data->status_pendaftaran }}
            </span>
          </div>
          <p class="text-sm text-gray-800">
            <span class="font-medium">Tempat & Tanggal Lahir:</span> {{ $data->tempat_lahir }}, {{ $data->tanggal_lahir }}
          </p>
          <p class="text-sm text-gray-800">
            <span class="font-medium">Nama {{ $data->nama_wali ? "Wali" : "Orang Tua" }}:</span> {{ $pj }}
          </p>
        </x-card>
      </a>
    @empty
      <div class="col-span-full text-center text-gray-800 py-8">
        <p class="text-lg font-semibold">Belum ada data pendaftaran.</p>
        <a href="{{ route('show.pendaftaran') }}" class="text-blue-500 hover:underline">
          <p class="text-sm">Silakan lakukan pendaftaran terlebih dahulu.</p>
        </a>
      </div>
    @endforelse
  </div>
</x-layout>