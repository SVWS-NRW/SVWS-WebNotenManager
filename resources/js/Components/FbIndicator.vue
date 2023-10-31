<script setup lang="ts">
    import { Schueler } from '@/Interfaces/Schueler'
    import { Leistung } from '@/Interfaces/Leistung'
    import { floskelgruppen } from '@/Interfaces/Floskelgruppe'
    import { formatBasedOnGender } from '@/Helpers/bemerkungen.helper'

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const props = defineProps<{
        model: Leistung,
        bemerkung: string | null,
    }>()

    const bemerkungButtonAriaLabel = (schueler: Schueler): string =>
        `Fachbezogene Bemerkungen für ${schueler.vorname} ${schueler.nachname} öffnen`

    const emit = defineEmits<EmitsOptions>()
</script>

<template>
    <button type="button" @click="emit('clicked')" :aria-label="bemerkungButtonAriaLabel">
        <span>
            <mdi-checkbox-marked-outline v-if="props.bemerkung"></mdi-checkbox-marked-outline>
            <mdi-checkbox-blank-outline v-else></mdi-checkbox-blank-outline>
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
