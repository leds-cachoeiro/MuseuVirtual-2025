<x-layouts.BaseLayout>
    @vite(['resources/css/mineraisBlade.css', 'resources/js/app.js'])
    <x-slot name="title">Minerais</x-slot>
    <div class="2xl:px-80 xl:px-32 lg:px-20 md:px-10 px-4">
        <br><br>
        <div class="hero-section">
            <h1><strong>Catálogo de Minerais</strong></h1>
            <p>
                Conheça os principais minerais em nossa coleção. Descubra como cada um se forma e suas características únicas.
            </p>
        </div>


        <div class="rock-type-section">
            <div class="rock-description">
                <h2><strong>Minerais</strong></h2>
                <p>
                    Formados a partir de processos geológicos. Os minerais são substâncias sólidas, inorgânicas e naturais, com uma composição química definida e uma estrutura cristalina.
                </p>
            </div>

            <div class="minerals-grid">
                @foreach ($minerais as $item)
                    {{-- @dd($item) --}}
                    <a href="{{ route('site.minerais.show', ['slug_mineral' => $item->slug]) }}">
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
                                <h2>{{ $item->nome }}</h2>
                            </figcaption>
                        </figure>
                    </a>
                @endforeach
            </div>

            <div class="pagination-links">
                {{ $minerais->links() }}
            </div>
        </div>
    </div>
</x-layouts.BaseLayout>
