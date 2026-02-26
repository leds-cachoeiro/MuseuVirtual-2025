<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Listagem de fotos') }}
            </h2>
            <a href="{{ route('fotos-create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-xl transition duration-200">Adicionar foto
            </a>
        </div>
    </x-slot>

    <x-slot name="slot">
        <div class="container mx-auto px-4 mt-6">
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Fotos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Rocha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Mineral</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Jazida</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Capa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Ações</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($fotos as $foto)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100"><img src="{{ asset('storage/' . $foto->caminho) }}" alt="Foto" class="h-[144px] w-[128px] object-cover"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $foto->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                @if ($foto->rocha)
                                    {{ $foto->rocha->nome}}
                                @else
                                    {{ __('Não Existe') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                @if ($foto->mineral)
                                    {{ $foto->mineral->nome}}
                                @else
                                    {{ __('Não Existe') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                @if ($foto->jazida)
                                    {{ $foto->jazida->localizacao}}
                                @else
                                    {{ __('Não existe') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $foto->capa ? 'Sim' : 'Não' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex items-center gap-3">
                                <a href="{{ route('fotos-edit', $foto->id) }}"
                                   class="text-blue-500 hover:underline">Editar</a>
                                   
                                <form action="{{ route('fotos-destroy', $foto->id) }}" method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja deletar?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-slot>
</x-app-layout>