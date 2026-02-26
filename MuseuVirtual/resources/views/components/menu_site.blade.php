<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Responsivo - Componente</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          screens: {
            'custom': '1350px',
          }
        }
      }
    }
  </script>
  <style>
    .menu-slide {
      transform: translateX(-100%);
      transition: transform 0.3s ease-in-out;
    }
    .menu-slide.open {
      transform: translateX(0);
    }
    .hamburger-line {
      transition: all 0.3s ease-in-out;
    }
    .hamburger.active .hamburger-line:nth-child(1) {
      transform: rotate(45deg) translate(6px, 6px);
    }
    .hamburger.active .hamburger-line:nth-child(2) {
      opacity: 0;
    }
    .hamburger.active .hamburger-line:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
    }
    .menu-shadow {
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.3);
    }
    
    /* Esconde menu desktop abaixo de 1350px */
    @media (max-width: 1349px) {
      .desktop-menu,
      .desktop-search {
        display: none !important;
      }
      .mobile-button {
        display: block !important;
      }
      .mobile-menu-container,
      .mobile-overlay {
        display: block !important;
      }
    }
    
    /* Mostra menu desktop acima de 1350px */
    @media (min-width: 1350px) {
      .desktop-menu,
      .desktop-search {
        display: flex !important;
      }
      .mobile-button {
        display: none !important;
      }
      .mobile-menu-container,
      .mobile-overlay {
        display: none !important;
      }
    }
  </style>
