<x-layouts.BaseLayout>
    @vite(['resources/css/mineraisBlade.css', 'resources/js/app.js'])
    <x-slot name="title">
        @if ($tipo == '1')
                Rochas Ígneas
            @elseif ($tipo == '2')
                Rochas Metamórficas
            @else
                Rochas Sedimentares
            @endif
        </x-slot>
    <div class="2xl:px-80 xl:px-32 lg:px-20 md:px-10 px-4">
        <br><br>
        <div class="hero-section">
            <h1><strong>
            @if ($tipo == '1')
                Ígneas
            @elseif ($tipo == '2')
                Metamórficas
            @else
                Sedimentares
            @endif
            </strong></h1>
            <p>
                @if ($tipo == '1')
                    Conheça as principais rochas ígneas em nossa coleção.
                @elseif ($tipo == '2')
                    Conheça as principais rochas metafórficas em nossa coleção.
                @else
                    Conheça as principais rochas sedimentares em nossa coleção.
                @endif
            </p>
        </div>

        <div class="rock-type-section">
            <div class="rock-description">
                <h2><strong>
                @if ($tipo == '1')
                    Ígneas
                @elseif ($tipo == '2')
                    Metamórficas
                @else
                    Sedimentares
                @endif    
                </strong></h2>
                <p>
                    @if ($tipo == '1')
                    As rochas ígneas são o resultado da solidificação do magma, que é uma mistura composta por uma fase líquida e uma fase gasosa. A fase líquida é constituída de rocha fundida e a fase gasosa de vários gases, como o dióxido de carbono (CO2) e o vapor de água.
                @elseif ($tipo == '2')
                    As rochas metamórficas são aquelas que se formam quando outras se modificam outras rochas preexistentes que se encontram no interior da Terra, o que ocorre por meio de um processo chamado metamorfismo.
                @else
                    As rochas sedimentares se formam a partir do acúmulo de sedimentos que são compostos de partículas que podem ser provenientes da destruição de outras rochas que existiam anteriormente.
                @endif
                </p>
            </div>

            <div class="minerals-grid">
                @foreach ($rochastipo as $item)
                    <a href="{{ route('site.rochas.show', [$item->tipo_nome, $item->slug]) }}">
                        <figure>
                            @php
                                $fotoExibir = null;
                                foreach ($item->fotos as $foto) {
                                    if ($foto->capa) {
                                        $fotoExibir = $foto;
                                        break;
                                    }
                                }
                                if (is_null($fotoExibir) && count($item->fotos) > 0) {
                                    $fotoExibir = $item->fotos[0];
                                }
                            @endphp

                            @if ($fotoExibir)
                                <img src="{{ asset('storage/' . $fotoExibir->caminho) }}"
                                    alt="Imagem da rocha {{ $item->nome }}">
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
        </div>
    </div>

</x-layouts.BaseLayout>