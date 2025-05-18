<x-layout>
  <div class="max-w-2xl mx-auto">
    <div class="p-6 border border-gray-200 rounded-xl shadow-md bg-white">
      <div class="flex justify-between items-center mb-2">
        <h2 class="text-2xl font-semibold text-gray-800">
          {{ $notifikasi->judul }}
        </h2>

        <span class="text-xs px-2 py-1 rounded-full 
          {{ $notifikasi->status_baca === 'Dibaca' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
          {{ $notifikasi->status_baca ?? 'Belum Dibaca' }}
        </span>
      </div>

      <div class="text-xs text-gray-500 mb-4">
        Dikirim: {{ $notifikasi->created_at->translatedFormat('l, d F Y H:i') }}
      </div>

      <p class="text-gray-800 leading-relaxed mb-6">
        {{ $notifikasi->isi_pesan }}
      </p>

      <a href="{{ route('show.notification', [$notifikasi->user->slug]) }}">
        <x-button>Kembali ke Notifikasi</x-button>
      </a>
    </div>
  </div>
</x-layout>