<x-layouts.BaseLayout>
    @vite(['resources/css/mineraisBlade.css', 'resources/js/app.js'])
    <x-slot name="title">Rochas Ornamentais</x-slot>
    <div class="2xl:px-80 xl:px-32 lg:px-20 md:px-10 px-4">
        <br><br>
        <div class="hero-section">
            <h1><strong>Catálogo de Rochas Ornamentais</strong></h1>
            <p>
                Conheça nosso catálo de exclusivamente rochas ornamentais.
            </p>
        </div>


        <div class="rock-type-section">
            <div class="rock-description">
                <h2><strong>Rochas ornamentais</strong></h2>
                <p>
                    As rochas ornamentais são materiais estratégicos extraídos em blocos ou placas, aplicados em revestimentos, decoração, móveis e projetos arquitetônicos.
                </p>
            </div>

            <div class="minerals-grid">
                @foreach($rochas as $rocha)
                    <a href="{{ route('site.rochas.show', [$rocha->tipo, $rocha->slug]) }}">
                        <figure>
                            @if($rocha->fotos->count())
                                <img src="{{ asset('storage/' . $rocha->fotos->first()->caminho) }}"
                                        alt="Imagem de {{ $rocha->slug }}"
                                        class="w-full h-70 object-cover">
                            @else
                                <img src="{{ asset('assets/img/placeholder.png') }}" alt="Nenhuma imagem disponível">
                            @endif

                            <figcaption>
                                <h2>{{ $rocha->nome }}</h2>
                            </figcaption>
                        </figure>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.BaseLayout>
