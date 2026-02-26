<x-layouts.BaseLayout>
    @vite(['resources/css/jazidasBlade.css', 'resources/js/app.js'])
    <x-slot name="title">Jazidas</x-slot>
    <div class="2xl:px-80 xl:px-32 lg:px-20 md:px-10 px-4">
        <br><br>
        <div class="hero-section">
            <h1 ><strong>Catálogo de Jazidas</strong></h1>
            <p>
                Conheça as principais jazidas em nossa coleção. Descubra como cada uma se forma e suas características únicas.
            </p>
        </div>
        <div class="rock-type-section">
            <div class="rock-description">
                <h2><strong>Jazidas</strong></h2>
                <p>
                    As jazidas representam concentrações naturais de minerais que podem ser exploradas economicamente. Explore nossa coleção para conhecer os principais locais e seus minerais característicos.
                </p>
            </div>

            <div class="minerals-grid">
                @foreach ($jazidas as $item)
                    <a href="{{ route('site.jazidas.show', $item->id) }}">
                        <figure>
                            @php
                                $fotoExibir = $item->fotos->firstWhere('capa', true) ?? $item->fotos->first();
                            @endphp

                            @if ($fotoExibir)
                                <img src="{{ asset('storage/' . $fotoExibir->caminho) }}" alt="Imagem de {{ $item->nome }}">
                            @else
                                <img src="{{ asset('assets/img/placeholder.png') }}" alt="Nenhuma imagem disponível">
                            @endif

                            <figcaption>
                                <h2>{{ $item->localizacao }}</h2>
                            </figcaption>
                        </figure>
                    </a>
                @endforeach
            </div>

            <div class="pagination-links">
                {{ $jazidas->links() }}
            </div>
        </div>
    </div>
</x-layouts.BaseLayout>
