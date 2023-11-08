<script setup lang="ts">
    import { Leistung, Schueler } from '@/Interfaces/Interface'
    import { floskelgruppen } from '@/Interfaces/Floskelgruppe'

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    let props = defineProps<{
        model: Leistung|Schueler,
        floskelgruppe: any
        value: any
    }>()

    const test = () => emit('clicked')

    const bemerkungButtonAriaLabel = (): string =>
        `Wechseln zu ${floskelgruppen[props.floskelgruppe]} für ${props.model.vorname} ${props.model.nachname}`
</script>


<template>
    <button
        type="button"
        @click="test()"
        :aria-label="bemerkungButtonAriaLabel()"
    >
        {{ value }}
    </button>
</template>
