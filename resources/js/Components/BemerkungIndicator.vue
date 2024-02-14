<template>
    <button type="button" @click="emit('clicked')" :aria-label="bemerkungButtonAriaLabel">
            <SvwsUiCheckbox v-model="bemerkungCheckboxStatus" @click.prevent.self></SvwsUiCheckbox>
        <span class="bemerkung">
            {{ formatBasedOnGender(props.bemerkung, props.model) }}
        </span>
    </button>
</template>


<script setup lang="ts">
    import { Schueler } from '@/Interfaces/Schueler';
    import { Leistung } from '@/Interfaces/Leistung';
    import { floskelgruppen } from '@/Interfaces/Floskelgruppe';
    import { formatBasedOnGender } from '@/Helpers/bemerkungen.helper';
    import { SvwsUiBadge, SvwsUiButton, SvwsUiModal, SvwsUiCheckbox } from '@svws-nrw/svws-ui';
    import { computed, Ref, ref } from 'vue';
    

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const props = defineProps<{
        model: Schueler | Leistung,
        bemerkung: string | null,
        floskelgruppe: 'asv' | 'aue' | 'zb' | 'fb',
    }>();
    
    const bemerkungCheckboxStatus = computed(() => (props.bemerkung === null || props.bemerkung === "" )  ? false : true);

    const bemerkungButtonAriaLabel = (schueler: Schueler): string => { 
        return `${floskelgruppen[props.floskelgruppe]} für ${schueler.vorname} ${schueler.nachname} öffnen`;
    }

    const emit = defineEmits<EmitsOptions>();
</script>


<style scoped>

    button {
        @apply max-w-full flex gap-1.5 items-center justify-start
    }

    .bemerkung {
        @apply truncate
    }
    
</style>
