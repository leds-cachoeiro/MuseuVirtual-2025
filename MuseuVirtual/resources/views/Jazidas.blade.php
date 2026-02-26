<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jazidas</title>
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gradient-to-b from-[#ACB18E] from-0.5% via-[#73785C] via-5% to-[#363C27] to-20% ">
    <x-menu_site></x-menu_site>
        <h1 class="font-[Arial] text-[50px] ms-80 text-[#F1EEDD] pb-6 pt-16"><strong>Jazidas</strong></h1>
        <div class="px-80">
            <img class= "pb-40 w-full object-cover" src="/assets/img/image 2.png" alt="Foto da jazida tal">
        </div>

        <div class="grid grid-cols-2 gap-8 ">
            @foreach ($jazidas as $item)
                <a href="{{ route('jazida_específica', $item->slug) }}">
                    <figure class="pl-80 w-full hover:w-">
                    @php
                        $fotoExibir = null; // Inicializa como nulo
                        // Tenta encontrar uma foto com capa
                        foreach ($item->fotos as $foto) {
                            if ($foto->capa) {
                                $fotoExibir = $foto;
                                break; // Encontrou a capa, pode parar
                            }
                        }

                        // Se nenhuma capa foi encontrada, usa a primeira foto (se existir)
                        if (is_null($fotoExibir) && count($item->fotos) > 0) {
                            $fotoExibir = $item->fotos[0];
                        }
                    @endphp

                    <img class="object-cover w-[500px] h-[500px] rounded-xl hover:scale-95 duration-300"
                        src="{{ asset('storage/' . $fotoExibir->caminho) }}" alt="Imagem da jazida tal">
                    <figcaption>
                        <h3 class="font-[Arial] text-[30px] text-[#F1EEDD] pt-4"><strong>Localização:
                                {{ $item->localizacao }}</strong></h3>
                    </figcaption>
                    </figure>
            </a>
            @endforeach
        </div>

        <x-rodape_site></x-rodape_site>
</body>

</html>

