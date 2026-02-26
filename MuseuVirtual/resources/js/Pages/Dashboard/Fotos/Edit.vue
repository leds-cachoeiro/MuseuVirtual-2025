<script setup>
import swal from 'sweetalert'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  fotos: Object,
  rochas: Array,
  minerais: Array,
  jazidas: Array
});


const previewFoto = ref(null);
const capaValue = ref(props.fotos.capa);

const form = useForm({
  _method: 'put',
  foto: null,
  capa: capaValue.value,
  idRocha: props.fotos.idRocha,
  idMineral: props.fotos.idMineral,
  idJazida: props.fotos.idJazida,
});

const submit = () => {
  form.post(route('fotos-update', props.fotos.id), {
    forceFormData: true,
  });
};

const atualizarCardCapa = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  form.foto = file;
  previewFoto.value = URL.createObjectURL(file);
};

const outraCapaJaExiste = props.fotos.capa === false && props.fotos.outraCapaExiste;

// ---------- CONTROLE DE ANOTAÇÕES ----------

const mostrarModalImagem = ref(false);

const anotacoes = ref(
  (props.fotos.anotacoes || []).map(a => ({
    id: a.id,
    xOriginal: a.x,
    yOriginal: a.y,
    x: 0,
    y: 0,
    texto: a.texto,
    mostrarTexto: true,
  }))
);

const anotacoesParaExcluir = ref([]);

const imagemRef = ref(null);

const abrirModalImagem = () => {
  mostrarModalImagem.value = true;
  nextTick().then(() => ajustarPosicoes());
};

const fecharModalImagem = () => {
  mostrarModalImagem.value = false;
};

const ajustarPosicoes = () => {
  if (!imagemRef.value) return;

  const img = imagemRef.value;
  const naturalWidth = img.naturalWidth;
  const naturalHeight = img.naturalHeight;

  const displayedWidth = img.clientWidth;
  const displayedHeight = img.clientHeight;

  const scaleX = displayedWidth / naturalWidth;
  const scaleY = displayedHeight / naturalHeight;

  anotacoes.value.forEach((anotacao) => {
    anotacao.x = anotacao.xOriginal * scaleX;
    anotacao.y = anotacao.yOriginal * scaleY;
  });
};

window.addEventListener('resize', ajustarPosicoes);

watch(imagemRef, async (newVal) => {
  if (!newVal) return;
  await nextTick();
  ajustarPosicoes();
});

const adicionarAnotacao = (e) => {
  if (!imagemRef.value) return;

  const bounds = imagemRef.value.getBoundingClientRect();
  const naturalWidth = imagemRef.value.naturalWidth;
  const naturalHeight = imagemRef.value.naturalHeight;

  const xExibido = e.clientX - bounds.left;
  const yExibido = e.clientY - bounds.top;

  const scaleX = naturalWidth / bounds.width;
  const scaleY = naturalHeight / bounds.height;

  const xNatural = xExibido * scaleX;
  const yNatural = yExibido * scaleY;

  swal("Digite o texto da anotação:", {
    content: "input",
  })
  .then((texto) => {
    if (texto && texto.trim() !== '') {
    anotacoes.value.push({
      id: null,
      xOriginal: xNatural,
      yOriginal: yNatural,
      x: xExibido,
      y: yExibido,
      texto: texto.trim(),
      mostrarTexto: true,
    });
  }
  });
  
};

const toggleAnotacao = (index) => {
  anotacoes.value[index].mostrarTexto = !anotacoes.value[index].mostrarTexto;
};

const editarAnotacao = (index) => {
  const anotacao = anotacoes.value[index];
  const novoTexto = prompt("Editar anotação:", anotacao.texto);
  if (novoTexto !== null && novoTexto.trim() !== '') {
    anotacao.texto = novoTexto.trim();
  }
};

const removerAnotacao = (index) => {
  const anotacao = anotacoes.value[index];
    swal({
    title: "Excluir?",
    text: "Tem certeza que deseja remover esta anotação?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((apagar) => {
    if (apagar) {
      if (anotacao.id) {
        anotacoesParaExcluir.value.push(anotacao.id);
      }
      anotacoes.value.splice(index, 1);
    }
  });
};

