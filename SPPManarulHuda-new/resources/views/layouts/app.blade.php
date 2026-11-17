<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pembayaran SPP Manarul Huda</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-custom-image {
            background-image: url('{{ asset('images/background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .content-overlay {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }
        
        .sidebar-overlay {
            background-color: rgba(255, 255, 255, 0.95);
        }
        
        .alert-dismissible {
            transition: opacity 0.3s ease-in-out;
        }
        
        /* Animasi untuk sidebar */
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutLeft {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.3s ease-out forwards;
        }
        
        .animate-slide-out-left {
            animation: slideOutLeft 0.3s ease-in forwards;
        }
    </style>
</head>
<body class="bg-custom-image">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @auth
        <div class="w-64 shadow-lg sidebar-overlay">
            <div class="p-4 bg-green-600">
                <!-- Logo dan Judul -->
                <div class="flex flex-col items-center mb-2">
                    <!-- Area untuk Logo -->
                    <div class="flex justify-center mb-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Manarul Huda" class="h-16 w-auto">
                    </div>
                    <h1 class="text-white text-xl font-bold text-center">Aplikasi Pembayaran SPP Manarul Huda</h1>
                </div>
            </div>
            <!-- Sidebar Navigation -->
            <nav class="mt-5 px-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 {{ request()->is('dashboard') ? 'bg-green-50 text-green-600' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                
                <a href="{{ route('siswa.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 {{ request()->is('siswa*') ? 'bg-green-50 text-green-600' : '' }}">
                    <i class="fas fa-user-graduate mr-2"></i> Siswa
                </a>
                
                <a href="{{ route('kelas.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 {{ request()->is('kelas*') ? 'bg-green-50 text-green-600' : '' }}">
                    <i class="fas fa-school mr-2"></i> Kelas
                </a>
                
                @can('bendahara-only')
                <a href="{{ route('spp.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 {{ request()->is('spp*') ? 'bg-green-50 text-green-600' : '' }}">
                    <i class="fas fa-money-bill-wave mr-2"></i> SPP
                </a>
                @endcan
                
                @can('bendahara-only')
                <a href="{{ route('pembayaran.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 {{ request()->is('pembayaran*') ? 'bg-green-50 text-green-600' : '' }}">
                    <i class="fas fa-cash-register mr-2"></i> Pembayaran
                </a>
                @endcan
                
                <a href="{{ route('tagihan.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 {{ request()->is('tagihan*') ? 'bg-green-50 text-green-600' : '' }}">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Tagihan
                </a>
                
                @can('bendahara-ketua-yayasan')
                <a href="{{ route('laporan.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 {{ request()->is('laporan*') ? 'bg-green-50 text-green-600' : '' }}">
                    <i class="fas fa-file-alt mr-2"></i> Laporan SPP
                </a>
                @endcan
                
                @can('bendahara-only')
                <a href="{{ route('petugas.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 {{ request()->is('petugas*') ? 'bg-green-50 text-green-600' : '' }}">
                    <i class="fas fa-users-cog mr-2"></i> Petugas
                </a>
                @endcan
            </nav>
        </div>
        @endauth

        <!-- Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            @auth
            <div class="bg-white shadow-sm z-10">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center py-3">
                        <div class="flex items-center space-x-4">
                            @auth
                            <button id="sidebarToggle" class="text-gray-500 hover:text-green-600 transition-all duration-300 transform hover:scale-110 focus:outline-none" onclick="toggleSidebar()">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            @endauth
                            
                            <!-- Logo dan Alamat -->
                            <div class="flex items-center">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo Manarul Huda" class="h-10 w-auto">
                                <div class="ml-3 hidden sm:block">
                                    <p class="text-green-600 font-semibold text-sm">MI Manarul Huda Palembang</p>
                                    <p class="text-gray-500 text-xs">JL. Taqwa Mata Merah KP. Kelapa Sawit No. 59 RT. 47 RW. 07, Sei Selincah, Kecamatan Kalidoni, Kota Palembang, Sumatera Selatan.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            @auth
                            <div class="relative">
                                <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                                    <span class="text-sm font-medium">{{ Auth::user()->nama_petugas }}</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @endauth

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4">
                <div class="content-overlay rounded-lg shadow-md p-4 my-2">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('hidden');
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.w-64');
            const toggleButton = document.getElementById('sidebarToggle');
            
            // Animasi rotasi untuk ikon
            toggleButton.classList.add('transition-transform', 'duration-500');
            
            if (sidebar.classList.contains('hidden')) {
                // Tampilkan sidebar dengan animasi slide
                sidebar.classList.remove('hidden');
                sidebar.classList.add('animate-slide-in-left');
                sidebar.classList.remove('animate-slide-out-left');
                
                // Rotasi ikon 180 derajat
                toggleButton.style.transform = 'rotate(180deg)';
                toggleButton.innerHTML = '<i class="fas fa-times text-xl"></i>';
            } else {
                // Sembunyikan sidebar dengan animasi slide
                sidebar.classList.add('animate-slide-out-left');
                setTimeout(() => {
                    sidebar.classList.add('hidden');
                    sidebar.classList.remove('animate-slide-in-left');
                }, 300);
                
                // Kembalikan ikon ke posisi awal
                toggleButton.style.transform = 'rotate(0deg)';
                toggleButton.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            }
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.relative button') && !event.target.matches('.relative button *')) {
                var dropdown = document.getElementById('userDropdown');
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        }
    </script>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    @stack('scripts')
    
    <script>
        console.log('Layout script loaded');
    </script>
</body>
</html> 

















@include('components.modal-confirmation')














