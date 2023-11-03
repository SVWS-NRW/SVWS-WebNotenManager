<script setup lang="ts">
    import { Ref, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import SynchronisationPopup from '@/Components/SynchronisationPopup.vue'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton } from '@svws-nrw/svws-ui'

    //TODO: do we need this?
    let props = defineProps({
        auth: Object,
    })

    //TODO: needed variables (this is default/dummy so far)
    let settings = ref({})

    //TODO: use this dynamically to open/close popup
    const defaultShowModal: Ref<boolean> = ref(false)

    //const displayConfirmPopup = () => 
    // {hide or show}

    //TODO: call from popup
    //TODO: api method does not exist yet
    const saveSettings = () => axios
        .post(route('api.settings.synchronisation'), {
            'Synchronisation': settings.value
        })
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern'
        ))
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
                <SvwsUiButton @click="displayConfirmPopup()" type="secondary">
                    Generieren
                </SvwsUiButton>
                <!-- dummy -->
                <SynchronisationPopup v-if="defaultShowModal" ref="modal" :show="displayConfirmPopup">
                    <template #modalActions>
                        <SvwsUiButton @click="xxx" type="secondary">Schließen</SvwsUiButton>
                    </template>
                </SynchronisationPopup>
            </div>
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
