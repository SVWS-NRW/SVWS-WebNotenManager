<script setup lang="ts">
    import { ref, Ref } from 'vue'
    import AppLayout from '../../Layouts/AppLayout.vue'
    import axios, { AxiosResponse } from 'axios'
    import SettingsMenu from '../../Components/SettingsMenu.vue'

    import {
        SvwsUiButton,
        SvwsUiCheckbox,
        SvwsUiTooltip,
        SvwsUiRadioOption,
        SvwsUiDataTable,
        SvwsUiDataTableRow,
        SvwsUiDataTableCell,
        SvwsUiIcon
    } from '@svws-nrw/svws-ui'

    type CollapseReference = [
        boolean,
        boolean[]
    ]

    let props = defineProps({
        auth: Object,
    })

    let jahrgaenge = ref([])
    let klassen = ref([])

    const collapsed: Ref<{[key: string]: CollapseReference}> = ref({})

    const matrixItems = [
        'editable_teilnoten', 'editable_noten', 'editable_mahnungen', 'editable_fehlstunden',
        'editable_fb', 'editable_asv', 'editable_aue', 'editable_zb',
    ]

    axios.get(route('api.matrix.index')).then((response: AxiosResponse): void => {
        jahrgaenge.value = response.data.jahrgaenge
        klassen.value = response.data.klassen

        setTableCollapseStatus(response.data.jahrgaenge)
        setTableCollapseStatus(response.data.klassen)
    })

    const saveMatrix = (klasse: {id: Number}, item: string, value: string) => axios.put(
        route('api.matrix.update', [klasse.id]),
        {key: item, value: value }
    ).then(res => console.log(res))


    let settings = ref({})

    axios.get(route('api.settings.index', 'matrix'))
        .then((response: AxiosResponse): void => settings.value = response.data)

    const saveSettings = (value: boolean, column: string) => axios
        .put(route('api.settings.update', {group: 'matrix'}), {value: value, column: column})


    // Creates the collapsed boolean table to switch the table toggles
    const setTableCollapseStatus = (obj: any): void => Object.keys(obj).forEach((key: string): CollapseReference =>
        collapsed.value[key] = [false, Array(obj[key].length).fill(false)]
    )
</script>

