<x-layouts.BaseLayout>

    <style>
        .swiper-slide {
            width: 100%;
            height: 800px;
            background-size: contain;
        }
    </style>

    <x-slot name="title">Home</x-slot>
    <h1 class="font-[arial] pt-40 font-bold text-[80px] text-center text-[#F1EEDD]">Museu Virtual</h1>
    <h2 class="text-center text-[20px] font-[arial] text-[#F1EEDD]">Bem-vindo ao Museu Virtual das Rochas, um <br>espaço
        interativo e educativo dedicado à incrível<br> diversidade geológica da nossa região. </h2>
    <br>
    {{-- <p class="text-center"><a href="{{ route('dashboardPublica') }}"
            class="p-1 pl-9 pr-9 rounded-full bg-[#F1EEDD] hover:bg-[#ACB18E] text-[#565851]">Login</a></p> --}}
    <figure class="w-100 mt-20 swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($fotosRecentes as $item)
                <img src="{{ asset('storage/' . $item->caminho) }}" class="swiper-slide bg-cover">
            @endforeach
            {{-- <div class="swiper-pagination"></div> --}}
        </div>
    </figure>


    <div class="flex justify-center mr-60 pt-40">
        <h2
            class="font-[arial] font-bold text-[80px] bg-gradient-to-b from-[#F1EEDD] to-[#363C27] bg-clip-text text-transparent">
            Explore</h2>
    </div>
    <div class="flex justify-center ml-60">
        <h3 class="text-[15px] font-[arial] text-[#F1EEDD] text-left">Explore o universo da geologia em nosso <br>
            site,onde você encontra informações <br> detalhadas sobre jazidas, rochas e minerais.</h3>
    </div>
    <br>
    <div class="flex justify-center mt-10">
        <div class="w-[1200px]">
            <div class="grid xl:grid-cols-2 grid-rows-2 gap-x-8 gap-y-4 grid-cols-1 justify-items-center items-center">
                <figure data-aos="fade-right">
                    <a href="{{ route('site.jazidas') }}"><img class="w-[584px] h-[876px] object-cover rounded-lg cursor-pointer"
                            src="/assets/img/JAZIDAinicial(2).png" alt=""></a>
                    <figcaption>
                        <h2 class="font-[arial] font-bold text-[40px] text-[#F1EEDD]">Jazidas</h2>
                        <h3 class="font-[arial] text-[20px] text-[#F1EEDD]">Confira aqui nosso acervo de jazidas.</h3>
                    </figcaption>
                </figure>

                <figure data-aos="fade-left">
                    <a href="{{ route('site.rochas') }}"><img class="w-[584px] h-[876px] object-cover rounded-lg cursor-pointer" src="/assets/img/rochaINICIAL.jpg"
                            alt=""></a>
                    <figcaption>
                        <h2 class="font-[arial] font-bold text-[40px] text-[#F1EEDD]">Rochas</h2>
                        <h3 class="font-[arial] text-[20px] text-[#F1EEDD]">Confira aqui nosso acervo de rochas.</h3>
                    </figcaption>
                </figure>

                <figure data-aos="fade-right">
                    <a href="{{ route('site.minerais') }}"><img class="rounded-lg cursor-pointer"
                            src="/assets/img/MINERALinicial.jpg" alt=""></a>
                    <figcaption>
                        <h2 class="font-[arial] font-bold text-[40px] text-[#F1EEDD]">Minerais</h2>
                        <h3 class="font-[arial] text-[20px] text-[#F1EEDD]">Confira aqui nosso acervo de minerais.</h3>
                    </figcaption>
                </figure>

                <figure data-aos="fade-left">
                    <a href="#"><img class="w-[584px] h-[876px] object-cover rounded-lg cursor-pointer" src="/assets/img/CATALOGOinicial.jpg"
                            alt=""></a>
                            <figcaption>
                        <h2 class="font-[arial] font-bold text-[40px] text-[#F1EEDD]">Catálogo</h2>
                        <h3 class="font-[arial] text-[20px] text-[#F1EEDD]">O catálogo é uma coleção organizada de
                            amostras
                            com <br> informações sobre suas características e classificações, confira <br>aqui nosso
                            catálogo.</h3>
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>

</x-layouts.BaseLayout>

<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1, // Número de slides visíveis por vez
        // spaceBetween: 10, // Espaço entre os slides em pixels
        loop: true, // Faz o slider ser infinito (volta para o início após o último)

        // Configura o autoplay
        autoplay: {
            delay: 2000, // Tempo de espera em milissegundos para a transição
            disableOnInteraction: false, // O autoplay não para se o usuário interagir
        },
    });
