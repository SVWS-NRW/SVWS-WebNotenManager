<template>
    <button
        type="button"
        @click="emit('clicked')"
        :aria-label="bemerkungButtonAriaLabel()"
    >
        {{ value }}
    </button>
</template>


<script setup lang="ts">
    import { Leistung, Schueler } from '@/Interfaces/Interface';
    import { Floskelgruppe, floskelgruppen } from '@/Interfaces/Floskelgruppe';

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const emit = defineEmits<EmitsOptions>();

    const props = defineProps<{
        model: Leistung|Schueler,
        floskelgruppe: any,
        value: any,
    }>();

    const bemerkungButtonAriaLabel = (): string => {
        return `Wechseln zu ${floskelgruppen[props.floskelgruppe as keyof floskelgruppen]} für ${props.model.vorname} ${props.model.nachname}`;
    }
</script>


<style scoped>
/**/
</style>