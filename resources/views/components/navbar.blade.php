<nav class="relative flex justify-center items-center">
    <ul class="opacity-100 sm:opacity-0 static sm:absolute z-10 sm:-z-10 w-full h-16 px-2 bg-white flex justify-between">
        <li>
            <a href="{{ route('home') }}" class="flex flex-row items-center space-x-2">
                <img src="{{ asset('storage/logo.png') }}" alt="Yayasan Kemala Bhayangkawri" class="w-10 h-10">
                <span class="font-semibold">TK KEMALA BHAYANGKARI 24</span>
            </a>
        </li>
        <li class="flex items-center">
            <button id="toggle-nav" class="cursor-pointer">
                <i id="icon" class="fas fa-bars"></i>
            </button>
        </li>
    </ul>
    <ul id="nav-toggle" class="opacity-0 sm:opacity-100 -z-50 sm:z-50 fixed sm:static top-16 sm:top-auto left-0 sm:left-auto right-0 sm:right-auto w-full sm:w-auto bg-white flex flex-col sm:flex-row sm:rounded-md shadow-md transition-all ease-in-out duration-300">
        <li>
            <a href="{{ route('home') }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
        </li>
        <li>
            @auth    
                <a href="{{ route('show.profile') }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                    <i class="fas fa-user"></i>
                    <span>Profil</span>
                </a>
                <li class="order-1">
                    <a href="{{ route('show.notification', [auth()->user()->slug]) }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                        <i class="fas fa-bell relative">
                            @if(auth()->user()->notifikasi->where('status_baca', 'Belum Dibaca')->count() > 0)
                                <span class="absolute top-0 right-0 block w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            @endif
                        </i>
                        <span>Notifikasi</span>
                    </a>
                </li>
            @else
                <a href="{{ route('show.login') }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                    <i class="fas fa-sign-in"></i>
                    <span>Masuk/Daftar</span>
                </a>
            @endauth
        </li>
        @if (!auth()->check() || auth()->user()->role == 'Orang Tua')
            <li>
                <a href="{{ route('show.list-pendaftaran') }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                    <i class="fas fa-database"></i>
                    <span>Data Peserta Didik</span>
                </a>
            </li>
            <li>
                <a href="{{ route('show.pendaftaran') }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                    <i class="fas fa-file-alt"></i>
                    <span>Formulir Pendaftaran</span>
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('show.master-data') }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                    <i class="fas fa-database"></i>
                    <span>Master Data</span>
                </a>
            </li>
            <li>
                <a href="{{ route('show.verification') }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                    <i class="fas fa-file-circle-check"></i>
                    <span>Verifikasi</span>
                </a>
            </li>
        @endif
        <li class="order-last">
            <a href="{{ route('show.about') }}" class="text-gray-800 hover:text-green-600 sm:px-4 py-2 sm:py-4 flex justify-center items-center space-x-2">
                <i class="fas fa-building"></i>
                <span>Tentang Kami</span>
            </a>
        </li>
    </ul>
</nav>