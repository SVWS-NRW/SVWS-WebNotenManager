<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">Einstellungen - Synchronisationseinstellungen für den SVWS-Server</h2>
                </div>
            </header>
            <br />
            <div class="content">
                <p v-if="clientExists">Letzte Tokengenerierung: {{ convertedClientRecordTimestamp }}</p>
                <p>Klicken Sie auf den Button, um einen neuen Access Token für den SVWS-Server zu generieren.</p>
                <SvwsUiButton @click="openModal()" type="secondary">
                    Generieren
                </SvwsUiButton>
            </div>
            <SvwsUiModal id="clientModal" ref="modal" :show="showModal" size="medium">
                <template #modalTitle>
                    {{ modalTitle }}
                </template>
                <template #modalContent>
                    <div ref="newClientDataInfo" class="client-data-block" v-if="newClientCreated">
                        <p>Diese Information wird Ihnen einmalig in diesem Fenster eingeblendet.</p>
                        <br />
                        <p><span class="client-data-fields">Client ID:</span> {{ clientRecord.id }} </p>
                        <p><span class="client-data-fields">Client Name:</span> {{ clientRecord.name }} </p>
                        <p><span class="client-data-fields">Client Secret:</span> {{ clientRecord.secret }} </p>
                        <br />
                    </div>
                    <p v-else>{{ adjustSettingsInfo }}</p>
                </template>
                <template #modalActions>
                    <div class="buttons-block">
                        <SvwsUiButton v-if="!newClientCreated" @click="adjustSettings()" type="secondary">Neuer Token
                        </SvwsUiButton>
                        <SvwsUiButton v-if="newClientCreated" @click="copyToClipboard(newClientDataInfo)" type="secondary">Kopieren</SvwsUiButton>
                        <SvwsUiButton @click="closeModal()" type="secondary">Schließen</SvwsUiButton>
                    </div>
                </template>
            </SvwsUiModal>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import { Ref, ref, watch } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosPromise, AxiosResponse } from 'axios';
    import SettingsMenu from '@/Components/SettingsMenu.vue';
    import { apiError, apiSuccess } from '@/Helpers/api.helper';
    import { SvwsUiButton, SvwsUiModal, SvwsUiTextInput } from '@svws-nrw/svws-ui';
    import { usePage } from '@inertiajs/inertia-vue3';

    let props = defineProps({
        auth: Object,
    });

    interface ClientRecord {
        id: number,
        name: string,
        secret: string,
        created_at: Date,
    }

    const newClientName: string = usePage().props.value.schoolName as string;
    const clientRecord: Ref<ClientRecord> = ref({} as ClientRecord);
    const clientExists: Ref<boolean> = ref(false);
    const convertedClientRecordTimestamp: Ref<string> = ref("");
    const newClientCreated: Ref<boolean> = ref(false);
    const newClientDataInfo: Ref<HTMLDivElement>= ref({} as HTMLDivElement);
    const modal = ref<any>(null);
    const modalTitle: Ref<string> = ref("Warnung");
    const adjustSettingsInfo: Ref<string> = ref("Es müssen die Einstellungen im zugehörigen SVWS-Server angepasst werden.");
    const _showModal: Ref<boolean> = ref(false);

    const showModal = (): Ref<boolean> => _showModal;

    const openModal = (): boolean => _showModal.value = true;

    const closeModal = (): boolean => _showModal.value = false;

    const copyToClipboard = (receivedClientDataInfo: HTMLDivElement) => {
        navigator.clipboard.writeText(receivedClientDataInfo.innerText);
    };

    axios.get(route('passport.index'))
        .then((response: AxiosResponse): void => {
            for (let record in response.data) {
                if (response.data[record].name == newClientName + "_client") {
                    clientRecord.value = response.data[record];
                    convertedClientRecordTimestamp.value =
                        new Date(clientRecord.value.created_at).toLocaleString('de-DE');
                    clientExists.value = true;
                }
            }
        });

    const adjustSettings = (): void => {
        if (clientExists.value) {
            axios.delete(route('passport.destroy', clientRecord.value.id))
                .then((response: AxiosResponse) => response)
                .catch((error: any): void => console.log("error: " + apiError(error)));
        }
        axios.post(route('passport.store'), { 'name': newClientName + "_client" })
            .then((response: AxiosResponse) => {
                clientRecord.value = response.data;
                convertedClientRecordTimestamp.value =
                    new Date(clientRecord.value.created_at).toLocaleString('de-DE');
                clientExists.value = true;
                newClientCreated.value = true;
                modalTitle.value = "Neuer Client erfolgreich angelegt";
            })
            .catch((error: any): void => apiError(error));
    }

    watch(() => _showModal.value, () => {
        if (_showModal.value == false) {

            newClientCreated.value = false;
            modalTitle.value = "Warnung";
        }
    });
</script>

<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

    header #headline {
        @apply flex items-center justify-start gap-6
    }

    .content {
        @apply px-6 flex flex-col gap-12
    }

    .content>div {
        @apply flex flex-col gap-5 justify-start
    }

    .client-data-fields {
        @apply font-bold
    }

    .client-data-block {
        @apply text-left pl-2
    }
    
    .buttons-block {
        @apply flex justify-end gap-2 -mr-[55%]
    }

    .button {
        @apply self-start
    }
</style>
