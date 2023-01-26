<script setup lang="ts">
    import { computed, reactive, ref } from 'vue'
    import axios from 'axios'
    import moment from 'moment'
    import { usePage } from '@inertiajs/inertia-vue3'
    import { Leistung } from '../Interfaces/Leistung'

    import {
        SvwsUiBadge,
        SvwsUiButton,
        SvwsUiCheckbox,
        SvwsUiIcon,
        SvwsUiModal,
    } from '@svws-nrw/svws-ui'

    const modal = ref<any>(null)

    let props = defineProps<{
        leistung: Leistung
        disabled: boolean,
    }>()

    let leistung = reactive<Leistung>(props.leistung)

    const updateIstGemahnt = (value: boolean): void => {
        leistung.istGemahnt = value
        axios.post(route('set_mahnung', leistung.id), leistung)
    }

    const istGemahnt = computed((): boolean => Boolean(leistung.istGemahnt))
    const mahndatumFormatted = (): string => moment(new Date(leistung.mahndatum)).format('DD.MM.YYYY')
    const isDisabled = (): boolean => !!usePage().props.value.warning_entry_disabled || props.disabled
</script>

<template>
    <div :class="{ red: leistung.istGemahnt, green: leistung.mahndatum }">
        <button @click="modal.openModal()" v-if="leistung.mahndatum" class="modal-button">
            <SvwsUiIcon>
                <mdi-checkbox-marked-outline aria-hidden="true" aria-description="Ist gemahnt mit Mahndatum"></mdi-checkbox-marked-outline>
            </SvwsUiIcon>
        </button>
        <div v-else>
            <span v-if="isDisabled()">
                <mdi-checkbox-marked-outline v-if="istGemahnt" aria-hidden="true" aria-description="Ist gemahnt"></mdi-checkbox-marked-outline>
            </span>
            <SvwsUiCheckbox v-else :modelValue="istGemahnt" :value="true" @update:modelValue="updateIstGemahnt($event)"></SvwsUiCheckbox>
        </div>
    </div>

    <SvwsUiModal ref="modal">
        <template #modalTitle>
            {{ leistung.vorname }} {{ leistung.nachname }}
            <SvwsUiBadge variant="primary">
                {{ leistung.klasse ?? leistung.kurs }}
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
    .red {
        @apply ui-text-red-500
    }

    .green {
        @apply ui-text-green-500
    }

    button.modal-button {
        @apply ui-flex ui-items-center ui-justify-center
    }
</style>
