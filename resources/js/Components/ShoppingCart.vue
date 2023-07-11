<script setup lang="ts">

import {ref} from 'vue';


 

const props = defineProps<{
    isOpened: string
}>()

 

const emit = defineEmits(['toggle']);

 

function setIsOpened(value) {

  emit('toggle', value);

}

</script>

 

<template>

  <button @click="setIsOpened('changed')">

    FYI: my value from clickable child is "changed"

  </button>

</template>