<template>
    <AppLayout>
        <template #main>
            <header>
                <div id="headline">
                    <h2 class="text-headline">Einstellungen - Schreibrechte</h2>
                </div>
            </header>
            <div class="content">
                <section class="flex flex-col w-full gap-4">
                    <SvwsUiCheckbox v-model="settings.lehrer_can_override_fachlehrer" value="true" @update:modelValue="saveSettings($event, 'lehrer_can_override_fachlehrer')">
                        <SvwsUiTooltip>
                            Die Klassenleitung darf alle Leistungsdaten bearbeiten.
                            <template #content>
                                "Die Klassenleitung darf als Vertretung einer Fachlehrkraft auch die Noten, Teilnoten, usw. der Fachlehrkraft editieren. Der Button zum Editieren damit für alle Klassenleitungen sichtbar.
                            </template>
                        </SvwsUiTooltip>
                    </SvwsUiCheckbox>

                    <SvwsUiDataTable collapsible :noData="false">
                        <template #header>
                            <SvwsUiDataTableRow thead>
                                <SvwsUiDataTableCell thead>Gruppierung</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead>Teilnoten</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead>Noten</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Mahnung">M</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Fehlstunden">FS</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Fachbezogene Bemerkungen">FB</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Arbeits und Sozialverhalten">ASV</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Außerunterrichtliches Engagement">AUE</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Zeugnisbemerkung">ZB</SvwsUiDataTableCell>
                            </SvwsUiDataTableRow>
                        </template>
                        <template #body>
                            <span v-for="(groupedJahrgaenge, jahrgangKey) in jahrgaenge">
                                <SvwsUiDataTableRow>
                                    <SvwsUiDataTableCell>
                                        <div class="flex items-center gap-1">
                                            <SvwsUiButton type="icon" size="small" @click="collapsed[jahrgangKey][0] = !collapsed[jahrgangKey][0]">
                                                <SvwsUiIcon>
                                                    <mdi-chevron-down v-if="collapsed[jahrgangKey][0]"></mdi-chevron-down>
                                                    <mdi-chevron-up v-else></mdi-chevron-up>
                                                </SvwsUiIcon>
                                            </SvwsUiButton>
                                            {{ jahrgangKey }}
                                        </div>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell v-for="_ in 8"></SvwsUiDataTableCell>
                                </SvwsUiDataTableRow>

                                <span v-for="(jahrgang, index) in groupedJahrgaenge">
                                    <SvwsUiDataTableRow :depth="1" :collapsed="collapsed[jahrgangKey][0]" :expanded="!collapsed[jahrgangKey][0]">
                                        <SvwsUiDataTableCell>
                                            <div class="flex items-center gap-1">
                                                <SvwsUiButton type="icon" size="small" @click="collapsed[jahrgangKey][1][index] = !collapsed[jahrgangKey][1][index]">
                                                    <mdi-chevron-down v-if="collapsed[jahrgangKey][1][index]"></mdi-chevron-down>
                                                    <mdi-chevron-up v-else></mdi-chevron-up>
                                                </SvwsUiButton>
                                                {{ jahrgang.kuerzel }} {{index}}
                                            </div>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell v-for="_ in 8"></SvwsUiDataTableCell>
                                    </SvwsUiDataTableRow>

                                    <SvwsUiDataTableRow :depth="2" :collapsed="collapsed[jahrgangKey][0] || collapsed[jahrgangKey][1][index]" :expanded="!collapsed[jahrgangKey][1][index]" v-for="klasse in jahrgang.klassen">
                                        <SvwsUiDataTableCell>
                                            {{ klasse.kuerzel }}
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiTooltip>
                                                <SvwsUiCheckbox v-model="klasse.editable_teilnoten" @update:modelValue="saveMatrix(klasse, 'editable_teilnoten', $event)"></SvwsUiCheckbox>
                                                <template #content>
                                                    Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                                </template>
                                            </SvwsUiTooltip>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiTooltip>
                                                <SvwsUiCheckbox v-model="klasse.editable_noten" @update:modelValue="saveMatrix(klasse, 'editable_noten', $event)"></SvwsUiCheckbox>
                                                <template #content>
                                                    Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                                </template>
                                            </SvwsUiTooltip>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                           <SvwsUiTooltip>
                                                <SvwsUiCheckbox v-model="klasse.editable_mahnungen" @update:modelValue="saveMatrix(klasse, 'editable_mahnungen', $event)"></SvwsUiCheckbox>
                                                <template #content>
                                                    Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                                </template>
                                            </SvwsUiTooltip>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                             <SvwsUiTooltip>
                                                <SvwsUiCheckbox v-model="klasse.editable_fehlstunden" @update:modelValue="saveMatrix(klasse, 'editable_fehlstunden', $event)"></SvwsUiCheckbox>
                                                <SvwsUiRadioOption v-model="klasse.toggleable_fehlstunden" name="toggleable" :value="true" @input="saveMatrix(klasse, 'toggleable_fehlstunden', true)">FS</SvwsUiRadioOption>
                                                <SvwsUiRadioOption v-model="klasse.toggleable_fehlstunden" name="toggleable" :value="false" @input="saveMatrix(klasse, 'toggleable_fehlstunden', false)">GFS</SvwsUiRadioOption>
                                                <template #content>
                                                    Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                                </template>
                                            </SvwsUiTooltip>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiTooltip>
                                                <SvwsUiCheckbox v-model="klasse.editable_fb" @update:modelValue="saveMatrix(klasse, 'editable_fb', $event)"></SvwsUiCheckbox>
                                                <template #content>
                                                    Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                                </template>
                                            </SvwsUiTooltip>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                             <SvwsUiTooltip>
                                                <SvwsUiCheckbox v-model="klasse.editable_asv" @update:modelValue="saveMatrix(klasse, 'editable_asv', $event)"></SvwsUiCheckbox>
                                                <template #content>
                                                    Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                                </template>
                                            </SvwsUiTooltip>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiTooltip>
                                                <SvwsUiCheckbox v-model="klasse.editable_aue" @update:modelValue="saveMatrix(klasse, 'editable_aue', $event)"></SvwsUiCheckbox>
                                                <template #content>
                                                    Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                                </template>
                                            </SvwsUiTooltip>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiTooltip>
                                                <SvwsUiCheckbox v-model="klasse.editable_zb" @update:modelValue="saveMatrix(klasse, 'editable_zb', $event)"></SvwsUiCheckbox>
                                                <template #content>
                                                    Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                                </template>
                                            </SvwsUiTooltip>
                                        </SvwsUiDataTableCell>
                                    </SvwsUiDataTableRow>
                                </span>
                            </span>
                        </template>
                    </SvwsUiDataTable>
                </section>
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

    table {
        @apply ui-border
    }

    table td, table th {
        @apply ui-border ui-p-4
    }

    .content {
        @apply ui-px-6 ui-flex ui-flex-col ui-gap-12 ui-items-start ui-w-full
    }

    .button {
        @apply ui-self-start
    }
</style>