<x-layout>
    <h1 class="text-center font-bold text-3xl sm:text-4xl lg:text-5xl mt-4 mb-8 px-4">PPDB TK KEMALA BHAYANGKARI</h1>

    <div class="w-full">
        <img src="{{ asset('storage/fasilitas/halaman_sekolah.jpg') }}" 
             alt="Halaman Sekolah" 
             class="w-full h-[300px] sm:h-[400px] lg:h-[500px] object-cover rounded-lg shadow-lg mb-12">
    </div>

    <x-card class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-semibold mb-4 text-center sm:text-left">Ruang Kelompok</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach ([['RK_a_2.jpg', 'Ruang Kelompok A'], ['RK_b1_1.jpg', 'Ruang Kelompok B1'], ['RK_b2_1.jpg', 'Ruang Kelompok B2']] as [$file, $label])
                <div class="text-center">
                    <div class="aspect-video overflow-hidden rounded-lg shadow-md hover:scale-105 hover:shadow-xl transition duration-300">
                        <img src="{{ asset("storage/fasilitas/$file") }}" alt="{{ $label }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-sm sm:text-base mt-2 font-medium text-gray-700">{{ $label }}</h3>
                </div>
            @endforeach
        </div>
        
        <h2 class="text-2xl sm:text-3xl font-semibold mb-4 text-center sm:text-left">Sarana dan Prasarana</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach ([['SP_alat_musik_angklung.jpg', 'Alat Musik Angklung'], ['SP_ape_luar_1.jpg', 'Taman Bermain Luar'], ['SP_lapangan_sepak_bola.jpg', 'Lapangan Sepak Bola'], ['SP_taman_lalulintas.jpg', 'Taman Lalulintas'], ['SP_gazebo.jpg', 'Gazebo']] as [$file, $label])
                <div class="text-center">
                    <div class="aspect-video overflow-hidden rounded-lg shadow-md hover:scale-105 hover:shadow-xl transition duration-300">
                        <img src="{{ asset("storage/fasilitas/$file") }}" alt="{{ $label }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-sm sm:text-base mt-2 font-medium text-gray-700">{{ $label }}</h3>
                </div>
            @endforeach
        </div>
        
        <h2 class="text-2xl sm:text-3xl font-semibold mb-4 text-center sm:text-left">Ruangan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([['administrasi_2.jpg', 'Ruang Administrasi'], ['ruang_tamu_1.jpg', 'Ruang Tamu'], ['ruang_kantor_1.jpg', 'Ruang Guru'], ['ruang_bermain.jpg', 'Ruang Bermain'], ['mushola_1.jpg', 'Mushola'], ['uks.jpg', 'Ruang UKS'], ['perpustakaan.jpg', 'Perpustakaan'], ['penghijauan_1.jpg', 'Penghijauan'], ['dapur.jpg', 'Dapur'], ['gudang.jpg', 'Gudang'], ['wc_anak.jpg', 'WC Anak'], ['wc_guru.jpg', 'WC Guru']] as [$file, $label])
                <div class="text-center">
                    <div class="aspect-video overflow-hidden rounded-lg shadow-md hover:scale-105 hover:shadow-xl transition duration-300">
                        <img src="{{ asset("storage/fasilitas/$file") }}" alt="{{ $label }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-sm sm:text-base mt-2 font-medium text-gray-700">{{ $label }}</h3>
                </div>
            @endforeach
        </div>
    </x-card>
</x-layout>