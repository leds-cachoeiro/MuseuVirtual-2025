<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";

const props = defineProps({
    estatisticas: {
        type: Object,
        default: () => ({}),
    },
    atividades: {
        type: Array,
        default: () => [],
    },
    fotosSemLigacao: {
        type: Number,
        default: 0,
    },
});

onMounted(() => {
    console.log(props);
});

const busca = ref("");

onMounted(() => {
    // Preenche o campo de busca no Dashboard se a query string `nome` existir
    try {
        const params = new URLSearchParams(window.location.search);
        busca.value = params.get("nome") || "";
    } catch (e) {
        // ambiente sem window
    }
});

const estatisticasList = computed(() => [
    { label: "Rochas", total: props.estatisticas?.rochas ?? 0 },
    { label: "Minerais", total: props.estatisticas?.minerais ?? 0 },
    { label: "Jazidas", total: props.estatisticas?.jazidas ?? 0 },
    { label: "Fotos", total: props.estatisticas?.fotos ?? 0 },
]);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-10 px-4 bg-gray-900 min-h-screen">
            <h1 class="text-2xl font-bold text-white mb-6">
                Bem-vindo, {{ $page.props.auth.user?.name || "Usuário" }}
            </h1>

            <!-- Searchbar (form GET para /rochas) -->
            <form
                action="/rochas"
                method="get"
                class="mb-6 flex items-center gap-2"
            >
                <input
                    name="nome"
                    type="search"
                    :value="busca"
                    class="px-3 py-2 rounded w-full max-w-md text-gray-900"
                    :placeholder="'Buscar rocha por nome...'"
                />
                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded"
                >
                    Buscar
                </button>
            </form>

            <!-- Estatísticas -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div
                    v-for="item in estatisticasList"
                    :key="item.label"
                    class="bg-gray-800 rounded-lg shadow-sm p-4 text-center"
                >
                    <h3 class="text-sm text-gray-400">{{ item.label }}</h3>
                    <p class="text-2xl font-semibold text-indigo-400">
                        {{ item.total }}
                    </p>
                </div>
            </div>

            <!-- Alerta -->
            <div
                v-if="fotosSemLigacao > 0"
                class="bg-yellow-100 border border-yellow-400 text-yellow-800 rounded p-4 mb-6"
            >
                Você tem <strong>{{ fotosSemLigacao }}</strong> foto(s) sem
                ligação com rochas, minerais ou jazidas.
                <a href="/fotos" class="underline">Organize agora</a>.
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-gray-800 rounded-lg shadow p-5 mb-10">
                <h2 class="text-lg font-semibold text-white mb-4">
                    Ações Rápidas
                </h2>
                <div class="flex flex-col space-y-2">
                    <a
                        href="/fotos/create"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-center font-semibold"
                    >
                        Adicionar Foto
                    </a>
                    <a
                        href="/rochas/create"
                        class="bg-gray-600 hover:bg-gray-700 text-white py-2 rounded text-center font-semibold"
                    >
                        Cadastrar Rocha
                    </a>
                    <a
                        href="/minerais/create"
                        class="bg-gray-600 hover:bg-gray-700 text-white py-2 rounded text-center font-semibold"
                    >
                        Cadastrar Mineral
                    </a>
                    <a
                        href="/jazidas/create"
                        class="bg-gray-600 hover:bg-gray-700 text-white py-2 rounded text-center font-semibold"
                    >
                        Cadastrar Jazida
                    </a>
                </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    Atividades Recentes
                </h2>
                <div
                    v-if="atividades.length === 0"
                    class="text-gray-400 text-center py-8"
                >
                    Nenhuma atividade recente encontrada.
                </div>
                <ul v-else class="space-y-4">
                    <li
                        v-for="(atividade, index) in atividades"
                        :key="index"
                        class="flex items-center space-x-4"
                    >
                        <img
                            :src="atividade.imagem"
                            alt="preview"
                            class="w-14 h-14 object-cover rounded"
                            @error="
                                $event.target.src = '/images/placeholder.jpg'
                            "
                        />
                        <div class="flex-1 text-sm text-gray-200">
                            <p class="font-medium">{{ atividade.texto }}</p>
                            <span class="text-xs text-gray-400">{{
                                atividade.sub
                            }}</span>
                        </div>
                        <a
                            :href="`/${atividade.tipo}/${atividade.id}/edit`"
                            class="text-blue-400 text-sm font-medium hover:underline"
                        >
                            Ver
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
