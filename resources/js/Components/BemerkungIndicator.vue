<script setup lang="ts">
    import { formatStringBasedOnGender } from '@/Helpers/bemerkungen.helper'
    import { Schueler } from '@/Interfaces/Schueler'
    import { Leistung } from '@/Interfaces/Leistung'

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const props = defineProps<{
        model: Schueler | Leistung,
        bemerkung: string | null,
        floskelgruppe: 'asv' | 'aue' | 'zb',
        disabled: boolean,
    }>()

    const floskelgruppen: any = {
        'asv': 'Arbeits- und Sozialverhalten',
        'aue': 'Außerunterrichtliches Engagement',
        'zb': 'Zeugnisbemerkung',
    }

    const bemerkungButtonAriaLabel = (schueler: Schueler): string =>
        `${floskelgruppen[props.floskelgruppe]} für ${schueler.vorname} ${schueler.nachname} öffnen`

    const emit = defineEmits<EmitsOptions>()
</script>

<template>
    <span class="bemerkung" v-if="props.disabled">
        {{ formatStringBasedOnGender(props.bemerkung, props.model) }}
    </span>

    <button v-else type="button" @click="emit('clicked')"  :aria-label="bemerkungButtonAriaLabel">
        <span>
            <mdi-checkbox-marked-outline v-if="props.bemerkung"></mdi-checkbox-marked-outline>
            <mdi-checkbox-blank-outline v-else></mdi-checkbox-blank-outline>
        </span>

        <span class="bemerkung">
            {{ formatStringBasedOnGender(props.bemerkung, props.model) }}
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
