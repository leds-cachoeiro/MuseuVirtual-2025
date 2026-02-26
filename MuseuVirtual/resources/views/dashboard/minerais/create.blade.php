<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastrar Mineral') }}
        </h2>
    </x-slot>

    <x-slot name="slot">
        <form action="{{ route('minerais.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <!-- Nome -->
                            <div class="mb-4">
                                <label for="nome" class="block mt-1 w-full">Nome</label>
                                <input type="text" id="nome" name="nome"
                                       class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                       required>
                            </div>

                            <!-- Descrição -->
                            <div class="mb-4">
                                <label for="descricao" class="block mt-1 w-full">Descrição</label>
                                <textarea id="descricao" name="descricao" rows="4"
                                          class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                          required></textarea>
                            </div>

                            <!-- Propriedades -->
                            <div class="mb-4">
                                <label for="propriedades" class="block mt-1 w-full">Propriedades</label>
                                <textarea id="propriedades" name="propriedades" rows="4"
                                          class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                          required></textarea>
                            </div>

                            <!-- Fotos do Mineral -->
                            <div class="mb-4">
                                <label for="foto" class="block mt-1 w-full">Foto(s) do Mineral</label>
                                <input type="file" id="foto" name="foto[]" multiple 
                                       class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            
                            <!-- Botão de Envio -->
                            <div class="flex items-center justify-end mt-6">
                                <x-primary-button type="submit" class="btn btn-primary">Criar</x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-slot>
</x-app-layout>