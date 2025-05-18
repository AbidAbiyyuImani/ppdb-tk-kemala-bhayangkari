<x-layout>
  <div class="max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Daftar Notifikasi</h2>

    @forelse ($notifikasi as $notif)
      @php
        $statusColor = $notif->status_baca === 'Dibaca' 
          ? 'bg-green-50 border-green-200 text-green-800' 
          : 'bg-yellow-50 border-yellow-200 text-yellow-800';
      @endphp

      <a href="{{ route('read.notification', [$notif->user->slug, $notif->slug]) }}">
        <div class="mb-4 p-4 border rounded-md shadow-sm transition hover:shadow-md {{ $statusColor }}">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-md font-semibold text-gray-800">
                {{ $notif->judul ?? 'Notifikasi' }}
              </h3>
              <p class="text-sm mt-1">
                {{ Str::limit($notif->isi_pesan, 50) }}
              </p>
            </div>
            <span class="text-xs text-gray-500 whitespace-nowrap">
              {{ $notif->created_at->diffForHumans() }}
            </span>
          </div>
        </div>
      </a>
    @empty
      <div class="text-center text-gray-500">
        Tidak ada notifikasi ditemukan.
      </div>
    @endforelse
    <div>
      {{ $notifikasi->links() }}
    </div>
  </div>
</x-layout>