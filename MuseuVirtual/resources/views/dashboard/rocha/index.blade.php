<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Stones List') }}
            </h2>
            <a href="{{ route('Rocha.create') }}" class="bg-[#9B9FB5] inline-block text-black px-4 bg-blue-600 rounded hover:bg-blue-700">
                Cadastrar Rocha
            </a>  
        </div>
    </x-slot>

    <x-slot name="slot">
        @if (session('success'))
            <div id="popup" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-md shadow-md opacity-0 transition-opacity duration-500 ease-in-out">
                {{ session('success') }}
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const popup = document.getElementById('popup');
                    popup.classList.remove('opacity-0');
                    popup.classList.add('opacity-100');

                    setTimeout(() => {
                        popup.classList.remove('opacity-100');
                        popup.classList.add('opacity-0');
                    }, 3000);
                });
            </script>
        @endif

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Foto
                                    </th>
                                    <th class="w-1/2 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nome
                                    </th>
                                    <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($rochas as $rocha)
                                    <tr>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-sm text-gray-900 dark:text-gray-100">
                                                @if ($rocha->fotos->isEmpty())
                                                    <p>Não existe fotos cadastradas</p>
                                                @else
                                                    @php
                                                        $fotoCapa = $rocha->fotos->firstWhere('capa', 1);
                                                    @endphp
                                                    @if ($fotoCapa)
                                                        <img src="{{ asset('storage/' . $fotoCapa->caminho) }}" alt="Foto da Rocha" class="h-[144px] w-[128px] object-cover">
                                                    @else
                                                        <img src="{{ asset('storage/' . $rocha->fotos->first()->caminho) }}" alt="Foto da Rocha" class="h-[144px] w-[128px] object-cover"> 
                                                    @endif
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $rocha->nome }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('Rocha.edit', $rocha->id) }}" class="inline-flex items-center px-2 py-1 text-sm bg-[#9B9FB5] text-black dark:text-white hover:underline rounded">
                                                    Editar
                                                    <x-icons.pencil />
                                                </a>
                                                <form action="{{ route('Rocha.destroy', $rocha->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta rocha?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-[#9B9FB5] text-sm text-red-600 dark:text-red-400 hover:underline rounded">
                                                    Excluir
                                                    <x-icons.trash />
                                                </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $rochas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>
