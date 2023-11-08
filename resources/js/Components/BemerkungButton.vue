<script setup lang="ts">
    import { Leistung, Schueler } from '@/Interfaces/Interface'
    import { floskelgruppen } from '@/Interfaces/Floskelgruppe'

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    let props = defineProps<{
        leistung: Leistung|Schueler,
        floskelgruppe: keyof floskelgruppen
        value: any
    }>()

const bemerkungButtonAriaLabel = (): string =>
    `Wechseln zu ${floskelgruppen[props.floskelgruppe]} für ${props.leistung.vorname} ${props.leistung.nachname}`
</script>


<template>
    <button
        type="button"
        @click="emit('clicked')"
        :aria-label="bemerkungButtonAriaLabel()"
    >
        {{ value }}
    </button>
</template>
