<script setup lang="ts">
    import { Schueler } from '@/Interfaces/Schueler'
    import { Leistung } from '@/Interfaces/Leistung'
    import { floskelgruppen } from '@/Interfaces/Floskelgruppe'
    import { formatBasedOnGender } from '@/Helpers/bemerkungen.helper'

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const props = defineProps<{
        model: Schueler | Leistung,
        bemerkung: string | null,
        floskelgruppe: 'asv' | 'aue' | 'zb' | 'fb',
    }>()

    const bemerkungButtonAriaLabel = (schueler: Schueler): string =>
        `${floskelgruppen[props.floskelgruppe]} für ${schueler.vorname} ${schueler.nachname} öffnen`

    const emit = defineEmits<EmitsOptions>()
</script>

<template>
    <button type="button" @click="emit('clicked')" :aria-label="bemerkungButtonAriaLabel">
        <span>
            <ri-checkbox-line v-if="props.bemerkung"></ri-checkbox-line>
            <ri-checkbox-blank-line v-else></ri-checkbox-blank-line>
        </span>

        <span class="bemerkung">
            {{ formatBasedOnGender(props.bemerkung, props.model) }}
        </span>
    </button>
</template>

<style scoped>
    button {
        @apply ui-max-w-full ui-flex ui-gap-1.5 ui-items-center ui-justify-start
    }

    .bemerkung {
        @apply ui-truncate
    }
</style>
