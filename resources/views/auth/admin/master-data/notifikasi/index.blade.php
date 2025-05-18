<x-layout>
  <x-card class="flex flex-col">
    <div class="flex flex-col sm:flex-row items-center sm:justify-between py-4">
      <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Data Notifikasi</h2>
      <div class="flex space-x-2 items-center">
        <a href="{{ route('show.master-data') }}">
          <x-button color="bg-gray-600">Kembali</x-button>
        </a>
        <a href="{{ route('show.create.data-notification') }}">
          <x-button color="bg-blue-600">Tambah</x-button>
        </a>
      </div>
    </div>

    <x-forms.filter :action="route('show.data-notification')" />

    <div class="hidden md:block">
      <table class="min-w-full divide-y divide-gray-200 text-sm shadow-sm">
        <thead class="bg-gray-100">
          <tr>
            <th>No</th>
            <th>Nama Penerima</th>
            <th>Judul</th>
            <th>Isi Pesan</th>
            <th>Status</th>
            <th>Tanggal Terkirim</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse ($notifikasi as $data)
            <tr>
              <td>{{ ($notifikasi->currentPage() - 1) * $notifikasi->perPage() + $loop->iteration }}</td>
              <td>{{ $data->user->nama }}</td>
              <td>{{ $data->judul }}</td>
              <td>{{ $data->isi_pesan }}</td>
              <td><x-badge-status-pendaftaran :data="$data->status_baca"/></td>
              <td>{{ $data->created_at->diffForHumans() }}</td>
              <td class="space-x-2">
                @if ($data->deleted_at)    
                  <form action="{{ route('restore.data-notification', [$data->user->slug, $data->slug]) }}" method="POST" class="inline">
                    @csrf @method('PATCH')
                    <x-button type="submit" color="bg-orange-500" onclick="return confirm('Pulihkan data notifikasi ini?')">Pulihkan</x-button>
                  </form>
                @else
                  <a href="{{ route('show.update.data-notification', [$data->user->slug, $data->slug]) }}">
                    <x-button color="bg-yellow-500">Ubah</x-button>
                  </a>
                  <form action="{{ route('destroy.data-notification', [$data->user->slug, $data->slug]) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <x-button type="submit" color="bg-red-500" onclick="return confirm('Apakah anda yakin ingin menghapus data notifikasi ini?')">Hapus</x-button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="bg-gray-50 text-center py-4">Tidak ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="block md:hidden space-y-4">
      @forelse ($notifikasi as $data)
        <div class="rounded-md shadow-sm">
          <button class="accordion-toggle w-full space-x-0.5 px-4 py-3 bg-gray-100 rounded-md text-left font-semibold text-sm flex justify-between items-center">
            <span>{{ $data->user->nama }} | {{ $data->judul }}</span>
            <svg class="w-5 h-5 transition-transform rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div class="accordion-content bg-gray-50 px-4 py-3 text-sm space-y-1">
            <p><strong>Nama Penerima:</strong> {{ $data->user->nama }}</p>
            <p><strong>Judul:</strong> {{ $data->judul }}</p>
            <p><strong>Isi Pesan:</strong> {{ $data->isi_pesan }}</p>
            <p><strong>Status:</strong> <x-badge-status-pendaftaran :data="$data->status_baca"/></p>
            <p><strong>Tanggal Terkirim:</strong> {{ $data->created_at->diffForHumans() }}</p>
            <div class="flex gap-2 mt-2">
              @if ($data->deleted_at)    
                <form action="{{ route('restore.data-notification', [$data->user->slug, $data->slug]) }}" method="POST" class="inline">
                  @csrf @method('PATCH')
                  <x-button type="submit" color="bg-orange-500" onclick="return confirm('Pulihkan data notifikasi ini?')">Pulihkan</x-button>
                </form>
              @else
                <a href="{{ route('show.update.data-notification', [$data->user->slug, $data->slug]) }}">
                  <x-button color="bg-yellow-500">Ubah</x-button>
                </a>
                <form action="{{ route('destroy.data-notification', [$data->user->slug, $data->slug]) }}" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <x-button type="submit" color="bg-red-500" onclick="return confirm('Apakah anda yakin ingin menghapus data notifikasi ini?')">Hapus</x-button>
                </form>
              @endif
            </div>
          </div>
        </div>
      @empty
        <p class="text-center py-4">Tidak ada data.</p>
      @endforelse
    </div>

    <x-slot:footer>
      {{ $notifikasi->links() }}
    </x-slot:footer>
  </x-card>
</x-layout>
<script>
  $(document).ready(function () {
    $('.accordion-toggle').on('click', function () {
      const $button = $(this);
      const $content = $button.next('.accordion-content');
      const $icon = $button.find('svg');

      $icon.toggleClass('rotate-180');
      $button.toggleClass('rounded-md rounded-t-md');
      $content.stop(true, true).slideToggle(200);
    });
  });
</script>