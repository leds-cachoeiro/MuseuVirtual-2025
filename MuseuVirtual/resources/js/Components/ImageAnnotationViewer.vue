<script setup>
import { ref, watch, nextTick, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  show: Boolean,
  imagem: Object, // { caminho: string, anotacoes: array }
});

const emit = defineEmits(['close']);

const anotacoes = ref([]);
const imagemRef = ref(null);
const modalRef = ref(null);

watch(
  () => props.imagem,
  async (novaImagem) => {
    if (!novaImagem) {
      anotacoes.value = [];
      return;
    }
    anotacoes.value = (novaImagem.anotacoes || []).map(a => ({
      xOriginal: a.x,
      yOriginal: a.y,
      x: 0,
      y: 0,
      texto: a.texto,
      mostrarTexto: true,
    }));

    await nextTick();
    ajustarPosicoes();
  },
  { immediate: true }
);

const fechar = () => {
  emit('close');
};

const toggleTextoAnotacao = (index) => {
  anotacoes.value[index].mostrarTexto = !anotacoes.value[index].mostrarTexto;
};

const onBackgroundClick = (e) => {
  if (e.target === modalRef.value) {
    fechar();
  }
};

const onEscape = (e) => {
  if (e.key === 'Escape') {
    fechar();
  }
};

onMounted(() => {
  document.addEventListener('keydown', onEscape);
});

onUnmounted(() => {
  document.removeEventListener('keydown', onEscape);
});

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

watch(imagemRef, async (val) => {
  if (!val) return;
  await nextTick();
  ajustarPosicoes();
});

window.addEventListener('resize', () => {
  ajustarPosicoes();
});

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
  <div
    v-if="show"
    ref="modalRef"
    @click="onBackgroundClick"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
  >
    <div
      class="bg-white p-4 rounded relative overflow-auto"
      style="max-width: 90vw; max-height: 90vh;"
    >
      <button @click="fechar" class="absolute top-2 right-2 text-3xl font-bold">&times;</button>
      <h3 class="text-lg font-semibold mb-4">Visualização da Imagem com Anotações</h3>

      <div class="relative">
        <img
          v-if="imagem"
          :src="`/storage/${imagem.caminho}`"
          ref="imagemRef"
          class="mx-auto max-w-full max-h-[80vh] w-auto h-auto"
          alt="Imagem Anotada"
        />

        <div
          v-for="(anotacao, index) in anotacoes"
          :key="index"
          :style="{ top: anotacao.y + 'px', left: anotacao.x + 'px', position: 'absolute' }"
        >
          <div
            @click="toggleTextoAnotacao(index)"
            class="w-4 h-4 bg-red-600 rounded-full pulsante cursor-pointer"
            title="Clique para mostrar/esconder texto"
          ></div>
          <div
            v-if="anotacao.mostrarTexto"
            :class="calcularClassePosicaoTexto(anotacao)"
          >
            <p>{{ anotacao.texto }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes pulse {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.5); opacity: 0.8; }
  100% { transform: scale(1); opacity: 1; }
}

.pulsante {
  animation: pulse 2s infinite;
}

.texto-direita,
.texto-esquerda {
  position: absolute;
  top: 100%;
  margin-top: 4px;
  z-index: 10;
  max-width: 40%;
  min-width: 120px;
  word-break: break-word;
  background: white;
  padding: 0.5rem;
  border-radius: 0.375rem;
  box-shadow: 0 2px 8px rgb(0 0 0 / 0.15);
}

.texto-direita {
  left: 8px;
}

.texto-esquerda {
  right: 8px;
  transform-origin: top right;
}

.texto-meio {
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  margin-top: 4px;
  z-index: 10;
  max-width: 45%;
  min-width: 150px;
  word-break: break-word;
  background: white;
  padding: 0.5rem;
  border-radius: 0.375rem;
  box-shadow: 0 2px 8px rgb(0 0 0 / 0.15);
}
</style>
