<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lista de Minerais') }}
            </h2>
            <a href="{{ route('minerais.create')}}" class = "bg-gray-100 inline-block text-black px-4 bg-blue-600 rounded hover:bg-blue-700">Cadastrar Mineral</a>
        </div>
    </x-slot>
    <x-slot name="slot">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
            <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                        <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                        <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($minerais as $mineral)
                        <tr>
                            <td class="px-6 py-4 text-center">
                                @if ($mineral->fotos->isEmpty())
                                    <p>Não existe fotos cadastradas</p>
                                @else
                                    <img src="{{ asset('storage/' . $mineral->fotos->first()->caminho) }}" alt="Foto dos Minerais" class="h-[144px] w-[128px] object-cover"> 
                                @endif</td>
                            <td class="px-6 py-4 text-center">{{ $mineral->nome }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('minerais.edit', $mineral->id)}}" class="inline-flex items-center px-2 py-1 text-sm text-blue-600 dark:text-blue-400 hover:underline">Editar</a>
                                    <form action="{{ route('minerais.destroy', $mineral->id) }}" method="POST">
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
        </div>
    </x-slot>
</x-app-layout>