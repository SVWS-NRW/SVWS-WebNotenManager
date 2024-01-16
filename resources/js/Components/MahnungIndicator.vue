<template>
    <svws-ui-modal :show="modal" size="small">
        <template #modalTitle>
            {{ props.leistung.vorname }} {{ props.leistung.nachname }}
            <SvwsUiBadge variant="primary">
                {{ props.leistung.klasse ?? props.leistung.kurs }}
            </SvwsUiBadge>
        </template>

        <template #modalDescription>
            <strong>Mahndatum:</strong> {{ mahndatumFormatted() }}
        </template>

        <template #modalActions>
            <svws-ui-button type="secondary" @click="close">
                Schliessen
            </svws-ui-button>
        </template>
    </svws-ui-modal>

    <span v-if="props.disabled">
        <span v-if="props.leistung.mahndatum" aria-description="Ist gemahnt mit Mahndatum">
            <span class="icon green" aria-hidden="true">
                <ri-checkbox-line ></ri-checkbox-line>
            </span>
        </span>
        <span v-else-if="leistung.istGemahnt" aria-description="Ist gemahnt ohne Mahndatum">
            <span class="icon red" aria-hidden="true">
                <ri-checkbox-line></ri-checkbox-line>
            </span>
        </span>
        <span v-else aria-description="Ist nicht gemahnt">
            <span class="icon" aria-hidden="true">
                <ri-checkbox-blank-line></ri-checkbox-blank-line>
            </span>
        </span>
    </span>

    <button v-else @click="props.leistung.mahndatum ? open() : toggleMahnung()">
        <span v-if="props.leistung.mahndatum" aria-description="Ist gemahnt mit Mahndatum">
           <span class="icon green" aria-hidden="true">
                <ri-checkbox-line ></ri-checkbox-line>
           </span>
        </span>
        <span v-else-if="leistung.istGemahnt" aria-description="Ist gemahnt ohne Mahndatum">
            <span class="icon red" aria-hidden="true">
               <ri-checkbox-line></ri-checkbox-line>
           </span>
        </span>
        <span v-else aria-description="Ist nicht gemahnt">
           <span class="icon" aria-hidden="true">
               <ri-checkbox-blank-line></ri-checkbox-blank-line>
           </span>
        </span>
    </button>
</template>


<script setup lang="ts">
    import { ref, Ref } from 'vue';
    import axios from 'axios';
    import moment from 'moment';
    import { Leistung } from '@/Interfaces/Interface';
    import { SvwsUiBadge, SvwsUiButton, SvwsUiModal } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
    }>();

    const modalVisible: Rev<boolean> = ref(false);
    const modal = (): boolean => modalVisible;
    const open = () => modal().value = true;
    const close = () => modal().value = false;

    const leistung: Ref<Leistung> = ref(props.leistung);

    const toggleMahnung = (): void => {
        leistung.value.istGemahnt = !leistung.value.istGemahnt;
        axios.post(route('api.mahnung', props.leistung.id), props.leistung)
            .catch((): boolean => leistung.value.istGemahnt = !leistung.value.istGemahnt);
    }

    const mahndatumFormatted = (): string => moment(new Date(props.leistung.mahndatum)).format('DD.MM.YYYY');
</script>


<style scoped>
    .red {
        @apply text-red-500
    }

    .green {
        @apply text-green-500
    }
    
</style>
