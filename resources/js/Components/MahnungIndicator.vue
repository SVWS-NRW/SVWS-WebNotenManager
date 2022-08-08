<script setup lang="ts">
    import { reactive } from 'vue'
    import { useStore } from "../store"
    import axios from 'axios'

    const store = useStore()
    const props = defineProps(['leistung'])

    let state = reactive({
        istGemahnt: Boolean(props.leistung.istGemahnt),
    })

    const setMahnung = (istGemahnt: boolean): Promise<void> => axios
        .post(route('set_mahnung', props.leistung), { istGemahnt: istGemahnt })
        .then((): void => store.updateLeistungMahnung(props.leistung, istGemahnt))
    
</script>

<template>
    <SvwsUiCheckbox v-model="state.istGemahnt" @update:modelValue="setMahnung" />
</template>

<style scoped>
    .svws-ui--icon > span {
        @apply sr-only
    }

    .svws-ui--icon > svg {
        @apply w-7 h-7l
    }
</style>
