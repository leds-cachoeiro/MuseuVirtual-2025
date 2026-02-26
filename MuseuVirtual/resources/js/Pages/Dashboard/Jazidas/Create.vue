<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TinyMCEEditor from '@/Components/TinyMCEEditor.vue';

const form = useForm({
  localizacao: '',
  descricao: '',
  foto: [],
  capa_nome: ''
})

const previewCards = ref([])

function handleFileChange(e) {
  const files = Array.from(e.target.files)
  previewCards.value = files.map(file => ({
    file,
    src: URL.createObjectURL(file),
    isCapa: false
  }))
  form.foto = files
  form.capa_nome = ''
}

function setCapa(fileName) {
  form.capa_nome = fileName
  previewCards.value.forEach(card => card.isCapa = card.file.name === fileName)
}

function submit() {
  form.post(route('jazidas.store'), {
    forceFormData: true
  })
}
</script>

<template>
  <Head title="Cadastrar Jazida" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
        Cadastrar Jazida
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submit" enctype="multipart/form-data">
              <div class="mb-4">
                <label for="localizacao" class="block font-medium">Localização</label>
                <TextInput id="localizacao" v-model="form.localizacao" type="text" class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required autocomplete="off" />
              </div>

              <div class="mb-4">
                <label for="descricao" class="block font-medium">Descrição</label>
                <TinyMCEEditor v-model="form.descricao" />
              </div>

              <div class="mb-4">
                <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fotos da Jazida</label>
                <input type="file" multiple @change="handleFileChange"
                       class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:bg-gray-100 file:border-0 file:py-2 file:px-4 file:rounded file:text-sm file:font-semibold file:text-gray-700 file:cursor-pointer hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600" />
              </div>

              <div class="mt-4 grid grid-cols-3 gap-4">
                <div v-for="(card, index) in previewCards" :key="index"
                     class="card cursor-pointer relative border border-gray-300 rounded-lg overflow-hidden aspect-w-3 aspect-h-4"
                     @click="setCapa(card.file.name)">
                  <img :src="card.src" class="w-full h-full object-cover" />
                  <div v-if="card.isCapa"
                       class="absolute top-2 left-2 bg-white px-2 py-1 text-xs font-semibold text-gray-800">
                    Capa
                  </div>
                </div>
              </div>

              <input type="hidden" name="capa_nome" :value="form.capa_nome">

              <div class="mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                  Criar Jazida
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.card:hover img {
  transform: scale(1.05);
  transition: transform 0.3s ease-in-out;
}

@media (max-width: 768px) {
  .grid-cols-3 {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .grid-cols-3 {
    grid-template-columns: 1fr;
  }
}
</style>
