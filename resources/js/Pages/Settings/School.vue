<script setup lang="ts">
    import { ref } from 'vue'
    import { useStore } from '../../store'
    // import Menubar from '../../Components/Menubar.vue'
    // import TopMenu from "../../Components/TopMenu.vue"
    import axios, {AxiosResponse} from 'axios'

    import {Inertia} from "@inertiajs/inertia";
    const navigate = (routeName: string): void => Inertia.get(route(routeName))

    let props = defineProps({
        settings: Object,
        auth: Object,
    })
    const store = useStore();
    const settings = ref([])

    axios.get(route('get_settings', 'school'))
        .then((response: AxiosResponse): void => settings.value = response.data)

    const saveSettings = () => axios
        .post(route('set_settings', {type: 'school', settings: settings.value}))
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
<!--                <Menubar :auth="props.auth" />-->
            </template>
            <template #secondaryMenu>
                <SvwsUiSecondaryMenu>
                    <template #headline>
                        Schulverwaltung
                    </template>

                    <template #content>
                        <SvwsUiSidebarMenuItem @click="navigate('settings.school')" :active="route().current('settings.school')" >
                            <template #label>Schule bearbeiten</template>
                        </SvwsUiSidebarMenuItem>
                    </template>
                </SvwsUiSecondaryMenu>
            </template>

            <template #main>
                <div class="ui-relative ui-flex ui-flex-col ui-w-full ui-h-screen">
<!--                    <TopMenu headline="Einstellungen - Schule"></TopMenu>-->
                    <div class="ui-px-6 ui-flex ui-flex-col ui-gap-12 ui-max-w-lg">
                        <div class="ui-flex ui-flex-col ui-gap-6 ui-justify-start">
                            <h3 class="headline-3">Schule</h3>
                            <SvwsUiTextInput v-model="settings.school_name" placeholder="Name der Schule"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.school_address" placeholder="Adresse der Schule"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.school_email" placeholder="E-Mail Adresse der Schule"></SvwsUiTextInput>
                        </div>

                        <div class="ui-flex ui-flex-col ui-gap-6 ui-justify-start">
                            <h3 class="headline-3">Schulleitung</h3>
                            <SvwsUiTextInput v-model="settings.school_management_name" placeholder="Name Schulleitung"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.school_management_telephone" placeholder="Sekretariat"></SvwsUiTextInput>
                        </div>

                        <div class="ui-flex ui-flex-col ui-gap-6 ui-items-start">
                            <h3 class="headline-3">Schulträger</h3>
                            <SvwsUiTextInput v-model="settings.school_board_name" placeholder="Name des Schulträgers"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.school_board_address" placeholder="Anschrift des Schulträgers"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.school_board_contact" placeholder="Kontaktdaten des Schulträgers"></SvwsUiTextInput>
                        </div>
                        <div class="ui-flex ui-flex-col ui-gap-6 ui-justify-start">
                            <h3 class="headline-3">Datenschutz</h3>
                            <SvwsUiTextInput v-model="settings.school_gdpr_email" placeholder="[Email des Datenschutzbeauftragten]"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.school_gdpr_address" placeholder="[Anschrift des Datenschutzbeauftragten]"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.hosting_provider_name" placeholder="[Name des Hosters]"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.hosting_provider_address" placeholder="[Anschrift des Hosters]"></SvwsUiTextInput>
                        </div>
                        <div class="ui-flex ui-flex-col ui-gap-6 ui-justify-start">
                            <h3 class="headline-3">Datenschutz</h3>
                            <SvwsUiTextInput v-model="settings.warning_entry_until" type="date" placeholder="Mahnungeingabe möglich bis"></SvwsUiTextInput>
                            <SvwsUiTextInput v-model="settings.note_entry_until" type="date" placeholder="Noteneingabe möglich bis"></SvwsUiTextInput>
                        </div>

                        <SvwsUiButton @click="saveSettings" class="ui-self-start">Speichern</SvwsUiButton>
                    </div>
                </div>
            </template>

            <template #contentSidebar>
            </template>
        </SvwsUiAppLayout>
    </div>
</template>

<style scoped>

</style>