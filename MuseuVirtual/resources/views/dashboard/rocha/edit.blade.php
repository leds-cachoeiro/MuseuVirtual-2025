<x-app-layout>
    {{-- Cabeçalho da página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Rocha') }}
        </h2>
    </x-slot>

    {{-- Conteúdo principal --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('Rocha.update', $rocha) }}">
                        @csrf
                        @method('PUT')

                        {{-- Nome --}}
                        <div class="mb-4">
                            <x-input-label for="nome" :value="__('Nome')" />
                            <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full"
                                        :value="old('nome', $rocha->nome)" />
                            @error('nome') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Descrição --}}
                        <div class="mb-4">
                            <x-input-label for="descricao" :value="__('Descrição')" />
                            <textarea id="descricao" name="descricao" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="4">{{ old('descricao', $rocha->descricao) }}</textarea>
                            @error('descricao') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Composição --}}
                        <div class="mb-4">
                            <x-input-label for="composicao" :value="__('Composição')" />
                            <x-text-input id="composicao" name="composicao" type="text" class="block mt-1 w-full"
                                        :value="old('composicao', $rocha->composicao)" />
                            @error('composicao') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Tipo de Rocha --}}
                        <div class="mb-4">
                            <x-input-label for="tipo" :value="__('Tipo de Rocha')" />
                            <select id="tipo" name="tipo" required class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                                <option value="" disabled {{ old('tipo', $rocha->tipo) === null ? 'selected' : '' }}>Escolha uma rocha...</option>
                                <option value="1" {{ old('tipo', $rocha->tipo) == '1' ? 'selected' : '' }}>Ígneas</option>
                                <option value="2" {{ old('tipo', $rocha->tipo) == '2' ? 'selected' : '' }}>Metamórficas</option>
                                <option value="3" {{ old('tipo', $rocha->tipo) == '3' ? 'selected' : '' }}>Sedimentares</option>
                            </select>
                            @error('tipo') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Salvar Alterações') }}
                            </x-primary-button>
                        </div>
                    </form>
                    <br>

                    <div class='flex flex-wrap justify-center border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm'>
                        <h2 class="self-center font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            {{ __('Fotos da rocha') }}
                        </h2>
                        
                        <a href="{{ route('fotos-create', ['idRocha' =>$rocha->id]) }}" class="bg-[#9B9FB5] inline-block self-end text-black px-4 bg-blue-600 rounded hover:bg-blue-700">
                            Adicionar fotos
                        </a>

                    </div>
                    <div class='flex flex-wrap border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm'>
                        @if ($rocha->fotos->isEmpty())
                            <br>
                            <p>Não existem imagens cadastradas para esta rocha</p>
                            <br>
                        @else
                            @php
                                $fotos = $rocha->fotos;
                            @endphp
                            @foreach ($fotos as $foto)
                                <div class="flex flex-col h-[212px] w-[160px] items-center justify-between p-2 m-2 border rounded-md dark:border-gray-700 dark:bg-gray-900">
                                    {{-- Imagem centralizada --}}
                                    <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Foto da Rocha" class="h-[144px] w-[128px] object-cover mb-2">

                                    {{-- Botões abaixo da imagem --}}
                                    <div class="flex items-center gap-2">

                                        <a href="{{ route('fotos-edit', $foto->id) }}" class="inline-flex items-center px-2 py-1 text-sm bg-[#9B9FB5] text-black dark:text-white hover:underline rounded">
                                            Editar
                                            <x-icons.pencil />
                                        </a>
                                        <form action="{{ route('fotos-destroy', $foto->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta rocha?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 bg-[#9B9FB5] text-sm text-red-600 dark:text-red-400 hover:underline rounded">
                                                Excluir
                                                <x-icons.trash />
                                            </button>

                                        </form>
                                    </div>
                                </div>
                            @endforeach

                        @endif
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
