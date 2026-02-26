<script setup>
import swal from 'sweetalert'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { aparelhoUso } from '@/Composables/aparelhoUso.js';

const props = defineProps({
    jazidas: {
        type: Object,
        default: () => [{ data: []}],
    },
});

onMounted (()=>{
    const a = Array.from(props.jazidas)
    console.log(props.jazidas)
});

const { Mobile, Desktop } = aparelhoUso();

const jazidas = props.jazidas.data;
const page = usePage();
const successMessage = computed(() => page.props?.flash?.success ?? null);

function submitDelete(id) {
    swal({
    title: "Excluir?",
    text: "Tem certeza que deseja excluir esta jazida?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((apagar) => {
    if (apagar) {
      router.delete(route('jazidas.destroy', id));
      location.reload();
    }
  });
}

</script>

<template>
    <Head title="Jazidas" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-900">
                    Lista de Jazidas
                </h2>
                <a :href="route('jazidas.create')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Cadastrar Jazida
                </a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                    
                    <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        {{ successMessage }}
                    </div>

                    <!-- Desktop View -->
                    <div v-if="Desktop">
                        <div v-if="props.jazidas.length">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Foto</th>
                                        <th class="w-1/3 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Localização</th>
                                        <th class="w-1/3 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="jazida in props.jazidas" :key="jazida.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        
                                        <td class="px-6 py-4 text-center">
                                            <p v-if="!jazida.fotos || jazida.fotos.length === 0">Não existe fotos cadastradas</p>
                                            <img v-else :src="`/storage/${jazida.fotos[0].caminho}`" alt="Foto das jazidas" class="h-[144px] w-[128px] object-cover" />
                                        </td>
                                        <td class="px-6 py-4 text-center">{{ jazida.localizacao ?? 'Sem localização' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a :href="route('jazidas.edit', jazida.id)" class="inline-flex items-center px-2 py-1 text-sm text-blue-600 dark:text-blue-400 hover:underline">Editar</a>
                                                <form :action="route('jazidas.destroy', jazida.id)" method="POST" @submit.prevent="submitDelete(jazida.id)">
                                                    <input type="hidden" name="_method" value="DELETE" />
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-sm text-red-600 dark:text-red-400 hover:underline">Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p v-else class="text-center text-gray-600 dark:text-gray-300">Nenhuma jazida cadastrada.</p>
                    </div>

                    <!-- Mobile View -->
                    <div v-else class="flex flex-col items-center gap-6">
                        <div v-if="jazidas.length > 0" v-for="jazida in jazidas" :key="jazida.id"
                             class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-900 shadow max-w-fit min-w-[220px] max-w-[90vw] text-center">
                            
                            <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-1">Jazida #{{ jazida.id }}</h3>

                            <div class="mb-2">
                                <template v-if="!jazida.fotos || jazida.fotos.length === 0">
                                    <p class="text-gray-500 text-sm">Sem foto cadastrada</p>
                                </template>
                                <template v-else>
                                    <img :src="`/storage/${jazida.fotos[0].caminho}`" alt="Foto da jazida"
                                         class="h-auto max-h-[144px] w-auto max-w-[128px] object-cover rounded mx-auto" />
                                </template>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-1"><strong>Localização:</strong> {{ jazida.localizacao ?? 'Sem localização' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2"><strong>Descrição:</strong> {{ jazida.descricao ?? 'Sem descrição' }}</p>

                            <div class="flex justify-center gap-2 mt-2">
                                <a :href="route('jazidas.edit', jazida.id)" class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Editar</a>
                                <button @click="submitDelete(jazida.id)" class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Excluir</button>
                            </div>
                        </div>

                        <p v-else class="text-center text-gray-600 dark:text-gray-300">Nenhuma jazida cadastrada.</p>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>