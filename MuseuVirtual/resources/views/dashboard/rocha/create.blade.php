<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Stones List') }}
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <form method="POST" action="{{ route('Rocha.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div>
                                <x-input-label for="nome" :value="__('Nome')" />
                                <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" required autofocus autocomplete="off" />
                            </div>

                            <div>
                                <x-input-label for="descricao" :value="__('Descrição')" />
                                <x-text-input id="descricao" class="block mt-1 w-full" type="text" name="descricao" required autocomplete="off" />
                            </div>

                            <div>
                                <x-input-label for="composicao" :value="__('Composição')" />
                                <x-text-input id="composicao" class="block mt-1 w-full" type="text" name="composicao" required autocomplete="off" />
                            </div>

                            <div>
                                <x-input-label for="tipo" :value="__('Tipo de Rocha')" />
                                <select id="tipo" name="tipo" required class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                                    <option value="" disabled selected>Escolha uma rocha...</option>
                                    <option value="1">Ígneas</option>
                                    <option value="2">Metamórficas</option>
                                    <option value="3">Sedimentares</option>
                                </select>
                            </div>

                            <div>
                                <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fotos da rocha</label>
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

                            <x-primary-button class="ms-3">
                                {{ __('Criar Rocha') }}
                            </x-primary-button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>
