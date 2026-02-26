<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

    <Head title="Dashboard" />
        <template #header>
            <!-- MENSAGEM INDICANDO APENAS ADMINISTRADORES -->
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __("Bem-vindo(a) ao painel de controle. Apenas administradores têm acesso a esta área.") }}
            </p>
        </template>