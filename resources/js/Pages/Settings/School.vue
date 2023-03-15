<script setup lang="ts">
import {reactive, ref} from 'vue'
    import { useStore } from '../../store'
    import {SvwsUiSecondaryMenu, SvwsUiSidebarMenuItem, SvwsUiTextInput, SvwsUiButton} from '@svws-nrw/svws-ui'
    import AppLayout from '../../Layouts/AppLayout.vue'
    import axios, {AxiosResponse} from 'axios'

    import {Inertia} from "@inertiajs/inertia";
    const navigate = (routeName: string): void => Inertia.get(route(routeName))

    let props = defineProps({
        settings: Object,
        auth: Object,
    })

    const store = useStore();
    let settings = reactive({})

    axios.get(route('api.settings.index', 'school'))
        .then((response: AxiosResponse): void => populateValues(response.data))

const populateValues = (data: { key:string, value:string|null }[]): void =>
    data.forEach((item): string => settings[item.key] = item.value)

    const saveSettings = () => axios
        .put(route('api.settings.update', {type: 'school', settings: settings}))
</script>

<template>
    <AppLayout title="Einstellungen">
        <template #main>
                <header>
                    <div id="headline">
                        <h2 class="text-headline">Einstellungen - Schule</h2>
                    </div>
                </header>
            <div class="content">

                {{ settings.school_address }}
                <div>
                    <h3 class="headline-3">Schule</h3>
                    <SvwsUiTextInput v-model="settings.school_name" placeholder="Name der Schule"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.school_address" placeholder="Adresse der Schule"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.school_email" placeholder="E-Mail Adresse der Schule"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Schulleitung</h3>
                    <SvwsUiTextInput v-model="settings.school_management_name" placeholder="Name Schulleitung"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.school_management_telephone" placeholder="Sekretariat"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Schulträger</h3>
                    <SvwsUiTextInput v-model="settings.school_board_name" placeholder="Name des Schulträgers"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.school_board_address" placeholder="Anschrift des Schulträgers"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.school_board_contact" placeholder="Kontaktdaten des Schulträgers"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Datenschutz</h3>
                    <SvwsUiTextInput v-model="settings.school_gdpr_email" placeholder="[Email des Datenschutzbeauftragten]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.school_gdpr_address" placeholder="[Anschrift des Datenschutzbeauftragten]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.hosting_provider_name" placeholder="[Name des Hosters]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.hosting_provider_address" placeholder="[Anschrift des Hosters]"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Mahnungeneingabe</h3>
                    <SvwsUiTextInput v-model="settings.warning_entry_until" type="date" placeholder="Mahnungeingabe möglich bis"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.note_entry_until" type="date" placeholder="Noteneingabe möglich bis"></SvwsUiTextInput>
                </div>

                <SvwsUiButton @click="saveSettings" class="button">Speichern</SvwsUiButton>
            </div>
        </template>
        <template #secondaryMenu>
<!--            TODO: Refactor to component -->
            <SvwsUiSecondaryMenu>
                <template #headline>
                    Schulverwaltung
                </template>
                <template #content>
                    <SvwsUiSidebarMenuItem @click="navigate('settings.school')" :active="route().current('settings.school')">
                        <template #label>Schule bearbeiten</template>
                    </SvwsUiSidebarMenuItem>
                </template>
            </SvwsUiSecondaryMenu>
        </template>
    </AppLayout>
<!--    <div>-->
<!--        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">-->
<!--            <template #sidebar>-->
<!--&lt;!&ndash;                <Menubar :auth="props.auth" />&ndash;&gt;-->
<!--            </template>-->
<!--            <template #secondaryMenu>-->
<!--                <SvwsUiSecondaryMenu>-->
<!--                    <template #headline>-->
<!--                        Schulverwaltung-->
<!--                    </template>-->

<!--                    <template #content>-->
<!--                        <SvwsUiSidebarMenuItem @click="navigate('settings.school')" :active="route().current('settings.school')" >-->
<!--                            <template #label>Schule bearbeiten</template>-->
<!--                        </SvwsUiSidebarMenuItem>-->
<!--                    </template>-->
<!--                </SvwsUiSecondaryMenu>-->
<!--            </template>-->

<!--            <template #main>-->
<!--                <div class="ui-relative ui-flex ui-flex-col ui-w-full ui-h-screen">-->
<!--&lt;!&ndash;                    <TopMenu headline="Einstellungen - Schule"></TopMenu>&ndash;&gt;-->
<!--                    <div class="ui-px-6 ui-flex ui-flex-col ui-gap-12 ui-max-w-lg">-->
<!--                        <div class="ui-flex ui-flex-col ui-gap-6 ui-justify-start">-->
<!--                            <h3 class="headline-3">Schule</h3>-->
<!--                            <SvwsUiTextInput v-model="settings.school_name" placeholder="Name der Schule"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.school_address" placeholder="Adresse der Schule"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.school_email" placeholder="E-Mail Adresse der Schule"></SvwsUiTextInput>-->
<!--                        </div>-->

<!--                        <div class="ui-flex ui-flex-col ui-gap-6 ui-justify-start">-->
<!--                            <h3 class="headline-3">Schulleitung</h3>-->
<!--                            <SvwsUiTextInput v-model="settings.school_management_name" placeholder="Name Schulleitung"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.school_management_telephone" placeholder="Sekretariat"></SvwsUiTextInput>-->
<!--                        </div>-->

<!--                        <div class="ui-flex ui-flex-col ui-gap-6 ui-items-start">-->
<!--                            <h3 class="headline-3">Schulträger</h3>-->
<!--                            <SvwsUiTextInput v-model="settings.school_board_name" placeholder="Name des Schulträgers"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.school_board_address" placeholder="Anschrift des Schulträgers"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.school_board_contact" placeholder="Kontaktdaten des Schulträgers"></SvwsUiTextInput>-->
<!--                        </div>-->
<!--                        <div class="ui-flex ui-flex-col ui-gap-6 ui-justify-start">-->
<!--                            <h3 class="headline-3">Datenschutz</h3>-->
<!--                            <SvwsUiTextInput v-model="settings.school_gdpr_email" placeholder="[Email des Datenschutzbeauftragten]"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.school_gdpr_address" placeholder="[Anschrift des Datenschutzbeauftragten]"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.hosting_provider_name" placeholder="[Name des Hosters]"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.hosting_provider_address" placeholder="[Anschrift des Hosters]"></SvwsUiTextInput>-->
<!--                        </div>-->
<!--                        <div class="ui-flex ui-flex-col ui-gap-6 ui-justify-start">-->
<!--                            <h3 class="headline-3">Datenschutz</h3>-->
<!--                            <SvwsUiTextInput v-model="settings.warning_entry_until" type="date" placeholder="Mahnungeingabe möglich bis"></SvwsUiTextInput>-->
<!--                            <SvwsUiTextInput v-model="settings.note_entry_until" type="date" placeholder="Noteneingabe möglich bis"></SvwsUiTextInput>-->
<!--                        </div>-->

<!--                        <SvwsUiButton @click="saveSettings" class="ui-self-start">Speichern</SvwsUiButton>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </template>-->

<!--            <template #contentSidebar>-->
<!--            </template>-->
<!--        </SvwsUiAppLayout>-->
<!--    </div>-->
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

    .content > div {
        @apply ui-flex ui-flex-col ui-gap-5 ui-justify-start
    }

    .button {
        @apply ui-self-start
    }
</style>