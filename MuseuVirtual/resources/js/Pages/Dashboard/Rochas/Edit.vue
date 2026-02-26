<script setup>
import swal from 'sweetalert'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import TinyMCEEditor from '@/Components/TinyMCEEditor.vue';

const props = defineProps({
    rocha: Object,
    jazidas: Array,
    minerais: Array, // minerais disponíveis para associação
});

const rocha = ref({
    ...props.rocha,
    minerais_ids: props.rocha.minerais_ids ?? [] // array de IDs já associados
});

const page = usePage();
const successMessage = computed(() => page.props?.flash?.success ?? null);

const searchMineral = ref('');

const filteredMinerais = computed(() => {
    return props.minerais.filter(m =>
        m.nome.toLowerCase().includes(searchMineral.value.toLowerCase())
    );
});

function submitForm() {
    rocha.value.minerais_ids = rocha.value.minerais_ids.map(Number);

    if (rocha.value.jazida_id === '89656') {
        rocha.value.jazida_id = null;
    }

    router.put(route('rochas.update', rocha.value.id), rocha.value);
}

function submitDeleteFoto(id) {
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
                onSuccess: () => {
                    rocha.value.fotos = rocha.value.fotos.filter(f => f.id !== id);
                },
            });
        }
    });
}
</script>

<template>
    <Head title="Editar Rocha" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ successMessage }}
                        </div>

                        <form @submit.prevent="submitForm">
                            <!-- Nome -->
                            <div class="mb-4">
                                <label for="nome" class="block font-medium">Nome</label>
                                <input v-model="rocha.nome" id="nome" type="text" required
                                    class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                            </div>

                            <!-- Descrição -->
                            <div class="mb-4">
                                <span for="descricao" class="block font-medium">Descrição</span>
                                <TinyMCEEditor v-model="rocha.descricao" />
                            </div>

                            <!-- Composição -->
                            <div class="mb-4">
                                <label for="composicao" class="block font-medium">Composição</label>
                                <input v-model="rocha.composicao" id="composicao" type="text"
                                    class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                            </div>

                            <!-- Tipo -->
                            <div class="mb-4">
                                <label for="tipo" class="block font-medium">Tipo de Rocha</label>
                                <select v-model="rocha.tipo" id="tipo" required
                                    class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                                    <option value="" disabled>Escolha uma rocha...</option>
                                    <option value="1">Ígneas</option>
                                    <option value="2">Metamórficas</option>
                                    <option value="3">Sedimentares</option>
                                </select>
                            </div>

                            <!-- Ornamental -->
                            <div class="mb-4">
                                <span class="block font-medium">É uma rocha ornamental?</span>
                                <div id="switch">
                                    <p>Não</p>
                                    <label class="switch" for="checkOrnamental">
                                        <input id="checkOrnamental" type="checkbox" v-model="rocha.ornamental">
                                        <span class="slider round"></span>
                                    </label>
                                    <p>Sim</p>
                                </div>
                            </div>

                            <!-- Jazida -->
                            <div class="mb-4">
                                <label for="jazida" class="block font-medium">Jazida Associada</label>
                                <select v-model="rocha.jazida_id" id="jazida"
                                    class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                                    <option value=''>Nenhuma jazida associada</option>
                                    <option v-for="jazida in props.jazidas" :key="jazida.id" :value="jazida.id">
                                        {{ jazida.localizacao }}
                                    </option>
                                </select>
                            </div>

                            <!-- Associar minerais -->
                            <div class="mb-4">
                                <label class="block font-medium">Associar rocha a minerais</label>
                                <input type="text" placeholder="Pesquisar mineral..." v-model="searchMineral"
                                    class="block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm mb-2" />

                                <div class="space-y-2 max-h-40 overflow-y-auto border rounded p-2">
                                    <div v-for="mineral in filteredMinerais" :key="mineral.id" class="flex items-center">
                                        <input type="checkbox"
                                            :value="Number(mineral.id)"
                                            v-model="rocha.minerais_ids"
                                            class="mr-2" />
                                        <span>{{ mineral.nome }}</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Salvar
                            </button>
                        </form>

                        <!-- Galeria de Fotos -->
                        <div class="mt-8 flex flex-wrap justify-center border-gray-300 dark:border-gray-700 rounded-md shadow-sm">
                            <h2 class="self-center font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                Fotos da Rocha:
                            </h2>
                            <a :href="route('fotos-create', { idRocha: rocha.id })"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 ml-4">
                                Adicionar Fotos
                            </a>
                        </div>

                        <div class="flex flex-wrap border-gray-300 dark:border-gray-700 rounded-md shadow-sm mt-4">
                            <p v-if="!rocha.fotos || rocha.fotos.length === 0">Não existem imagens cadastradas para esta rocha.</p>
                            <div v-else v-for="foto in rocha.fotos" :key="foto.id"
                                class="flex flex-col h-[212px] w-[160px] items-center justify-between p-2 m-2 border rounded-md dark:border-gray-700">
                                <img :src="`/storage/${foto.caminho}`" alt="Foto da Rocha" class="h-[144px] w-[128px] object-cover mb-2" />
                                <div class="flex items-center gap-2">
                                    <a :href="route('fotos-edit', foto.id)" class="bg-gray-600 text-white px-2 py-1 rounded hover:bg-gray-700 text-sm">
                                        Editar
                                    </a>
                                    <button @click="submitDeleteFoto(foto.id)"
                                        class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-sm">
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
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