<template>
  <div class="timeline">
    <!-- EONS -->
    <div class="nivel" id="eons">
      <div
        v-for="eon in eons"
        :key="eon.id"
        class="eon"
        @click="selectEon(eon.id)"
      >
        {{ eon.nome }}
      </div>
    </div>

    <!-- ERAS -->
    <div v-if="activeEon" class="nivel">
      <div
        v-for="era in erasFiltradas"
        :key="era.id"
        class="era"
        @click="selectEra(era.id)"
      >
        {{ era.nome }}
      </div>
    </div>

    <!-- PERÍODOS -->
    <div v-if="activeEra" class="nivel">
      <div
        v-for="periodo in periodosFiltrados"
        :key="periodo.id"
        class="periodo"
      >
        {{ periodo.nome }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  eons: {
    type: Array,
    default: () => []
  }
})

const activeEon = ref(null)
const activeEra = ref(null)

const erasFiltradas = computed(() => {
  if (!activeEon.value) return []
  const eon = props.eons.find(e => e.id === activeEon.value)
  return eon ? eon.eras : []
})

const periodosFiltrados = computed(() => {
  if (!activeEra.value) return []
  for (const eon of props.eons) {
    const era = eon.eras.find(er => er.id === activeEra.value)
    if (era) return era.periodos
  }
  return []
})

function selectEon(eonId) {
  activeEon.value = eonId
  activeEra.value = null
}
function selectEra(eraId) {
  activeEra.value = eraId
}
</script>

<style scoped>
.timeline {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.nivel {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  background-color: #41334d;
  padding: 15px;
  border-radius: 10px;
  width: 100%;
}

.eon, .era, .periodo {
  background-color: #603985;
  color: white;
  padding: 10px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.eon:hover, .era:hover, .periodo:hover {
  background-color: #8159a7;
}
</style>