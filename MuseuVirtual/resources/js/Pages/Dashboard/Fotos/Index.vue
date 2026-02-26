<script setup>
import swal from 'sweetalert'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import ImageAnnotationViewer from '@/Components/ImageAnnotationViewer.vue';
import { ref, watch, computed } from 'vue';

const props = defineProps({
  fotos: Object,
  rochas: Array,
  minerais: Array,
  jazidas: Array,
  filters: Object,
});

const page = usePage();
const flashMessage = computed(() => page.props.flash?.success ?? null);

const mostrarModalVisualizacao = ref(false);
const imagemSelecionada = ref(null);

const abrirModalVisualizacao = (foto) => {
  imagemSelecionada.value = foto;
  mostrarModalVisualizacao.value = true;
};

const fecharModalVisualizacao = () => {
  mostrarModalVisualizacao.value = false;
};

function deletar(id) {
    swal({
    title: "Excluir?",
    text: "Tem certeza que deseja excluir esta foto?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((apagar) => {
    if (apagar) {
      router.delete(route('fotos-destroy', id), {
      preserveState: true});
      location.reload();
    }
  });
}

const filters = ref({
  termo: props.filters?.termo || '',
  comRocha: props.filters?.comRocha ?? false,
  comMineral: props.filters?.comMineral ?? false,
  comJazida: props.filters?.comJazida ?? false,
  semLigacao: props.filters?.semLigacao ?? false,
});

watch(
  filters,
  (newFilters) => {
    router.get(
      route('fotos.index'),
      {
        ...newFilters,
        comRocha: newFilters.comRocha ? 1 : 0,
        comMineral: newFilters.comMineral ? 1 : 0,
        comJazida: newFilters.comJazida ? 1 : 0,
        semLigacao: newFilters.semLigacao ? 1 : 0,
      },
      { preserveState: true, replace: true }
    );
  },
  { deep: true }
);
</script>

<template>
  <Head title="Lista de Fotos" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-black leading-tight">Lista de Fotos</h2>
        <a :href="route('fotos-create')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Adicionar foto
        </a>
      </div>
    </template>

    <div class="py-12 container mx-auto px-4">
      <div v-if="flashMessage" class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-800 rounded-lg">
        {{ flashMessage }}
      </div>

      <!-- Área de filtros -->
      <div class="mb-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg font-semibold mb-4 dark:text-white">Filtros</h3>
        <div class="flex flex-wrap gap-4 items-center">
          <input
            v-model="filters.termo"
            type="text"
            placeholder="Buscar por nome de rocha, mineral ou jazida..."
            class="border rounded p-2 w-full md:w-96 dark:bg-gray-700 dark:text-white"
          />

          <label class="inline-flex items-center space-x-2 dark:text-white">
            <input type="checkbox" v-model="filters.comRocha" />
            <span>Com Rocha</span>
          </label>

          <label class="inline-flex items-center space-x-2 dark:text-white">
            <input type="checkbox" v-model="filters.comMineral" />
            <span>Com Mineral</span>
          </label>

          <label class="inline-flex items-center space-x-2 dark:text-white">
            <input type="checkbox" v-model="filters.comJazida" />
            <span>Com Jazida</span>
          </label>

          <label class="inline-flex items-center space-x-2 dark:text-white font-semibold">
            <input type="checkbox" v-model="filters.semLigacao" />
            <span>Sem Nenhuma Ligação</span>
          </label>
        </div>
      </div>

      <!-- Tabela -->
      <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Foto</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Rocha</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Mineral</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Jazida</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Capa</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="foto in fotos.data" :key="foto.id">
              <td class="px-6 py-4">
                <img
                  :src="`/storage/${foto.caminho}`"
                  alt="Foto"
                  class="h-[144px] w-[128px] object-cover cursor-pointer rounded"
                  @click="abrirModalVisualizacao(foto)"
                />
              </td>
              <td class="px-6 py-4 dark:text-white">{{ foto.id }}</td>
              <td class="px-6 py-4 dark:text-white">{{ foto.rocha?.nome ?? '-' }}</td>
              <td class="px-6 py-4 dark:text-white">{{ foto.mineral?.nome ?? '-' }}</td>
              <td class="px-6 py-4 dark:text-white">{{ foto.jazida?.localizacao ?? '-' }}</td>
              <td class="px-6 py-4 dark:text-white">{{ foto.capa ? 'Sim' : 'Não' }}</td>
              <td class="px-6 py-4">
                <div class="flex justify-center gap-2">
                  <a :href="route('fotos-edit', foto.id)" class="inline-flex items-center px-2 py-1 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    Editar
                  </a>
                  <button @click="deletar(foto.id)" class="inline-flex items-center px-2 py-1 text-sm text-red-600 dark:text-red-400 hover:underline">
                    Excluir
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="fotos.data.length === 0">
              <td colspan="7" class="text-center py-4 text-white">Nenhuma foto encontrada.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginação -->
      <div class="mt-4 flex justify-center gap-2 flex-wrap">
        <button
          v-for="link in fotos.links"
          :key="link.label"
          v-html="link.label"
          :disabled="!link.url"
          @click="link.url && router.visit(link.url)"
          class="px-3 py-1 rounded-lg border text-sm transition"
          :class="{
            'bg-gray-800 text-white': link.active,
            'text-gray-600 border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700': !link.active,
            'cursor-not-allowed opacity-50': !link.url
          }"
        />
      </div>

      <!-- Modal -->
      <ImageAnnotationViewer
        :show="mostrarModalVisualizacao"
        :imagem="imagemSelecionada"
        @close="fecharModalVisualizacao"
      />
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos adicionais podem ser inseridos aqui se quiser */
</style>
