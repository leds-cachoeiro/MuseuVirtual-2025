<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import TinyMCEEditor from '@/Components/TinyMCEEditor.vue';

const props = defineProps({
    jazida: Object,
})

const form = useForm({
    localizacao: props.jazida.localizacao,
    descricao: props.jazida.descricao,
})

function submit() {
    form.put(route('jazidas.update', props.jazida.id))
}

onMounted(()=>{
    console.log(props.jazida)
})
</script>

<template>

    <Head title="Jazidas " />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Editar Jazida
                </h2>
                <a :href="route('jazidas.index')"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Voltar
                </a>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <form @submit.prevent="submit">
                            <!-- Localização -->
                            <div class="mb-4">
                                <label for="localizacao" class="block font-medium">Localização</label>
                                <TextInput id="localizacao" v-model="form.localizacao" type="text"
                                    class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm"/>
                                <span v-if="form.errors.localizacao" class="text-red-500 text-sm">{{
                                    form.errors.localizacao }}</span>
                            </div>

                            <!-- Descrição -->
                            <div class="mb-4">
                                 <label for="descricao" class="block font-medium">Descrição</label>
                                <TinyMCEEditor v-model="form.descricao" />
                    
                                <span v-if="form.errors.descricao" class="text-red-500 text-sm">{{ form.errors.descricao
                                }}</span>
                            </div>

                            <div class="flex items-center justify-center mt-6">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>

                        <!-- Fotos da Jazida -->
                        <div class="mt-6">
                            <div class="mt-8 flex flex-wrap justify-center">
                                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mr-4">
                                    Fotos da Jazida:
                                </h2>
                                <a :href="route('fotos-create', { idJazida: jazida.id })"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 ml-4">
                                    Adicionar fotos
                                </a>
                            </div>  
                            <div class="flex flex-wrap">
                                <p v-if="props.jazida.fotos.length === 0">Não há fotos cadastradas para esta jazida.</p>
                                <div v-else v-for="foto in props.jazida.fotos" :key="foto.id"
                                    class="p-2 border rounded-md mx-2">
                                    <img :src="`/storage/${foto.caminho}`" alt="Foto da Jazida"
                                        class="h-32 w-32 object-cover mb-2" />
                                    <div class="flex gap-2">
                                        <a :href="route('fotos-edit', foto.id)" class="text-blue-500">Editar</a>
                                        <form :action="route('fotos-destroy', foto.id)" method="POST"
                                            @submit.prevent="$inertia.delete(route('fotos-destroy', foto.id))">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="text-red-500"
                                                @click.prevent="$inertia.delete(route('fotos-destroy', foto.id))">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>