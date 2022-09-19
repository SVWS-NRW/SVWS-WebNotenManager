<script setup lang="ts">
    import {reactive, ref} from 'vue'
    import axios, {AxiosResponse} from 'axios'
    import moment from 'moment'

    const emit = defineEmits(['updated'])
    const props = defineProps(['leistung'])
    const modal = ref(true)

    type config = { istGemahnt: boolean, mahndatum: string|null }

    let mahndatumFormatted = (): string|null => {
        if (!props.leistung.mahndatum) return null
        return moment(new Date(props.leistung.mahndatum)).format('YYYY-MM-DD')
    }

    let state = reactive({
        istGemahnt: Boolean(props.leistung.istGemahnt),
        mahndatum: mahndatumFormatted(),
    });

    const setMahnung = (): void => {
        if (!state.istGemahnt) state.mahndatum = null
        let url: string = route('set_mahnung', props.leistung)
        let config: config = state

<<<<<<< HEAD
        axios.post(url, config)
            .then((): void => emit('updated', props.leistung, state.istGemahnt, state.mahndatum))
=======
        axios.post(url, config).then((): void => emit('updated', props.leistung, state.istGemahnt, state.mahndatum))
>>>>>>> develop
    }
</script>

<template>
<<<<<<< HEAD
    <button @click="modal.openModal()">
        <SvwsUiIcon v-if="state.istGemahnt">
            <span>Ist gemahnt</span>
            <i-ri-checkbox-line aria-hidden="true"></i-ri-checkbox-line>
        </SvwsUiIcon>

        <SvwsUiIcon v-else>
            <span>Ist nicht gemahnt</span>
            <i-ri-checkbox-blank-line aria-hidden="true"></i-ri-checkbox-blank-line>
        </SvwsUiIcon>
    </button>
=======
    <div class="text-center" :class="{ red: state.istGemahnt, green: state.mahndatum }">
        <button @click="modal.openModal()">
            <SvwsUiIcon v-if="state.istGemahnt">
                <i-ri-checkbox-line aria-hidden="true" aria-description="Ist gemahnt"></i-ri-checkbox-line>
            </SvwsUiIcon>

            <SvwsUiIcon v-else>
                <i-ri-checkbox-blank-line aria-hidden="true" aria-description="Ist nicht gemahnt gemahnt"></i-ri-checkbox-blank-line>
            </SvwsUiIcon>
        </button>
    </div>
>>>>>>> develop

    <SvwsUiModal ref="modal">
        <template #modalTitle>Mahnung</template>
        <template #modalDescription>
            {{ props.leistung.vorname }} {{ props.leistung.nachname }}
            <SvwsUiBadge variant="highlight" size="normal" class="px-6">
                {{ props.leistung.klasse ?? props.leistung.kurs }}
            </SvwsUiBadge>
        </template>

        <template #modalContent>
            <div class="flex flex-col gap-6">
                <SvwsUiCheckbox v-model="state.istGemahnt">Ist gemahnt</SvwsUiCheckbox>
                <SvwsUiTextInput v-model="state.mahndatum" type="date" placeholder="Mahndatum" :disabled="!state.istGemahnt"></SvwsUiTextInput>
            </div>
        </template>

        <template #modalActions>
            <SvwsUiButton @click="setMahnung()" type="primary">Speichern</SvwsUiButton>
            <SvwsUiButton @click="modal.closeModal()" type="secondary">Schließen</SvwsUiButton>
        </template>
    </SvwsUiModal>
</template>

<style scoped>
    .icon > svg {
        @apply w-7 h-7
    }

<<<<<<< HEAD
    .svws-ui--icon > svg {
        @apply w-7 h-7
=======
    .red {
        @apply bg-red-500 text-white
    }

    .green {
        @apply bg-green-500 text-white
>>>>>>> develop
    }
</style>
