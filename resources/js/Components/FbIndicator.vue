<script setup lang="ts">
    import { Leistung } from '../Interfaces/Leistung'
    import { SvwsUiIcon } from '@svws-nrw/svws-ui'

    const props = defineProps<{ leistung: Leistung }>()
    const emit = defineEmits(['clicked'])
    const clicked = (): void => emit('clicked')
</script>

<template>
    <button @click="clicked">
        <SvwsUiIcon>
            <mdi-checkbox-marked-outline v-if="props.leistung.fachbezogeneBemerkungen"></mdi-checkbox-marked-outline>
            <mdi-checkbox-blank-outline v-else></mdi-checkbox-blank-outline>
        </SvwsUiIcon>
        {{ props.leistung.fachbezogeneBemerkungen }}
    </button>
</template>

<style scoped>
    button {
        @apply
        ui-flex ui-gap-3 ui-items-start ui-justify-start
        ui-text-left
        ui-leading-6
    }
</style>