const salvarAnotacoes = () => {
  const payload = anotacoes.value.map(a => ({
    id: a.id,
    x: a.xOriginal,
    y: a.yOriginal,
    texto: a.texto,
  }));

  router.post(route('fotos.anotacoes.store', props.fotos.id), {
    anotacoes: payload,
    deletadas: anotacoesParaExcluir.value,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      mostrarModalImagem.value = false;
      anotacoesParaExcluir.value = [];
    },
  });
};

const calcularClassePosicaoTexto = (anotacao) => {
  if (!imagemRef.value) return 'texto-direita';

  const containerWidth = imagemRef.value.clientWidth;
  const boxMaxWidthPx = containerWidth * 0.4; // max width 40%
  const pinX = anotacao.x;

  if (pinX + 8 + boxMaxWidthPx < containerWidth) {
    return 'texto-direita';
  } else if (pinX > containerWidth * 0.3 && pinX < containerWidth * 0.7) {
    return 'texto-meio';
  } else {
    return 'texto-esquerda';
  }
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Editar Foto" />
    <div class="py-12">
      <div class="max-w-3xl mx-auto bg-gray-900 p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-semibold text-white">Editar Foto</h2>
          <button
            @click="abrirModalImagem"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Gerenciar Anotações
          </button>
        </div>

        <img
          :src="previewFoto || `/storage/${props.fotos.caminho}`"
          alt="Pré-visualização da foto"
          class="h-40 w-36 object-cover mb-6 rounded-lg border border-gray-700 shadow"
        />

        <form @submit.prevent="submit" class="space-y-6">
          <div>
            <label
              class="block mb-1 font-medium text-white"
              for="foto"
            >
              Alterar Foto
            </label>
            <input
              id="foto"
              type="file"
              @change="atualizarCardCapa"
              class="w-full rounded-lg border border-gray-600 bg-gray-800 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
          </div>
          <fieldset class="mb-4 border border-gray-700 rounded-lg p-4">
            <legend class="font-medium mb-2 text-white">Selecionar como capa?</legend>
            <div v-if="outraCapaJaExiste" class="text-yellow-400 text-sm mb-2">
              ⚠️ Já existe outra imagem marcada como capa para esta rocha/mineral/jazida. Você não pode marcar outra.
            </div>
            <div class="flex gap-6 items-center">
              <label class="flex items-center gap-2 cursor-pointer text-white">
                <input
                  type="radio"
                  value="1"
                  v-model="form.capa"
                  :disabled="outraCapaJaExiste"
                  :checked="form.capa == 1"
                  class="cursor-pointer"
                />
                <span>Sim</span>
              </label>

              <label class="flex items-center gap-2 cursor-pointer text-white">
                <input
                  type="radio"
                  value="0"
                  v-model="form.capa"
                  :checked="form.capa == 0"
                  class="cursor-pointer"
                />
                <span>Não</span>
              </label>

              <span
                v-if="form.capa == 1 && !outraCapaJaExiste"
                class="text-xs text-blue-400 ml-4"
              >
                Esta imagem será marcada como capa
              </span>
            </div>
          </fieldset>


          <!-- ROCHA -->
          <div>
            <label for="idRocha" class="block mb-1 font-medium text-white">Rocha</label>
            <select
              id="idRocha"
              v-model="form.idRocha"
              class="w-full rounded-lg border border-gray-600 bg-gray-800 text-white px-3 py-2"
            >
              <option value="">Escolha uma rocha...</option>
              <option
                v-for="rocha in props.rochas"
                :key="rocha.id"
                :value="rocha.id"
              >
                {{ rocha.id }} - {{ rocha.nome }}
              </option>
            </select>
          </div>

          <!-- MINERAL -->
          <div>
            <label for="idMineral" class="block mb-1 font-medium text-white">Mineral</label>
            <select
              id="idMineral"
              v-model="form.idMineral"
              class="w-full rounded-lg border border-gray-600 bg-gray-800 text-white px-3 py-2"
            >
              <option value="">Escolha um mineral...</option>
              <option
                v-for="mineral in props.minerais"
                :key="mineral.id"
                :value="mineral.id"
              >
                {{ mineral.id }} - {{ mineral.nome }}
              </option>
            </select>
          </div>

          <!-- JAZIDA -->
          <div>
            <label for="idJazida" class="block mb-1 font-medium text-white">Jazida</label>
            <select
              id="idJazida"
              v-model="form.idJazida"
              class="w-full rounded-lg border border-gray-600 bg-gray-800 text-white px-3 py-2"
            >
              <option value="">Escolha uma jazida...</option>
              <option
                v-for="jazida in props.jazidas"
                :key="jazida.id"
                :value="jazida.id"
              >
                {{ jazida.id }} - {{ jazida.localizacao }}
              </option>
            </select>
          </div>



          <button
            type="submit"
            class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition w-full font-semibold"
            :disabled="form.processing"
          >
            Atualizar Foto
          </button>
        </form>
      </div>
    </div>

    <!-- Modal Anotações -->
    <div
      v-if="mostrarModalImagem"
      class="fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center p-4"
    >
      <div
        class="bg-gray-900 p-6 rounded-lg w-full max-w-5xl max-h-[90vh] overflow-auto relative shadow-lg"
      >
        <button
          @click="fecharModalImagem"
          class="absolute top-3 right-3 text-3xl font-bold text-white hover:text-red-600 transition"
          aria-label="Fechar modal"
        >
          &times;
        </button>
        <h3 class="text-xl font-semibold mb-4 text-white">Clique na imagem para adicionar anotações</h3>

        <div class="relative border border-gray-700 rounded overflow-hidden cursor-crosshair">
          <img
            :src="`/storage/${props.fotos.caminho}`"
            @click="adicionarAnotacao"
            ref="imagemRef"
            alt="Imagem para anotações"
            class="w-full select-none rounded"
          />

          <div
            v-for="(anotacao, index) in anotacoes"
            :key="anotacao.id ?? index"
            :style="{ top: anotacao.y + 'px', left: anotacao.x + 'px', position: 'absolute' }"
            class="z-10"
          >
            <div
              @click="toggleAnotacao(index)"
              class="w-5 h-5 bg-red-600 rounded-full cursor-pointer pulsante border-2 border-white shadow"
              title="Clique para mostrar/esconder texto"
            ></div>
            <div
              v-if="anotacao.mostrarTexto"
              :class="calcularClassePosicaoTexto(anotacao)"
              class="select-text"
              style="background-color: rgba(31, 41, 55, 0.95);"
            >
              <p class="text-sm font-medium text-white whitespace-pre-wrap">{{ anotacao.texto }}</p>
              <div class="flex justify-between text-xs mt-1 space-x-4">
                <button @click.stop="editarAnotacao(index)" class="text-blue-400 hover:underline focus:outline-none">Editar</button>
                <button @click.stop="removerAnotacao(index)" class="text-red-400 hover:underline focus:outline-none">Excluir</button>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 text-right">
          <button
            @click="salvarAnotacoes"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold"
          >
            Salvar Anotações
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.bg-opacity-60 {
  backdrop-filter: blur(4px);
}

@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.5);
    opacity: 0.8;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.pulsante {
  animation: pulse 2s infinite;
}

.texto-direita,
.texto-esquerda,
.texto-meio {
  position: absolute;
  top: 100%;
  margin-top: 6px;
  z-index: 20;
  max-width: 40%;
  min-width: 120px;
  word-break: break-word;
  padding: 0.5rem;
  border-radius: 0.375rem;
  box-shadow: 0 2px 8px rgb(0 0 0 / 0.4);
}

.texto-direita {
  left: 8px;
  background-color: rgba(31, 41, 55, 0.95);
}

.texto-esquerda {
  right: 8px;
  transform-origin: top right;
  background-color: rgba(31, 41, 55, 0.95);
}

.texto-meio {
  left: 50%;
  transform: translateX(-50%);
  max-width: 45%;
  min-width: 150px;
  background-color: rgba(31, 41, 55, 0.95);
}
</style>
