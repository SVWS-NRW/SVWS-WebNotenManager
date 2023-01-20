<script setup lang="ts">
    import { ref } from 'vue'
    import { Leistung } from '../Interfaces/Leistung'

    import {
        SvwsUiButton,
        SvwsUiIcon,
        SvwsUiModal,
    } from '@svws-nrw/svws-ui'

    const modal = ref<any>(null)

    let props = defineProps<{
        leistung: Leistung
    }>()
</script>

<template>
    <div class="flex items-center justify-center">
        <button @click="modal.openModal()" class="modal-button" v-if="props.leistung.fachbezogeneBemerkungen">
            <SvwsUiIcon>
                <span class="sr-only">Bemerkung vorhanden</span>
                <mdi-checkbox-marked-outline aria-hidden="true"></mdi-checkbox-marked-outline>
            </SvwsUiIcon>
        </button>

        <SvwsUiIcon v-else>
            <span class="sr-only">Keine Bemerkung vorhanden</span>
            <mdi-checkbox-blank-outline aria-hidden="true"></mdi-checkbox-blank-outline>
        </SvwsUiIcon>
    </div>

    <SvwsUiModal ref="modal">
        <template #modalTitle>
            {{ props.leistung.vorname }} {{ props.leistung.nachname }}
        </template>

        <template #modalContent>
            {{ props.leistung.fachbezogeneBemerkungen }}
        </template>

        <template #modalActions>
            <SvwsUiButton @click="modal.closeModal()" type="secondary">Schließen</SvwsUiButton>
        </template>
    </SvwsUiModal>
</template>

