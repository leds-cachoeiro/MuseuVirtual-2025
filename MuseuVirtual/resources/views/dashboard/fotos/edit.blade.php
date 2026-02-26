<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Edição de Fotos</h2>
    </x-slot>

    <x-slot name="slot">
        <div class="max-w-3xl mx-auto mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div>
                <h2>Foto Antiga</h2> 
            </div>

            <img src="{{ asset('storage/' . $fotos->caminho) }}" alt="Foto da Rocha" class="h-[144px] w-[128px] object-cover">
            
                <form method="POST" action="{{ route('fotos-update', $fotos->id) }}" enctype="multipart/form-data">
                @method('PUT')    
                @csrf

                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nova foto(Caso deseje alterar)</label>
                    <input type="file" name="foto" id="foto" onchange="atualizarCardCapa(this)"
                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:bg-gray-100 file:border-0 file:py-2 file:px-4 file:rounded file:text-sm file:font-semibold file:text-gray-700 file:cursor-pointer hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600" />
                </div>

                <div>
                    <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usar foto como
                        capa?</span>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="capa" value="1" id="capaa"
                                class="text-blue-600" {{ $fotos->capa == 1 ? 'checked' : ''}}>
                            <span class="text-gray-700 dark:text-gray-300">Sim</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="capa" value="0" id="capaa" class="text-blue-600"
                                {{ $fotos->capa == 1 ? '' : 'checked'}}>
                            <span class="text-gray-700 dark:text-gray-300">Não</span>
                        </label>
                    </div>
                </div>

                
                <!-- Área onde os cards de fotos serão exibidos -->
                <div class="mt-4 grid grid-cols-3 gap-4" id="cardsFotos">
                    <!-- Cards serão gerados aqui via JS -->
                </div>

                <script>
                    function atualizarCardCapa(input) {
                        const container = document.getElementById('cardsFotos');
                        const capaInput = document.getElementById('capa_nome');
                        container.innerHTML = '';
                        capaInput.value = ''; // limpa valor anterior

                        const arquivo = input.files[0];
                        if (!arquivo) return;

                        const card = document.createElement('div');
                        card.classList.add('card', 'relative', 'border', 'border-gray-300', 'rounded-lg', 'overflow-hidden');

                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(arquivo);
                        img.classList.add('w-full', 'h-full', 'object-cover');
                        card.appendChild(img);

                        const capaIndicator = document.createElement('div');
                        capaIndicator.classList.add('capa-indicator', 'hidden'); // começa escondido
                        capaIndicator.textContent = 'Capa';
                        card.appendChild(capaIndicator);
                        radio - documenct.getElementById('capaa')


                        // Não marca automaticamente como capa
                        container.appendChild(card);
                    }

                </script>

                <style>
                    .card {
                        position: relative;
                        border: 2px solid transparent;
                        transition: transform 0.3s ease-in-out, border-color 0.3s;
                        cursor: pointer;
                    }

                    .card img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        transition: transform 0.3s ease-in-out;
                    }

                    .card:hover img {
                        transform: scale(1.05);
                    }

                    .card.selected {
                        border-color: #4C51BF; /* Indigo-600 */
                        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3); /* visual extra para destaque */
                    }

                    .card:hover {
                        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                    }

                    .card .capa-indicator {
                        position: absolute;
                        top: 0.5rem;
                        left: 0.5rem;
                        background-color: white;
                        color: #1F2937; /* text-gray-800 */
                        font-size: 0.75rem; /* text-xs */
                        font-weight: 600;
                        padding: 0.25rem 0.5rem;
                        border-radius: 0.25rem;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                    }

                    /* Responsividade */
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


                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID da Rocha:</label>
                    <input type="number" name="idRocha" value="{{ $fotos->idRocha }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID do Mineral:</label>
                    <input type="number" name="idMineral" value="{{ $fotos->idMineral }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID da Jazida:</label>
                    <input type="number" name="idJazida" value="{{ $fotos->idJazida }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition ease-in-out duration-150">
                        Atualizar
                    </button>
                </div>
            </form>
        </div>
    </x-slot>
</x-app-layout>
