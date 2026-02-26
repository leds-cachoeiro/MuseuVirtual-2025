<script setup>
import swal from 'sweetalert'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { aparelhoUso } from '@/Composables/aparelhoUso.js';

const props = defineProps({
  minerais: {
    type: Object,
    default: () => ({ data: []}),
  },
});

const { Mobile, Desktop } = aparelhoUso();

const minerais = props.minerais.data;
const page = usePage();
const successMessage = computed(() => page.props?.flash?.success ?? null);

function submitDelete(id) {
    swal({
    title: "Excluir?",
    text: "Tem certeza que deseja excluir este mineral?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((apagar) => {
    if (apagar) {
      router.delete(route('minerais.destroy', id));
      location.reload();
    }
  });
}
</script>

<template>
  <Head title="Lista de Minerais" />
  <AuthenticatedLayout>
    
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
          Lista de Minerais
        </h2>
        <a :href="route('minerais.create')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Cadastrar Mineral
        </a>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">

            <!-- Mensagem de sucesso -->
            <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
              {{ successMessage }}
            </div>

            <!-- Desktop -->
            <div v-if="Desktop">
              <div v-if="minerais.length > 0">
                <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                      <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                      <th class="w-1/3 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                      <th class="w-1/3 px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="mineral in minerais" :key="mineral.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                      <td class="px-6 py-4 text-center">
                        <p v-if="!mineral.fotos || mineral.fotos.length === 0">Não existe fotos cadastradas</p>
                        <img v-else :src="`/storage/${(mineral.fotos.find(f => f.capa) || mineral.fotos[0]).caminho}`"
                             alt="Foto dos Minerais" class="h-[144px] w-[128px] object-cover" />
                      </td>
                      <td class="px-6 py-4 text-center">{{ mineral.nome }}</td>
                      <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                          <a :href="route('minerais.edit', mineral.id)"
                             class="inline-flex items-center px-2 py-1 text-sm text-blue-600 dark:text-blue-400 hover:underline">Editar</a>
                          <form :action="route('minerais.destroy', mineral.id)" method="POST" @submit.prevent="submitDelete(mineral.id)">
                            <input type="hidden" name="_method" value="DELETE" />
                            <button type="submit"
                                    class="inline-flex items-center px-2 py-1 text-sm text-red-600 dark:text-red-400 hover:underline">Excluir</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- Paginação Desktop -->
                <div v-if="Desktop && props.minerais.links.length > 0" class="mt-6 flex justify-center gap-2">
                  <button
                    v-for="link in props.minerais.links"
                    :key="link.label"
                    :disabled="!link.url"
                    @click="router.get(link.url)"
                    v-html="link.label"
                    class="mx-1 px-3 py-1 rounded text-sm"
                    :class="{
                      'bg-blue-600 text-white': link.active,
                  'text-gray-500 dark:text-gray-30': !link.active,
                  'text-gray-400': !link.url
                    }"
                  />
                </div>
              </div>
              <p v-else class="text-center text-gray-600 dark:text-gray-300">Nenhum mineral cadastrado.</p>
            </div>

            <!-- Mobile -->
            <div v-else class="flex flex-col items-center gap-6">
              <div v-if="minerais.length > 0" v-for="mineral in minerais" :key="mineral.id"
                   class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-900 shadow max-w-fit min-w-[220px] max-w-[90vw] text-center">
                
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-1">{{ mineral.nome }}</h3>

                <div class="mb-2">
                  <template v-if="!mineral.fotos || mineral.fotos.length === 0">
                    <p class="text-gray-500 text-sm">Sem foto cadastrada</p>
                  </template>
                  <template v-else>
                    <img :src="`/storage/${(mineral.fotos.find(f => f.capa) || mineral.fotos[0]).caminho}`"
                         alt="Foto do Mineral"
                         class="h-auto max-h-[144px] w-auto max-w-[128px] object-cover rounded mx-auto" />
                  </template>
                </div>

                <div class="flex justify-center gap-2 mt-2">
                  <a :href="route('minerais.edit', mineral.id)"
                     class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Editar</a>
                  <button @click="submitDelete(mineral.id)"
                          class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Excluir</button>
                </div>
              </div>

              <p v-else class="text-center text-gray-600 dark:text-gray-300">Nenhum mineral cadastrado.</p>
            </div>
            <!-- Paginação Mobile -->
            <div v-if="!Desktop && props.minerais.links.length > 0" class="mt-6 flex flex-wrap justify-center gap-2">
              <button
                v-for="link in props.minerais.links"
                :key="link.label"
                :disabled="!link.url"
                @click="router.get(link.url)"
                v-html="link.label"
                class="mx-1 px-3 py-1 rounded text-sm"
                :class="{
                  'bg-blue-600 text-white': link.active,
                  'text-gray-500 dark:text-gray-30': !link.active,
                  'text-gray-400': !link.url
                }"
              />
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
