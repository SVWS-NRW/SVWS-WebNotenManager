<script setup lang="ts">
    import { computed, reactive, ref, Ref } from 'vue'
    import axios from 'axios'
    import moment from 'moment'
    import { usePage } from '@inertiajs/inertia-vue3'
    import { Leistung } from '../Interfaces/Leistung'
    import { CellRef, setCellRefs, navigateTable } from '../Helpers/tableNavigationHelper'

    import { SvwsUiBadge, SvwsUiButton,
        SvwsUiModal } from '@svws-nrw/svws-ui'

    const modal = ref<any>(null)

    let props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
    }>()

    let element: CellRef = undefined
    let leistung = reactive<Leistung>(props.leistung)

    const updateIstGemahnt = (): void => {
        leistung.istGemahnt = !leistung.istGemahnt
        axios.post(route('api.mahnung', leistung.id), leistung)
        .catch((): boolean => leistung.istGemahnt = !leistung.istGemahnt)
    }

    const istGemahnt = computed((): boolean => Boolean(leistung.istGemahnt))
    const mahndatumFormatted = (): string => moment(new Date(leistung.mahndatum)).format('DD.MM.YYYY')
    const isDisabled = (): boolean => !!usePage().props.value.warning_entry_disabled || props.disabled
    const _showModal: Ref<boolean> = ref(false)
    const showModal = () => _showModal
    const openModal = () => _showModal.value = true
    const closeModal = () => _showModal.value = false


    const navigate = (direction: string): Promise<void> => navigateTable(direction, props.rowIndex, element)
</script>

<template>
    <span v-if="isDisabled()">
        <span v-if="leistung.mahndatum" aria-description="Ist gemahnt mit Mahndatum">
            <span class="icon green" aria-hidden="true">
                <mdi-checkbox-marked-outline ></mdi-checkbox-marked-outline>
            </span>
        </span>
        <span v-else-if="istGemahnt" aria-description="Ist gemahnt ohne Mahndatum">
            <span class="icon red" aria-hidden="true">
                <mdi-checkbox-marked-outline></mdi-checkbox-marked-outline>
            </span>
        </span>
        <span v-else aria-description="Ist nicht gemahnt">
            <span class="icon" aria-hidden="true">
                <mdi-checkbox-blank-outline></mdi-checkbox-blank-outline>
            </span>
        </span>
    </span>

    <button
        v-else
        @click="leistung.mahndatum ? openModal() : updateIstGemahnt()"
        @keydown.up.stop.prevent="navigate('up')"
        @keydown.down.stop.prevent="navigate('down')"
        @keydown.enter.stop.prevent="navigate('down')"
        @keydown.left.stop.prevent="navigate('left')"
        @keydown.right.stop.prevent="navigate('right')"
        @keydown.tab.stop.prevent="navigate('right')"
        :ref="(el: CellRef): CellRef => {element = el; setCellRefs(element, props.rowIndex); return el}"
    >
        <span v-if="leistung.mahndatum" aria-description="Ist gemahnt mit Mahndatum">
           <span class="icon green" aria-hidden="true">
                <mdi-checkbox-marked-outline ></mdi-checkbox-marked-outline>
           </span>
        </span>
        <span v-else-if="istGemahnt" aria-description="Ist gemahnt ohne Mahndatum">
            <span class="icon red" aria-hidden="true">
               <mdi-checkbox-marked-outline></mdi-checkbox-marked-outline>
           </span>
        </span>
        <span v-else aria-description="Ist nicht gemahnt">
           <span class="icon" aria-hidden="true">
               <mdi-checkbox-blank-outline></mdi-checkbox-blank-outline>
           </span>
        </span>
    </button>
    <SvwsUiModal ref="modal" :show="showModal">
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
            <SvwsUiButton @click="closeModal()" type="secondary">Schließen</SvwsUiButton>
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
