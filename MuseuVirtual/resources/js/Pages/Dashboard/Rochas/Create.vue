<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, reactive, computed } from 'vue';
import TinyMCEEditor from '@/Components/TinyMCEEditor.vue';

const form = reactive({
    nome: '',
    descricao: '',
    composicao: '',
    tipo: '',
    fotos: [],
    capa_nome: '',
    jazida_id: '',
    minerais_ids: [],
    ornamental: '',
});

const props = defineProps({
    jazidas: Array,
    minerais: Array,
});

const associarJazida = ref(false);
const fotoInput = ref(null);
const previewFotos = ref([]);
const searchMineral = ref('');
const rochaOrnamental = ref(false);

const filteredMinerais = computed(() => {
    return props.minerais.filter(m => m.nome.toLowerCase().includes(searchMineral.value.toLowerCase()));
});

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
    payload.append('composicao', form.composicao);
    payload.append('tipo', form.tipo);

    if (associarJazida.value && form.jazida_id) {
        payload.append('jazida_id', form.jazida_id);
    }

    payload.append('ornamental', rochaOrnamental.value ? '1' : '0');

    form.minerais_ids = form.minerais_ids.map(Number);
    form.minerais_ids.forEach(id => payload.append('minerais_ids[]', id));

    form.fotos.forEach(f => payload.append('foto[]', f));
    payload.append('capa_nome', form.capa_nome);

    router.post(route('rochas.store'), payload);
}
</script>

<template>

    <Head title="Criar Rocha" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
                Cadastrar Rocha
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submitForm" enctype="multipart/form-data">
                            <!-- Nome -->
                            <div class="mb-4">
                                <label for="nome" class="block font-medium">Nome</label>
                                <input id="nome" v-model="form.nome" type="text" required
                                    class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" />
                            </div>

                            <!-- Descrição -->
                            <div class="mb-4">
                                <span for="descricao" class="block font-medium">Descrição</span>
                                <TinyMCEEditor v-model="form.descricao" />
                            </div>

                            <!-- Composição -->
                            <div class="mb-4">
                                <label for="composicao" class="block font-medium">Composição</label>
                                <input id="composicao" v-model="form.composicao" type="text" required
                                    class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" />
                            </div>

                            <!-- Tipo -->
                            <div class="mb-4">
                                <label for="tipo" class="block font-medium">Tipo de Rocha</label>
                                <select id="tipo" v-model="form.tipo" required
                                    class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                                    <option value="" disabled>Escolha uma rocha...</option>
                                    <option value="1">Ígneas</option>
                                    <option value="2">Metamórficas</option>
                                    <option value="3">Sedimentares</option>
                                </select>
                            </div>

                            <!-- Ornamental? -->
                            <div class="mb-4">
                                <label for="checkOrnamental" class="block font-medium">É uma rocha ornamental?</label>
                                <div class="mv-switch-wrap">
                                    <span>Não</span>
                                    <label class="mv-switch" for="checkOrnamental">
                                        <input id="checkOrnamental" type="checkbox" v-model="rochaOrnamental"
                                            aria-label="É uma rocha ornamental?">
                                        <span class="mv-switch__track mv-switch--round"></span>
                                    </label>
                                    <span>Sim</span>
                                </div>
                            </div>

                            <!-- Associar Jazida -->
                            <div class="mb-4">
                                <label class="block font-medium" for="checkJazida">Associar esta rocha à alguma
                                    jazida?</label>
                                <div class="mv-switch-wrap">
                                    <span>Não</span>
                                    <label class="mv-switch" for="checkJazida">
                                        <input id="checkJazida" type="checkbox" v-model="associarJazida"
                                            aria-label="Associar a uma jazida">
                                        <span class="mv-switch__track mv-switch--round"></span>
                                    </label>
                                    <span>Sim</span>
                                </div>
                            </div>

                            <div class="mb-4" v-if="associarJazida">
                                <label for="jazida_id" class="block font-medium">Selecionar Jazida</label>
                                <select id="jazida_id" v-model="form.jazida_id"
                                    class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                                    <option disabled value="">Escolha uma jazida...</option>
                                    <option v-for="jazida in props.jazidas" :key="jazida.id" :value="jazida.id">
                                        {{ jazida.localizacao }}
                                    </option>
                                </select>
                            </div>

                            <!-- Associar Minerais -->
                            <div class="mb-4">
                                <label class="block font-medium">Associar rocha a minerais</label>
                                <input type="text" placeholder="Pesquisar mineral..." v-model="searchMineral"
                                    class="block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm mb-2" />

                                <div class="space-y-2 max-h-40 overflow-y-auto border rounded p-2">
                                    <div v-for="mineral in filteredMinerais" :key="mineral.id"
                                        class="flex items-center">
                                        <input type="checkbox" :value="mineral.id" v-model="form.minerais_ids"
                                            class="mr-2" />
                                        <span>{{ mineral.nome }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fotos -->
                            <div class="mb-4">
                                <label for="foto" class="block font-medium">Fotos da Rocha</label>
                                <input ref="fotoInput" @change="handleFileChange" type="file" id="foto" multiple
                                    class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:bg-gray-100 file:border-0 file:py-2 file:px-4 file:rounded file:text-sm file:font-semibold file:text-gray-700 file:cursor-pointer hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600" />
                            </div>

                            <div v-if="previewFotos.length" class="mt-4 grid grid-cols-3 gap-4">
                                <div v-for="(foto, index) in previewFotos" :key="foto.url"
                                    class="card cursor-pointer relative border border-gray-300 rounded-lg overflow-hidden aspect-w-3 aspect-h-4"
                                    @click="setCapa(index)">
                                    <img :src="foto.url" class="w-full h-full object-cover" />
                                    <div v-if="foto.isCapa"
                                        class="absolute top-2 left-2 bg-white px-2 py-1 text-xs font-semibold text-gray-800">
                                        Capa
                                    </div>
                                </div>
                            </div>

                            <!-- Botão -->
                            <div class="mt-6">
                                <button type="submit"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                    Criar Rocha
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

/* layout dos rótulos do switch */
.mv-switch-wrap { 
  display: flex; 
  align-items: center; 
  column-gap: 6px; 
}

/* container do switch */
.mv-switch {
  position: relative;
  display: inline-block;
  width: 70px;
  height: 30px;
}

/* esconder checkbox nativo (acessível) */
.mv-switch input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

/* trilho do switch */
.mv-switch__track {
  position: absolute;
  inset: 0;
  cursor: pointer;
  background-color: #ccc;
  transition: .25s;
}

/* “bolinha” */
.mv-switch__track::before {
  content: "";
  position: absolute;
  height: 22px;
  width: 22px;
  left: 4px;
  top: 4px;
  background-color: #fff;
  transition: .25s;
}

/* estados */
.mv-switch input:checked + .mv-switch__track { background-color: #2196F3; }
.mv-switch input:focus + .mv-switch__track { box-shadow: 0 0 0 2px rgba(33,150,243,.35); }
.mv-switch input:checked + .mv-switch__track::before { transform: translateX(40px); }

/* cantos arredondados */
.mv-switch--round { border-radius: 15px; }
.mv-switch--round::before { border-radius: 50%; }
</style>