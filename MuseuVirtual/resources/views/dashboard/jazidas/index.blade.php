<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lista de Jazidas') }}
            </h2>
            <a href="{{ route('jazidas.create')}}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Cadastrar Jazida
            </a>
        </div>
    </x-slot>

    <x-slot name="slot">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($jazidas->count())
                            <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                                        <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fotos</th>
                                        <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Localização</th>
                                        <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descrição</th>
                                        <th class="w-2/6 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($jazidas as $jazida)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4">{{ $jazida->id }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if ($jazida->fotos->isEmpty())
                                                    <p>Não existe fotos cadastradas</p>
                                                @else
                                                    <img src="{{ asset('storage/' . $jazida->fotos->first()->caminho) }}" alt="Foto das jazidas" class="h-[144px] w-[128px] object-cover">
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">{{ $jazida->localizacao ?? 'Sem localização' }}</td>
                                            <td class="px-6 py-4 text-center">{{ $jazida->descricao ?? 'Sem descrição' }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('jazidas.edit', $jazida->id)}}" class="inline-flex items-center px-2 py-1 text-sm text-blue-600 dark:text-blue-400 hover:underline">Editar</a>
                                                    <form action="{{ route('jazidas.destroy', $jazida->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta jazida?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-2 py-1 text-sm text-red-600 dark:text-red-400 hover:underline">Excluir</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-gray-600 dark:text-gray-300">Nenhuma jazida cadastrada.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>