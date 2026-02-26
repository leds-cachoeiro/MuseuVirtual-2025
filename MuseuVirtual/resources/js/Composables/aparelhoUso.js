// composables/useDevice.js
import { ref, onMounted, computed } from 'vue';

export function aparelhoUso() {
  const Mobile = ref(false);

  onMounted(() => {
    Mobile.value = /Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  });

  return {
    Mobile,
    Desktop: computed(() => !Mobile.value)
  };
}
