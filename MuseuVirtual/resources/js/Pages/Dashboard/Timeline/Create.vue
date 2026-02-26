<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref, computed } from 'vue';


const props = defineProps({
    rochas: Array,
    minerais: Array,
    eons: Array
});

const searchRocha = ref('');
const searchMineral = ref('');

const filteredRochas = computed(() => {
    return props.rochas.filter(r => r.nome.toLowerCase().includes(searchRocha.value.toLowerCase()));
});

const filteredMinerais = computed(() => {
    return props.minerais.filter(m => m.nome.toLowerCase().includes(searchMineral.value.toLowerCase()));
});

const form = reactive({
    eon_id: '',
    era_id: '',
    periodo_id: null,
    rocha_id: null,
    mineral_id: null
});

function submitForm() {
    if (!form.era_id) {
        alert('Selecione uma Era.');
        return;
    }

    if (!form.rocha_id && !form.mineral_id) {
        alert('Selecione uma Rocha ou um Mineral.');
        return;
    }

    if (form.rocha_id && form.mineral_id) {
        alert('Selecione apenas um: Rocha OU Mineral.');
        return;
    }

    router.post('/timeline', {
        era_id: form.era_id,
        periodo_id: form.periodo_id,
        rocha_id: form.rocha_id,
        mineral_id: form.mineral_id
    }, { preserveScroll: true });
    router.visit(route('timeline.index'));
}
</script>

<template>
<Head title="Associar Formação Geológica" />

<AuthenticatedLayout>
    <template #header>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
        Associar Formação Geológica
    </h2>
    </template>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submitForm">
            <!-- Éons -->
            <div class="mb-4">
                <label class="block font-medium">Éon</label>
                <select v-model="form.eon_id" class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                <option value="" disabled>Escolha um Éon...</option>
                <option v-for="eon in props.eons" :key="eon.id" :value="eon.id">{{ eon.nome }}</option>
                </select>
            </div>

            <!-- Eras -->
            <div class="mb-4" v-if="form.eon_id">
                <label class="block font-medium">Era</label>
                <select v-model="form.era_id" class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                <option value="" disabled>Escolha uma Era...</option>
                <option v-for="era in props.eons.find(e => e.id === form.eon_id).eras" :key="era.id" :value="era.id">{{ era.nome }}</option>
                </select>
            </div>

            <!-- Períodos (nullable) -->
            <div class="mb-4" v-if="form.era_id">
                <label class="block font-medium">Período (opcional)</label>
                <select v-model="form.periodo_id" class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                <option value="">Nenhum</option>
                <option v-for="periodo in props.eons.find(e => e.id === form.eon_id).eras.find(er => er.id === form.era_id).periodos" :key="periodo.id" :value="periodo.id">{{ periodo.nome }}</option>
                </select>
            </div>

            <!-- Pesquisa Rocha -->
            <div class="mb-4">
                <label class="block font-medium">Rocha</label>
                <input type="text" placeholder="Pesquisar rocha..." v-model="searchRocha" class="block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm mb-2" />
                <select v-model="form.rocha_id" @change="form.mineral_id = null" class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                <option value="">Nenhuma</option>
                <option v-for="rocha in filteredRochas" :key="rocha.id" :value="rocha.id">{{ rocha.nome }}</option>
                </select>
            </div>

            <!-- Pesquisa Mineral -->
            <div class="mb-4">
                <label class="block font-medium">Mineral</label>
                <input type="text" placeholder="Pesquisar mineral..." v-model="searchMineral" class="block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm mb-2" />
                <select v-model="form.mineral_id" @change="form.rocha_id = null" class="block mt-1 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                <option value="">Nenhum</option>
                <option v-for="mineral in filteredMinerais" :key="mineral.id" :value="mineral.id">{{ mineral.nome }}</option>
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Associar</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    </div>  
</AuthenticatedLayout>
</template>