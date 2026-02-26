<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastro de fotos') }}
        </h2>
    </x-slot>

    <x-slot name="title">Cadastro de fotos</x-slot>

    <x-slot name="slot">
        <div class="container mx-auto px-4 mt-6">
            <div class="max-w-3xl mx-auto mt-2 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
                <form action="{{ route('fotos-store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fotos</label>
                        <input type="file" name="foto[]" id="foto" multiple onchange="atualizarCardsCapa(this)"
                            class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:bg-gray-100 file:border-0 file:py-2 file:px-4 file:rounded file:text-sm file:font-semibold file:text-gray-700 file:cursor-pointer hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600" />
                    </div>

                    <!-- Área onde os cards de fotos serão exibidos -->
                    <div class="mt-4 grid grid-cols-3 gap-4" id="cardsFotos">
                        <!-- Cards serão gerados aqui via JS -->
                    </div>

                    <!-- Campo oculto para armazenar o nome da foto de capa -->
                    <input type="hidden" name="capa_nome" id="capa_nome">

                    <script>
                        function atualizarCardsCapa(input) {
                            const container = document.getElementById('cardsFotos');
                            const capaInput = document.getElementById('capa_nome');
                            container.innerHTML = ''; // Limpar a área antes de adicionar as novas imagens

                            const arquivos = input.files;
                            if (!arquivos.length) return;

                            for (let i = 0; i < arquivos.length; i++) {
                                // Cria o card para cada imagem
                                const card = document.createElement('div');
                                card.classList.add('card', 'cursor-pointer', 'relative', 'border', 'border-gray-300', 'rounded-lg', 'overflow-hidden', 'aspect-w-3', 'aspect-h-4');

                                // Cria a imagem dentro do card
                                const img = document.createElement('img');
                                img.src = URL.createObjectURL(arquivos[i]); // Cria URL temporária para a imagem
                                img.classList.add('w-full', 'h-full', 'object-cover');
                                card.appendChild(img);

                                // Cria o texto no canto superior esquerdo para indicar a "foto de capa"
                                const capaIndicator = document.createElement('div');
                                capaIndicator.classList.add('absolute', 'top-2', 'left-2', 'bg-white', 'px-2', 'py-1', 'text-xs', 'font-semibold', 'text-gray-800', 'hidden');
                                capaIndicator.textContent = 'Capa';
                                card.appendChild(capaIndicator);

                                // Adiciona evento de clique no card
                                card.addEventListener('click', () => {
                                    // Marca a imagem como capa
                                    capaInput.value = arquivos[i].name; // Salva o nome da foto como capa
                                    const allCards = document.querySelectorAll('.card');
                                    allCards.forEach(card => {
                                        // Remove a indicação de capa de todos os cards
                                        card.querySelector('.absolute').classList.add('hidden');
                                    });
                                    capaIndicator.classList.remove('hidden'); // Mostra o indicador no card selecionado
                                });

                                // Adiciona o card à área de cards
                                container.appendChild(card);
                                }
                            }
                            </script>

                            <style>
                                .card {
                                    position: relative;
                                }

                                .card img {
                                    transition: transform 0.3s ease-in-out;
                                }

                                .card:hover img {
                                    transform: scale(1.05);
                                }

                                .card.selected {
                                    border-color: #4C51BF; /* Cor da borda para o card selecionado */
                                }

                                .card:hover {
                                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                                }

                                /* Responsividade para diferentes tamanhos de tela */
                                @media (max-width: 768px) {
                                    .grid-cols-3 {
                                        grid-template-columns: repeat(2, 1fr);
                                    }
                                }

                                @media (max-width: 480px) {
                                    .grid-cols-3 {
                                        grid-template-columns: 1fr;
                                    }
                                }
                            </style>



                    <!-- idRocha -->
                    <div>
                        <label for="idRocha"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rocha</label>
                        <select name="idRocha" id="idRocha"
                            class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-3">
                            <option value="">Escolha uma rocha...</option>
                            @foreach ($rochas as $rocha)
                                <option value="{{ $rocha->id }}" {{ request('idRocha') == $rocha->id ? 'selected' : '' }}>
                                    {{ $rocha->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <!-- idMineral -->
                    <div>
                        <label for="idMineral" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID do
                            Mineral</label>
                        <select name="idMineral" id="idMineral"
                            class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-3">
                            <option value="">Escolha um Mineral...</option>
                            @foreach ($minerais as $mineral)
                                <option value="{{ $mineral->id }}" {{ request('idMineral') == $mineral->id ? 'selected' : '' }}>
                                    {{ $mineral->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- idJazida -->
                    <div>
                        <label for="idJazida" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID da
                            Jazida</label>
                        <select name="idJazida" id="idJazida"
                            class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-3">
                            <option value="">Escolha uma Jazida...</option>
                            @foreach ($jazidas as $jazida)
                                <option value="{{ $jazida->id }}">{{ $jazida->localizacao }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                            Salvar Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>
</x-app-layout>
