<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tournoi de Football Togolais')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans leading-normal tracking-normal text-gray-800">
    <div class="min-h-screen flex flex-col">
        <header class="bg-gradient-to-r from-green-700 to-green-900 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <i class="ri-football-line text-4xl text-white"></i>
                    <h1 class="text-3xl font-extrabold tracking-tight">Tournoi de Football</h1>
                </div>
                
                <div class="md:hidden">
                    <button id="mobile-menu-toggle" class="text-white focus:outline-none">
                        <i class="ri-menu-line text-2xl"></i>
                    </button>
                </div>

                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('menu.view') }}" class="px-4 py-2 rounded-lg transition duration-300 hover:bg-green-600 hover:bg-opacity-50 flex items-center">
                        <i class="ri-home-line mr-2"></i>Accueil
                    </a>
                    <a href="{{ route('kpessekou.index') }}" class="px-4 py-2 rounded-lg transition duration-300 hover:bg-green-600 hover:bg-opacity-50 flex items-center">
                        <i class="ri-football-line mr-2"></i>Kpessekou
                    </a>
                    <a href="{{ route('zobibi.index') }}" class="px-4 py-2 rounded-lg transition duration-300 hover:bg-green-600 hover:bg-opacity-50 flex items-center">
                        <i class="ri-team-line mr-2"></i>Zobibi
                    </a>
                </nav>
            </div>

            <div id="mobile-menu" class="md:hidden hidden bg-green-800 absolute inset-x-0 z-20">
                <nav class="px-4 pt-2 pb-4 space-y-2">
                    <a href="{{ route('menu.view') }}" class="block px-4 py-2 rounded hover:bg-green-700 flex items-center">
                        <i class="ri-home-line mr-2"></i>Accueil
                    </a>
                    <a href="{{ route('kpessekou.index') }}" class="block px-4 py-2 rounded hover:bg-green-700 flex items-center">
                        <i class="ri-football-line mr-2"></i>Kpessekou
                    </a>
                    <a href="{{ route('zobibi.index') }}" class="block px-4 py-2 rounded hover:bg-green-700 flex items-center">
                        <i class="ri-team-line mr-2"></i>Zobibi
                    </a>
                </nav>
            </div>
        </header>

        <main class="container mx-auto px-4 py-8 flex-grow">
            @yield('content')
        </main>

        <footer class="bg-green-900 text-white py-6">
            <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-sm">&copy; {{ date('Y') }} Tournoi de Football Togolais</p>
                <div class="space-x-4 flex items-center">
                    <a href="#" class="hover:text-green-300 transition flex items-center">
                        <i class="ri-file-text-line mr-2"></i>Mentions Légales
                    </a>
                    <a href="#" class="hover:text-green-300 transition flex items-center">
                        <i class="ri-mail-line mr-2"></i>Contact
                    </a>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
    <script> 
        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>