</head>
<body class="bg-black text-white">
  <header class="w-full bg-black text-white relative z-50">
    <nav class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">

      <img src="/assets/img/logo_sem_estado.png" alt="Museu Virtual ES Logo" class="h-11">

      <ul class="desktop-menu hidden flex space-x-8 font-semibold text-2xl">
        <li><a href="{{ route('home') }}" class="px-4 py-3 transition-colors hover:text-gray-400 hover:underline {{ request()->routeIs('home') ? 'underline decoration-white' : '' }}">Home</a></li>
        <li><a href="{{ route('site.jazidas') }}" class="px-4 py-3 transition-colors hover:text-gray-400 hover:underline {{ request()->routeIs('site.jazidas','site.jazidas.show') ? 'underline decoration-white' : '' }}">Jazidas</a></li>
        <li><a href="{{ route('site.rochas') }}" class="px-4 py-3 transition-colors hover:text-gray-400 hover:underline {{ request()->routeIs('site.rochas','site.rochas.show','site.rochas.tipo') ? 'underline decoration-white' : '' }}">Rochas</a></li>
        <li><a href="{{ route('site.minerais') }}" class="px-4 py-3 transition-colors hover:text-gray-400 hover:underline {{ request()->routeIs('site.minerais','site.minerais.show','site.minerais.tipo') ? 'underline decoration-white' : '' }}">Minerais</a></li>
        <li><a href="{{ route('site.rochasOrnamentais') }}" class="px-4 py-3 transition-colors hover:text-gray-400 hover:underline {{ request()->routeIs('site.rochasOrnamentais','site.rochasOrnamentais.show') ? 'underline decoration-white' : '' }}">Rochas Ornamentais</a></li>
      </ul>

      <!-- Busca desktop -->
      <form action="{{ route('busca') }}" method="GET" class="desktop-search hidden items-center">
        <input type="text" name="q" placeholder="Buscar..." class="w-64 px-4 py-2 text-sm text-gray-800 placeholder-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-300 ease-in-out value="{{ $termo ?? '' }}">
        <button type="submit" class="text-white px-4 py-2 rounded-full opacity-90 bg-[#565851] hover:bg-gray-200 transition">Buscar</button>
      </form>

      <!-- Botão mobile -->
      <button id="btn-mobile" class="mobile-button hidden p-3 focus:outline-none relative z-60">
        <div class="hamburger w-8 h-8 flex flex-col justify-center items-center">
          <span class="hamburger-line block w-7 h-0.5 bg-white mb-1.5"></span>
          <span class="hamburger-line block w-7 h-0.5 bg-white mb-1.5"></span>
          <span class="hamburger-line block w-7 h-0.5 bg-white"></span>
        </div>
      </button>
    </nav>
    <hr class="border-white opacity-30">
  </header>

  <!-- MENU MOBILE LATERAL -->
  <div id="mobile-menu" class="mobile-menu-container hidden fixed top-0 left-0 h-full w-80 bg-black text-white z-40 menu-slide">
    <div class="flex justify-between items-center p-4 border-b border-gray-700">
      <img src="/assets/img/logo12.png" alt="Logo" class="h-10">
    </div>

    <nav class="pt-6">
      <ul class="space-y-2">
        <li><a href="{{ route('home') }}" class="block px-6 py-4 text-2xl transition-all duration-200 hover:bg-gray-800 hover:text-gray-400 hover:underline {{ request()->routeIs('home') ? 'underline decoration-white font-bold' : '' }}">Home</a></li>
        <li><a href="{{ route('site.jazidas') }}" class="block px-6 py-4 text-2xl transition-all duration-200 hover:bg-gray-800 hover:text-gray-400 hover:underline {{ request()->routeIs('site.jazidas','site.jazidas.show') ? 'underline decoration-white font-bold' : '' }}">Jazidas</a></li>
        <li><a href="{{ route('site.rochas') }}" class="block px-6 py-4 text-2xl transition-all duration-200 hover:bg-gray-800 hover:text-gray-400 hover:underline {{ request()->routeIs('site.rochas','site.rochas.show','site.rochas.tipo') ? 'underline decoration-white font-bold' : '' }}">Rochas</a></li>
        <li><a href="{{ route('site.minerais') }}" class="block px-6 py-4 text-2xl transition-all duration-200 hover:bg-gray-800 hover:text-gray-400 hover:underline {{ request()->routeIs('site.minerais','site.minerais.show','site.minerais.tipo') ? 'underline decoration-white font-bold' : '' }}">Minerais</a></li>
        <li><a href="{{ route('site.rochasOrnamentais') }}" class="block px-6 py-4 text-2xl transition-all duration-200 hover:bg-gray-800 hover:text-gray-400 hover:underline {{ request()->routeIs('site.minerais','site.minerais.show','site.minerais.tipo') ? 'underline decoration-white font-bold' : '' }}">Rochas Ornamentais</a></li>
      </ul>
    </nav>

    <!-- Busca mobile -->
    <div class="p-6 border-t border-gray-700 mt-8">
      <form action="{{ route('busca') }}" method="GET" class="flex">
        <input type="text" name="q" placeholder="Buscar..." class="flex-grow px-4 py-3 rounded-l-md text-black placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 value="{{ $termo ?? '' }}">
        <button type="submit" class="bg-white text-black text-lg rounded-r-md hover:bg-gray-200 transition font-semibold">🔍</button>
      </form>
    </div>
  </div>

  <!-- OVERLAY -->
  <div id="menu-overlay" class="mobile-overlay hidden fixed inset-0 bg-black bg-opacity-20 z-30 opacity-0 pointer-events-none transition-opacity duration-300"></div>

  <!-- JAVASCRIPT -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const btnMobile = document.getElementById('btn-mobile');
      const mobileMenu = document.getElementById('mobile-menu');
      const menuOverlay = document.getElementById('menu-overlay');
      const hamburger = btnMobile.querySelector('.hamburger');

      let isMenuOpen = false;

      function toggleMenu() {
        isMenuOpen = !isMenuOpen;

        if (isMenuOpen) {
          mobileMenu.classList.add('open', 'menu-shadow');
          menuOverlay.classList.remove('opacity-0', 'pointer-events-none');
          menuOverlay.classList.add('opacity-100');
          hamburger.classList.add('active');
        } else {
          mobileMenu.classList.remove('open', 'menu-shadow');
          menuOverlay.classList.remove('opacity-100');
          menuOverlay.classList.add('opacity-0', 'pointer-events-none');
          hamburger.classList.remove('active');
        }
      }

      btnMobile.addEventListener('click', toggleMenu);
      menuOverlay.addEventListener('click', toggleMenu);

      const menuLinks = mobileMenu.querySelectorAll('a');
      menuLinks.forEach(link => {
        link.addEventListener('click', function() {
          if (isMenuOpen) toggleMenu();
        });
      });

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isMenuOpen) toggleMenu();
      });

      window.addEventListener('resize', function() {
        if (window.innerWidth >= 1350 && isMenuOpen) toggleMenu();
      });
    });
  </script>

</body>
</html>