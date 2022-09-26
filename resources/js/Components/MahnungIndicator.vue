<script setup lang="ts">
    import {reactive, ref} from 'vue'
    import axios from 'axios'
    import moment from 'moment'

    const emit = defineEmits(['updated'])
    const props = defineProps(['leistung'])
    const modal = ref(true)

    let mahndatumFormatted = (): string|null => {
        if (!props.leistung.mahndatum) return null
        return moment(new Date(props.leistung.mahndatum)).format('DD.MM.YYYY')
    }

    let state = reactive({
        istGemahnt: Boolean(props.leistung.istGemahnt),
        mahndatum: mahndatumFormatted(),
    });

    const setMahnung = () => axios
        .post(route('set_mahnung', props.leistung), state)
        .then((): void => emit('updated', props.leistung, state.istGemahnt))
</script>

<template>
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
                <span v-if="state.mahndatum"><strong>Mahndatum:</strong> {{ state.mahndatum }}</span>
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


    .red {
        @apply bg-red-500 text-white
    }

    .green {
        @apply bg-green-500 text-white

    }
</style>
