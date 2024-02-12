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

    <!-- function on click added because readonly seems to have no effect -->
    <span v-if="props.disabled">
        <span v-if="props.leistung.mahndatum" aria-description="Ist gemahnt mit Mahndatum">
            <SvwsUiCheckbox v-model="selectedCheckbox" @click="readonlyCheckbox($event)" color="success" readonly></SvwsUiCheckbox>
        </span>
        <span v-else-if="leistung.istGemahnt" aria-description="Ist gemahnt ohne Mahndatum">
            <SvwsUiCheckbox v-model="selectedCheckbox" @click="readonlyCheckbox($event)" color="error" readonly></SvwsUiCheckbox>
        </span>
        <span v-else aria-description="Ist nicht gemahnt">
                <SvwsUiCheckbox v-model="blankCheckbox" @click="readonlyCheckbox($event)" readonly></SvwsUiCheckbox>
        </span>
    </span>

    <button v-else @click="props.leistung.mahndatum ? open() : toggleMahnung()">
        <span v-if="props.leistung.mahndatum" aria-description="Ist gemahnt mit Mahndatum">
            <SvwsUiCheckbox v-model="selectedCheckbox" color="success"></SvwsUiCheckbox>
        </span>
        <span v-else-if="leistung.istGemahnt" aria-description="Ist gemahnt ohne Mahndatum">
            <SvwsUiCheckbox v-model="selectedCheckbox" color="error"></SvwsUiCheckbox>
        </span>
        <span v-else aria-description="Ist nicht gemahnt">
            <SvwsUiCheckbox v-model="blankCheckbox" readonly></SvwsUiCheckbox>
        </span>
    </button>
</template>


<script setup lang="ts">
    import { ref, Ref } from 'vue';
    import axios from 'axios';
    import moment from 'moment';
    import { Leistung } from '@/Interfaces/Interface';
    import { SvwsUiBadge, SvwsUiButton, SvwsUiModal, SvwsUiCheckbox } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
    }>();

    const modalVisible: Ref<boolean> = ref(false);
    const modal = (): Ref<boolean> => modalVisible;
    const open = () => modal().value = true;
    const close = () => modal().value = false;

    //TODO: restructure conditional rendering up there and thus these two values?
    const blankCheckbox: boolean = false;
    const selectedCheckbox: boolean = true;

    const readonlyCheckbox = (event: Event): void => {
        if (event) {
            event.preventDefault();
        }
        console.log("Checkbox value is readonly.")
    }

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
