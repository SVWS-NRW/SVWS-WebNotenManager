<script setup lang="ts">
    import { reactive, ref } from 'vue'
    import axios from 'axios'
    import moment from 'moment'
    import { usePage } from '@inertiajs/inertia-vue3'

    const emit = defineEmits(['updated'])
    const props = defineProps(['leistung'])
    const modal = ref(true)

    let state = reactive({
        istGemahnt: Boolean(props.leistung.istGemahnt),
        mahndatum: props.leistung.mahndatum,
    });

    const mahndatumFormatted = (): string => moment(new Date(state.mahndatum)).format('DD.MM.YYYY')

    const setMahnung = (): Promise<void> => axios
        .post(route('set_mahnung', props.leistung.id), state)
        .then((): void => emit('updated', props.leistung, state.istGemahnt))
</script>

<template>
    <div class="text-center" :class="{ red: state.istGemahnt, green: state.mahndatum }">
        <button @click="modal.openModal()" v-if="state.mahndatum">
            <SvwsUiIcon>
                <i-ri-mail-line aria-hidden="true" aria-description="Ist gemahnt mit Mahndatum"></i-ri-mail-line>
            </SvwsUiIcon>
        </button>
        <div v-else>
            <SvwsUiIcon v-if="usePage().props.value.warning_entry_disabled">
                <i-ri-check-line v-if="state.istGemahnt" aria-hidden="true" aria-description="Ist gemahnt"></i-ri-check-line>
            </SvwsUiIcon>
            <SvwsUiCheckbox v-else v-model="state.istGemahnt" @update:modelValue="setMahnung"></SvwsUiCheckbox>
        </div>
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
            <strong>Mahndatum:</strong> {{ mahndatumFormatted() }}
        </template>

        <template #modalActions>
            <SvwsUiButton @click="modal.closeModal()" type="secondary">Schließen</SvwsUiButton>
        </template>
    </SvwsUiModal>
</template>

<style scoped>
    .icon > svg {
        @apply w-6 h-6
    }

    .red {
        @apply bg-red-500 text-white
    }

    .green {
        @apply bg-green-500 text-white
    }
</style>
