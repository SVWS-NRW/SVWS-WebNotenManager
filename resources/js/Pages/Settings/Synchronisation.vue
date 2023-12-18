<script setup lang="ts">
    import { Ref, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import { apiError, apiSuccess } from '@/Helpers/api.helper'
    import { SvwsUiButton, SvwsUiModal, SvwsUiTextInput } from '@svws-nrw/svws-ui'
    import { usePage } from '@inertiajs/inertia-vue3'

    let props = defineProps({
        auth: Object,
    })

    interface ClientRecord {
        id: string
        name: string
        secret: string
    }

    const clientRecords: Ref<ClientRecord[]> = ref({} as ClientRecord[])
    const newClientRecord: Ref<ClientRecord> = ref({} as ClientRecord)
    const adjustSettingsInfo: Ref<string> = ref("Es müssen die Einstellungen im zugehörigen SVWS-Server angepasst werden")
    const modalTitle: Ref<string> = ref("Warnung")
    const newClientName: string = usePage().props.value.schoolName as string
    const buttonText: Ref<string> = ref("Generieren")
    const clientExists: Ref<boolean> = ref(false)

    const modal = ref<any>(null)
    const _showModal: Ref<boolean> = ref(false)

    const showModal = () => _showModal

    const openModal = () => _showModal.value = true

    const closeModal = () => _showModal.value = false

    axios.get(route('passport.index'))
        .then((response: AxiosResponse): void => {
            clientRecords.value = response.data
            for (let record in clientRecords.value) {
                if (clientRecords.value[record].name == newClientName + "_client") {
                    buttonText.value = "Client bereits vorhanden"
                    clientExists.value = true
                }
            }
        })

    const adjustSettings = () => axios
        .post(route('passport.store'), { 'name': newClientName + "_client" })
        .then((response: AxiosResponse) => {
            newClientRecord.value = response.data
            modalTitle.value = "Neuer Client erfolgreich angelegt"
            buttonText.value = "Client bereits  vorhanden"
            clientExists.value = true
        })
        .catch((error: any): void => apiError(error))
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
                <SvwsUiButton @click="openModal()" :disabled="clientExists" type="secondary">
                    {{ buttonText }}
                </SvwsUiButton>
            </div>
            <SvwsUiModal ref="modal" :show="showModal" size="medium">
                <template #modalTitle>
                    {{ modalTitle }}
                </template>
                <template #modalContent>
                    <p v-if="!clientExists">{{ adjustSettingsInfo }}</p>
                    <div v-else>
                        <p><span class="client-data-fields">Client ID:</span> {{ newClientRecord.id }} </p>
                        <p><span class="client-data-fields">Client Name:</span> {{ newClientRecord.name }} </p>
                        <p><span class="client-data-fields">Client Secret:</span> {{ newClientRecord.secret }} </p>
                    </div>
                </template>
                <template #modalActions>
                    <SvwsUiButton v-if="!clientExists" @click="adjustSettings()" type="secondary">Neuer Token</SvwsUiButton>
                    <SvwsUiButton @click="closeModal()" type="secondary">Abrechen</SvwsUiButton>
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

    .client-data-fields{
        @apply ui-font-bold
    }

    .button {
        @apply ui-self-start
    }
</style>
