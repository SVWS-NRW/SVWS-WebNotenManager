<script setup lang="ts">
    import { formatStringBasedOnGender } from '../Helpers/bemerkungen.helper'
    import {Leistung, Schueler} from '../types'

    import { SvwsUiIcon } from '@svws-nrw/svws-ui'

    const props = defineProps<{
        model: Schueler|Leistung,
        bemerkung: string|null,
    }>()

    const emit = defineEmits(['clicked'])
    const clicked = (): void => emit('clicked')
</script>

<template>
    <button @click="clicked" class="indicator">
        <SvwsUiIcon>
            <mdi-checkbox-marked-outline v-if="props.bemerkung"></mdi-checkbox-marked-outline>
            <mdi-checkbox-blank-outline v-else></mdi-checkbox-blank-outline>
        </SvwsUiIcon>

        <span class="indicator__bemerkung">
            {{ formatStringBasedOnGender(props.bemerkung, props.model) }}
        </span>
    </button>
</template>

<style scoped>
    .indicator {
        @apply
        ui-max-w-full
        ui-flex ui-gap-1.5 ui-items-center ui-justify-start
    }

    .indicator__bemerkung {
        @apply
        ui-truncate
    }
</style>
