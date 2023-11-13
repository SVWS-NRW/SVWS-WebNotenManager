<script setup lang="ts">
    import { Ref, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiModal } from '@svws-nrw/svws-ui'

    let props = defineProps({
        auth: Object,
    })

    const modal = ref<any>(null)
    const _showModal: Ref<boolean> = ref(false)

    const showModal = () => _showModal

    const openModal = () => _showModal.value = true

    const closeModal = () => _showModal.value = false

    //TODO: api method does not exist yet
    const adjustSettings = 
        //example
        // () => axios
        // .post(route('api.settings.synchronisation'), {
        //     //etc
        // })
        // .then((): void => apiSuccess())
        // .catch((error: any): void => apiError(
        //     error,
        //     'Ein Problem ist aufgetreten bei Speichern'
        // ))
        () => alert("Call to adjustSettings")
</script>

<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">Einstellungen - Synchronisationseinstellungen für den SVWS-Server</h2>
                </div>
            </header>
            <div class="content">
                Klicken Sie auf den Button, um einen neuen Access Token für den SVWS-Server zu generieren.
                <SvwsUiButton @click="openModal()" type="secondary">
                    Generieren
                </SvwsUiButton>
            </div>
            <SvwsUiModal ref="modal" :show="showModal">
                <template #modalTitle>
                    Wahrnung
                </template>
                <template #modalContent>
                    <p>Es müssen die Einstellungen im zugehörigen SVWS-Server angepasste werden</p>
                </template>

                <template #modalActions>
                    <SvwsUiButton @click="closeModal()" type="secondary">Abrechen</SvwsUiButton>
                    <SvwsUiButton @click="adjustSettings()" type="secondary">OK</SvwsUiButton>
                </template>
            </SvwsUiModal>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>

<style scoped>
    header {
        @apply ui-flex ui-flex-col ui-gap-4 ui-p-6
    }

    header #headline {
        @apply ui-flex ui-items-center ui-justify-start ui-gap-6
    }

    .content {
        @apply ui-px-6 ui-flex ui-flex-col ui-gap-12 ui-max-w-lg
    }

    .content>div {
        @apply ui-flex ui-flex-col ui-gap-5 ui-justify-start
    }

    .button {
        @apply ui-self-start
    }
</style>
