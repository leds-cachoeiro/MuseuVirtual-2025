<template>
  <div style="padding: 2rem;">
    <h2>Escolha uma imagem</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
      <img
        v-for="img in images"
        :key="img"
        :src="img"
        style="max-width: 150px; cursor: pointer; border: 2px solid #eee; border-radius: 4px;"
        @click="selectImage(img)"
      />
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps({ images: Array });

function selectImage(url) {
  window.opener.SetUrl(url);
  window.close();
}
</script>