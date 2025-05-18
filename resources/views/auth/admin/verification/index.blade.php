<x-layout>
  <x-card class="flex flex-col">
    <div class="flex flex-col sm:flex-row items-center sm:justify-between py-4">
      <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Data Pendaftaran</h2>
      <div class="flex space-x-2 items-center">
        <a href="{{ route('home') }}">
          <x-button color="bg-gray-600">Kembali</x-button>
        </a>
      </div>
    </div>

    <div class="hidden md:block">
      <table class="min-w-full divide-y divide-gray-200 text-sm shadow-sm">
        <thead class="bg-gray-100">
          <tr>
            <th>No</th>
            <th>Nama anak</th>
            <th>Nama panggilan</th>
            <th>Jenis kelamin</th>
            <th>TTL</th>
            <th>Agama</th>
            <th>Anak ke</th>
            <th>Status dalam keluarga</th>

            <th>Nama ayah</th>
            <th>Nama ibu</th>
            <th>Pekerjaan ayah</th>
            <th>Pekerjaan ibu</th>
            <th>Nama wali</th>
            <th>Pekerjaan wali</th>

            <th>Alamat</th>
            <th>Kelurahan</th>
            <th>Nomor telepon</th>
            <th>Email</th>
            <th>Nomor whatsapp</th>

            <th>Imunisasi vaksin yang pernah diterima</th>
            <th>Penyakit berat yang pernah diderita</th>
            <th>Jarak dari tempat tinggal ke sekolah</th>
            <th>Golongan darah</th>

            <th>Status pendaftaran</th>
            <th>Tanggal pengajuan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse ($pendaftaran as $data)
            <tr>
              <td>{{ ($pendaftaran->currentPage() - 1) * $pendaftaran->perPage() + $loop->iteration }}</td>
              <td>{{ $data->nama_anak }}</td>
              <td>{{ $data->nama_panggilan }}</td>
              <td>{{ $data->jenis_kelamin }}</td>
              <td>{{ $data->tempat_lahir }}, {{ $data->tanggal_lahir }}</td>
              <td>{{ $data->agama }}</td>
              <td>{{ $data->anak_ke }}</td>
              <td>{{ Str::title(Str::slug($data->status_anak, ' ')) }}</td>

              <td>{{ $data?->nama_ayah ?? '-' }}</td>
              <td>{{ $data?->nama_ibu ?? '-' }}</td>
              <td>{{ $data?->pekerjaan_ayah ?? '-' }}</td>
              <td>{{ $data?->pekerjaan_ibu ?? '-' }}</td>
              <td>{{ $data?->nama_wali ?? '-' }}</td>
              <td>{{ $data?->pekerjaan_wali ?? '-' }}</td>

              <td>{{ $data->alamat }}</td>
              <td>{{ $data->kelurahan }}</td>
              <td>{{ $data->no_telp }}</td>
              <td>{{ $data->email }}</td>
              <td>{{ $data->no_wa }}</td>

              <td>{{ $data->imunisasi_vaksin_yang_pernah_diterima }}</td>
              <td>{{ $data->penyakit_berat_yang_diderita }}</td>
              <td>{{ $data->jarak_dari_rumah }}m</td>
              <td>{{ $data->golongan_darah }}</td>

              <td><x-badge-status-pendaftaran :data="$data->status_pendaftaran"/></td>
              <td>{{ $data->created_at->diffForHumans() }}</td>
              <td>
                @if ($data->status_pendaftaran === 'Diajukan')
                  <a href="{{ route('show.detail.verification', [$data->slug]) }}">
                    <x-button color="bg-blue-600">Detail</x-button>
                  </a>
                @else
                  <x-button color="bg-blue-600" disabled>Terverifikasi</x-button>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="26" class="bg-gray-50 text-center py-4">Tidak ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="block md:hidden space-y-4">
      @forelse ($pendaftaran as $data)
        <div class="rounded-md shadow-sm">
          <button class="accordion-toggle w-full space-x-0.5 px-4 py-3 bg-gray-100 rounded-md text-left font-semibold text-sm flex justify-between items-center">
            <span>{{ $data->nama_anak }}</span>
            <svg class="w-5 h-5 transition-transform rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div class="accordion-content bg-gray-50 px-4 py-3 text-sm space-y-1">
            <p><strong>Nama anak:</strong> {{ $data->nama_anak }}</p>
            <p><strong>Nama panggilan:</strong> {{ $data->nama_panggilan }}</p>
            <p><strong>Jenis kelamin:</strong> {{ $data->jenis_kelamin }}</p>
            <p><strong>TTL:</strong> {{ $data->tempat_lahir }}, {{ $data->tanggal_lahir }}</p>
            <p><strong>Agama:</strong> {{ $data->agama }}</p>
            <p><strong>Anak ke:</strong> {{ $data->anak_ke }}</p>
            <p><strong>Status dalam keluarga:</strong> {{ Str::title(Str::slug($data->status_anak, ' ')) }}</p>

            <p><strong>Nama ayah:</strong> {{ $data?->nama_ayah ?? '-' }}</p>
            <p><strong>Nama ibu:</strong> {{ $data?->nama_ibu ?? '-' }}</p>
            <p><strong>Pekerjaan ayah:</strong> {{ $data?->pekerjaan_ayah ?? '-' }}</p>
            <p><strong>Pekerjaan ibu:</strong> {{ $data?->pekerjaan_ibu ?? '-' }}</p>
            <p><strong>Nama wali:</strong> {{ $data?->nama_wali ?? '-' }}</p>
            <p><strong>Pekerjaan wali:</strong> {{ $data?->pekerjaan_wali ?? '-' }}</p>

            <p><strong>Alamat:</strong> {{ $data->alamat }}</p>
            <p><strong>Kelurahan:</strong> {{ $data->kelurahan }}</p>
            <p><strong>Nomor telepon:</strong> {{ $data->no_telp }}</p>
            <p><strong>Email:</strong> {{ $data->email }}</p>
            <p><strong>Nomor whatsapp:</strong> {{ $data->no_wa }}</p>

            <p><strong>Imunisasi vaksin yang pernah diterima:</strong> {{ $data->imunisasi_vaksin_yang_pernah_diterima }}</p>
            <p><strong>Penyakit berat yang pernah diderita:</strong> {{ $data->penyakit_berat_yang_diderita }}</p>
            <p><strong>Jarak dari tempat tinggal ke sekolah:</strong> {{ $data->jarak_dari_rumah }}m</p>
            <p><strong>Golongan darah:</strong> {{ $data->golongan_darah }}</p>

            <p><strong>Status pendaftaran:</strong> <x-badge-status-pendaftaran :data="$data->status_pendaftaran"/></p>
            <p><strong>Tanggal pengajuan:</strong> {{ $data->created_at->diffForHumans() }}</p>
            <div class="flex gap-2 mt-2">
              @if ($data->status_pendaftaran === 'Diajukan')
                <a href="{{ route('show.detail.verification', [$data->slug]) }}">
                  <x-button color="bg-blue-600">Detail</x-button>
                </a>
              @else
                <x-button color="bg-blue-600" disabled>Terverifikasi</x-button>
              @endif
            </div>
          </div>
        </div>
      @empty
        <p class="text-center py-4">Tidak ada data.</p>
      @endforelse
    </div>

    <x-slot:footer>
      {{ $pendaftaran->links() }}
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