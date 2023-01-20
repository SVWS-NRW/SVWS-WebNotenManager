<script setup lang="ts">
    import { reactive, ref, watch } from 'vue'
    import axios, { AxiosPromise } from 'axios'
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

    const mahndatumFormatted = (): string => moment(new Date(leistung.mahndatum)).format('DD.MM.YYYY')

    watch((): boolean => leistung.istGemahnt, (): AxiosPromise =>
        axios.post(route('set_mahnung', leistung.id), leistung)
    )

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
                <mdi-checkbox-marked-outline v-if="leistung.istGemahnt" aria-hidden="true" aria-description="Ist gemahnt"></mdi-checkbox-marked-outline>
            </span>
            <SvwsUiCheckbox v-else v-model="leistung.istGemahnt" :value="true"></SvwsUiCheckbox>
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
        @apply text-red-500
    }

    .green {
        @apply text-green-500
    }

    button.modal-button {
        @apply flex items-center justify-center
    }
</style>
