<script setup>
import swal from 'sweetalert'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const props = defineProps({
  eons: { type: Array, default: () => [] }
});

const selected = reactive({ eon: null, era: null, periodo: null });
const showAquisi = ref([]);

function selectEon(eon) {
  selected.eon = eon;
  selected.era = null;
  selected.periodo = null;
  showAquisi.value = [];
}

function selectEra(era) {
  selected.era = era;
  selected.periodo = null;
  if (!era.periodos || era.periodos.length === 0) {
    showAquisi.value = era.aquisicoes || [];
  } else {
    showAquisi.value = [];
  }
}

function selectPeriodo(periodo) {
  selected.periodo = periodo;
  showAquisi.value = periodo.aquisicoes || [];
}

function excluirAssociacao(id) {
    swal({
    title: "Excluir?",
    text: "Tem certeza que deseja excluir esta rocha?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((apagar) => {
    if (apagar) {
      router.delete(route('timeline.associacoes.destroy', id));
      location.reload();
    }
  });
}

</script>

<template>
  <Head title="Linha do Tempo" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-800 leading-tight">
          Linha do Tempo Geológica
        </h2>
        <a :href="route('timeline.create')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Adicionar à Linha do Tempo
        </a>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg p-6">

          <!-- Eons -->
          <div class="flex gap-2 mb-4">
            <button v-for="eon in eons" :key="eon.id" @click="selectEon(eon)"
                    class="px-3 py-1 border rounded" :class="{'bg-blue-600 text-white': selected.eon === eon}">
              {{ eon.nome }}
            </button>
          </div>

          <!-- Eras -->
          <div v-if="selected.eon" class="flex gap-2 mb-4">
            <button v-for="era in selected.eon.eras" :key="era.id" @click="selectEra(era)"
                    class="px-3 py-1 border rounded" :class="{'bg-blue-600 text-white': selected.era === era}">
              {{ era.nome }}
            </button>
          </div>

          <!-- Períodos -->
          <div v-if="selected.era && selected.era.periodos?.length" class="flex gap-2 mb-4">
            <button v-for="periodo in selected.era.periodos" :key="periodo.id" @click="selectPeriodo(periodo)"
                    class="px-3 py-1 border rounded" :class="{'bg-blue-600 text-white': selected.periodo === periodo}">
              {{ periodo.nome }}
            </button>
          </div>

          <!-- Rochas e Minerais -->
          <div v-if="showAquisi.length" class="grid grid-cols-3 gap-4">
            <div v-for="item in showAquisi" :key="item.id" class="border rounded overflow-hidden relative" style="width:250px; height:100px;">

              <!-- Botão Excluir Associação -->
              <button @click="excluirAssociacao(item.id)"
                      class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded">
                X
              </button>

              <!-- Imagem da Rocha -->
              <template v-if="item.rocha">
                <template v-if="!item.rocha.fotos || item.rocha.fotos.length === 0">
                  <p>Não existe fotos cadastradas</p>
                </template>
                <template v-else>
                  <img :src="`/storage/${(item.rocha.fotos.find(f => f.capa) || item.rocha.fotos[0]).caminho}`"
                       class="w-full h-full object-cover">
                </template>
                <div class="absolute bottom-0 w-full bg-black bg-opacity-50 text-white text-xs text-center">
                  {{ item.rocha.nome }}
                </div>
              </template>

              <!-- Imagem do Mineral -->
              <template v-else-if="item.mineral">
                <template v-if="!item.mineral.fotos || item.mineral.fotos.length === 0">
                  <p>Não existe fotos cadastradas</p>
                </template>
                <template v-else>
                  <img :src="`/storage/${(item.mineral.fotos.find(f => f.capa) || item.mineral.fotos[0]).caminho}`"
                       class="w-full h-full object-cover">
                </template>
                <div class="absolute bottom-0 w-full bg-black bg-opacity-50 text-white text-xs text-center">
                  {{ item.mineral.nome }}
                </div>
              </template>

            </div>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
button:focus { outline: none; }
</style>