</script>
<x-layouts.BaseLayout>

    <style>
        .swiper-slide {
            width: 100%;
            height: 800px;
            background-size: contain;
        }
    </style>

    <x-slot name="title">Home</x-slot>
    <h1 class="font-[arial] pt-40 font-bold text-[80px] text-center text-[#F1EEDD]">Museu Virtual</h1>
    <h2 class="text-center text-[20px] font-[arial] text-[#F1EEDD]">Bem-vindo ao Museu Virtual das Rochas, um <br>espaço
        interativo e educativo dedicado à incrível<br> diversidade geológica da nossa região. </h2>
    <br>
    {{-- <p class="text-center"><a href="{{ route('dashboardPublica') }}"
            class="p-1 pl-9 pr-9 rounded-full bg-[#F1EEDD] hover:bg-[#ACB18E] text-[#565851]">Login</a></p> --}}
    <figure class="w-100 mt-20 swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($fotosRecentes as $item)
                <img src="{{ asset('storage/' . $item->caminho) }}" class="swiper-slide bg-cover">
            @endforeach
            {{-- <div class="swiper-pagination"></div> --}}
        </div>
    </figure>


    <div class="flex justify-center mr-60 pt-40">
        <h2
            class="font-[arial] font-bold text-[80px] bg-gradient-to-b from-[#F1EEDD] to-[#363C27] bg-clip-text text-transparent">
            Explore</h2>
    </div>
    <div class="flex justify-center ml-60">
        <h3 class="text-[15px] font-[arial] text-[#F1EEDD] text-left">Explore o universo da geologia em nosso <br>
            site,onde você encontra informações <br> detalhadas sobre jazidas, rochas e minerais.</h3>
    </div>
    <br>
    <div class="flex justify-center mt-10">
        <div class="w-[1200px]">
            <div class="grid xl:grid-cols-2 grid-rows-2 gap-x-8 gap-y-4 grid-cols-1 justify-items-center items-center">
                <figure data-aos="fade-right">
                    <a href="{{ route('site.jazidas') }}"><img class="w-[584px] h-[876px] object-cover rounded-lg cursor-pointer"
                            src="/assets/img/JAZIDAinicial(2).png" alt=""></a>
                    <figcaption>
                        <h2 class="font-[arial] font-bold text-[40px] text-[#F1EEDD]">Jazidas</h2>
                        <h3 class="font-[arial] text-[20px] text-[#F1EEDD]">Confira aqui nosso acervo de jazidas.</h3>
                    </figcaption>
                </figure>

                <figure data-aos="fade-left">
                    <a href="{{ route('site.rochas') }}"><img class="w-[584px] h-[876px] object-cover rounded-lg cursor-pointer" src="/assets/img/rochaINICIAL.jpg"
                            alt=""></a>
                    <figcaption>
                        <h2 class="font-[arial] font-bold text-[40px] text-[#F1EEDD]">Rochas</h2>
                        <h3 class="font-[arial] text-[20px] text-[#F1EEDD]">Confira aqui nosso acervo de rochas.</h3>
                    </figcaption>
                </figure>

                <figure data-aos="fade-right">
                    <a href="{{ route('site.minerais') }}"><img class="rounded-lg cursor-pointer"
                            src="/assets/img/MINERALinicial.jpg" alt=""></a>
                    <figcaption>
                        <h2 class="font-[arial] font-bold text-[40px] text-[#F1EEDD]">Minerais</h2>
                        <h3 class="font-[arial] text-[20px] text-[#F1EEDD]">Confira aqui nosso acervo de minerais.</h3>
                    </figcaption>
                </figure>

                <figure data-aos="fade-left">
                    <a href="#"><img class="w-[584px] h-[876px] object-cover rounded-lg cursor-pointer" src="/assets/img/CATALOGOinicial.jpg"
                            alt=""></a>
                            <figcaption>
                        <h2 class="font-[arial] font-bold text-[40px] text-[#F1EEDD]">Catálogo</h2>
                        <h3 class="font-[arial] text-[20px] text-[#F1EEDD]">O catálogo é uma coleção organizada de
                            amostras
                            com <br> informações sobre suas características e classificações, confira <br>aqui nosso
                            catálogo.</h3>
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>

</x-layouts.BaseLayout>

<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1, // Número de slides visíveis por vez
        // spaceBetween: 10, // Espaço entre os slides em pixels
        loop: true, // Faz o slider ser infinito (volta para o início após o último)

        // Configura o autoplay
        autoplay: {
            delay: 2000, // Tempo de espera em milissegundos para a transição
            disableOnInteraction: false, // O autoplay não para se o usuário interagir
        },
    });
</script>
