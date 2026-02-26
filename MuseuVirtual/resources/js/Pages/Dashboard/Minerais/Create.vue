<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, reactive, computed } from 'vue';
import TinyMCEEditor from '@/Components/TinyMCEEditor.vue';

const form = reactive({
    nome: '',
    descricao: '',
    propriedades: '',
    idJazida: '',
    fotos: [],
    capa_nome: '',
    rochas_ids: [],
});

const props = defineProps({
  jazidas: Array,
  rochas: Array,
});

const searchRocha = ref('');

const filteredRochas = computed(() => {
    return props.rochas.filter(r => r.nome.toLowerCase().includes(searchRocha.value.toLowerCase()));
});

const fotoInput = ref(null);
const previewFotos = ref([]);
const associarJazida = ref(false);

function handleFileChange(event) {
    const files = Array.from(event.target.files);
    form.fotos = files;
    previewFotos.value = files.map((file, index) => ({
        file,
        url: URL.createObjectURL(file),
        isCapa: false,
        name: file.name
    }));
    form.capa_nome = ''; // reset
}

function setCapa(index) {
    previewFotos.value.forEach((foto, i) => {
        foto.isCapa = i === index;
    });
    form.capa_nome = previewFotos.value[index].name;
}

function submitForm() {
    const payload = new FormData();
    payload.append('nome', form.nome);
    payload.append('descricao', form.descricao);
    payload.append('propriedades', form.propriedades);
    payload.append('idJazida', form.idJazida);

    form.rochas_ids = form.rochas_ids.map(Number);
    form.rochas_ids.forEach(id => payload.append('rochas_ids[]', id));

    form.fotos.forEach(f => payload.append('foto[]', f));
    payload.append('capa_nome', form.capa_nome);
    
    router.post(route('minerais.store'), payload);
}

</script>

<template>
  <Head title="Cadastrar Mineral" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
        Cadastrar Mineral
      </h2>
    </template>

<!-- Formulário de Criação -->
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
              <!-- Nome -->
              <div class="mb-4">
                <label for="nome" class="block font-medium">Nome</label>
                <input id="nome" v-model="form.nome" type="text" required class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" />
              </div>

              <!-- Descrição -->
              <div class="mb-4">
                <label for="descricao" class="block font-medium">Descrição</label>
                <TinyMCEEditor v-model="form.descricao" />
              </div>

              <!-- Propriedades -->
              <div class="mb-4">
                <label for="propriedades" class="block font-medium">Propriedades</label>
                <input id="propriedades" v-model="form.propriedades" type="text" required class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" />
              </div>

              <!-- Associar Jazida -->
              <div class="mb-4">
                <label for="jazida" class="block font-medium">Associar este mineral á alguma jazida?</label>
                <div id="switch">
                  <p>Não</p>
                  <label class="switch">
                    <input type="checkbox" v-model="associarJazida">
                    <span class="slider round"></span>
                  </label>
                  <p>Sim</p>
                </div>
                <select v-if="associarJazida" v-model="form.idJazida" class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                  <option disabled value="">Escolha uma jazida...</option>
                <option v-for="jazida in props.jazidas" :key="jazida.id" :value="jazida.id">{{ jazida.localizacao }}</option>
                </select>
              </div>

              <!-- Associar Rocha -->
              <div class="mb-4">
                <label class="block font-medium">Associar mineral a rochas</label>
                <input type="text" placeholder="Pesquisar rocha..." v-model="searchRocha"
                  class="block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm mb-2" />

                <div class="space-y-2 max-h-40 overflow-y-auto border rounded p-2">
                  <div v-for="rocha in filteredRochas" :key="rocha.id" class="flex items-center">
                    <input type="checkbox" 
                          :value="rocha.id" 
                          v-model="form.rochas_ids" 
                          class="mr-2" />
                    <span>{{ rocha.nome }}</span>
                  </div>
                </div>
              </div>

              <!-- Fotos -->
              <div class="mb-4">
              <label for="foto" class="block font-medium">Fotos do mineral</label>
              <input ref="fotoInput" @change="handleFileChange" type="file" id="foto" multiple class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:bg-gray-100 file:border-0 file:py-2 file:px-4 file:rounded file:text-sm file:font-semibold file:text-gray-700 file:cursor-pointer hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600" />
              </div>
              <div v-if="previewFotos.length" class="mt-4 grid grid-cols-3 gap-4">
                  <div v-for="(foto, index) in previewFotos" :key="foto.url"
                      class="card cursor-pointer relative border border-gray-300 rounded-lg overflow-hidden aspect-w-3 aspect-h-4"
                      @click="setCapa(index)">
                      <img :src="foto.url" class="w-full h-full object-cover"/>
                      <div v-if="foto.isCapa" class="absolute top-2 left-2 bg-white px-2 py-1 text-xs font-semibold text-gray-800"> Capa </div>
                  </div>
              </div>
              <!-- Botão de Envio -->
              <div class="mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"> Criar Mineral </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </AuthenticatedLayout>
</template>

<style scoped>
.card img {
    transition: transform 0.3s ease-in-out;
}

.card:hover img {
    transform: scale(1.05);
}

.card:hover {
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
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

/* O switch */
.switch {
  position: relative;
  display: inline-block;
  width: 70px;
  height: 30px;
}

#switch { 
  display: flex;
  align-items: center;
  column-gap: 5px;
}

/* Esconder checkbox padrão*/
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* Slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 22px;
  width: 22px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
} 
  

input:checked + .slider:before {
  -webkit-transform: translateX(40px);
  -ms-transform: translateX(40px);
  transform: translateX(40px);
}

.slider.round {
  border-radius: 15px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>