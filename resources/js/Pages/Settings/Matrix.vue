<script setup lang="ts">
    import { ref, Ref, onBeforeMount } from 'vue'
    import axios, { AxiosResponse } from 'axios'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import { apiSuccess, apiError } from '@/Helpers/api.helper'

    import {
        SvwsUiButton, SvwsUiCheckbox, SvwsUiTooltip, SvwsUiRadioOption, 
        SvwsUiDataTable, SvwsUiDataTableRow, SvwsUiDataTableCell, SvwsUiIcon,
    } from '@svws-nrw/svws-ui'

    const defaultCollapsed: boolean = false

    const cellTooltip: string = `
        Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in 
        den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
    `
    
    type CollapseReference = { 
        [key: string]: [boolean, boolean[]] 
    }

    interface Klasse {
        id: number
        kuerzel: string
        sortierung: number
        editable_teilnoten: boolean
        editable_noten: boolean
        editable_mahnungen: boolean
        editable_fehlstunden: boolean
        toggleable_fehlstunden: boolean
        editable_fb: boolean
        editable_asv: boolean
        editable_aue: boolean
        editable_zb: boolean
    }

    interface Jahrgang {
        id: number
        kuerzel: string
        stufe: string
        klassen: Klasse[]
    }

    type JahrgangStructure = {
        [key: string]: Jahrgang[]
    }

    type Settings = {
        lehrer_can_override_fachlehrer: boolean,
    }

    type ToggleableKeys = {
        [K in keyof Klasse]: Klasse[K] extends boolean ? K : never;
    }[keyof Klasse];

    const settings: Ref<Settings> = ref({
        lehrer_can_override_fachlehrer: false
    })

    const jahrgaenge: Ref<JahrgangStructure> = ref({})
    const klassen: Ref<Klasse[]> = ref([]) 
    const jahgraengeCollapsed: Ref<CollapseReference> = ref({})
    const klassenCollapsed: Ref<boolean> = ref(defaultCollapsed)

    onBeforeMount((): void => {
        axios.get(route('api.matrix.index'))
            .then((response: AxiosResponse): void => {
                jahrgaenge.value = response.data.jahrgaenge
                klassen.value = response.data.klassen
                setTableCollapseValues(response.data.jahrgaenge)
            })     
            .catch((error: any): void => apiError(error))

        axios.get(route('api.settings.index', 'matrix'))
            .then((response: AxiosResponse): void => settings.value = response.data)       
            .catch((error: any): void => apiError(error))
    })

    // Creates the collapsed boolean table to switch the table toggles
    const setTableCollapseValues = (obj: Record<string, any[]>): void => 
        Object.keys(obj).forEach((key: string) => 
            jahgraengeCollapsed.value[key] = [
                defaultCollapsed, Array(obj[key].length).fill(defaultCollapsed)
            ]
        )

    const saveMatrix = () => {
        const klassenArray = Object.values(jahrgaenge.value)
            .flat()
            .map((item: Stufe): Klasse[] => item.klassen)
            .flat()
            .concat(klassen.value)

        axios.put(route('api.matrix.update'), {klassen: klassenArray})    
            .then((): void => apiSuccess())
            .catch((error: any): void => apiError(error))
    }

    const saveSettings = (): Promise<void> => axios
        .put(route('api.settings.bulk_update', {group: 'matrix'}), {settings: settings.value})
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(error))  
        

    let toggleable: ToggleableKeys[] = [
        'editable_teilnoten',
        'editable_noten',
        'editable_mahnungen',
        'editable_fehlstunden',
        'toggleable_fehlstunden',
        'editable_fb',
        'editable_asv',
        'editable_aue',
        'editable_zb',
    ]   

    const toggleKlasse = (klasse: Klasse) => toggleable.forEach(
        (item: ToggleableKeys): boolean => klasse[item] = !klasse[item]
    )    

    const toggleJahrgang = (jahrgang: Jahrgang) => jahrgang.klassen.forEach(
        (klasse: Klasse): void => toggleKlasse(klasse)
    )

    const toggleGroup = (jahrgaenge: Jahrgang[]) => jahrgaenge.forEach(
        (jahrgang: Jahrgang): void => toggleJahrgang(jahrgang)
    )
   
    const toggleJahrgangColumn = (jahrgang: Jahrgang, column: ToggleableKeys): void => 
        jahrgang.klassen.forEach((klasse: Klasse): boolean => klasse[column] = !klasse[column])

    const toggleGroupColumn = (jahrgaenge: Jahrgang[], column: ToggleableKeys) => 
        jahrgaenge.forEach((jahrgang: Jahrgang): void => toggleJahrgangColumn(jahrgang, column))
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
                    <SvwsUiCheckbox v-model="settings.lehrer_can_override_fachlehrer" value="true">
                        <SvwsUiTooltip>
                            Die Klassenleitung darf alle Leistungsdaten bearbeiten.
                            <template #content>
                                Die Klassenleitung darf als Vertretung einer Fachlehrkraft auch die Noten, Teilnoten, 
                                usw. der Fachlehrkraft editieren. Der Button zum Editieren damit für alle Klassenleitungen sichtbar.
                            </template>
                        </SvwsUiTooltip>
                    </SvwsUiCheckbox>

                    <SvwsUiButton @click="saveSettings()">Speichern</SvwsUiButton>              

                    <SvwsUiDataTable collapsible :noData="false" v-if="Object.entries(jahrgaenge).length || klassen.length">
                        <template #header>
                            <SvwsUiDataTableRow thead>
                                <SvwsUiDataTableCell thead>Gruppierung</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead>Teilnoten</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead>Noten</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Mahnung">M</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Fehlstunden">FS</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Fehlstunden/Gesamtfehlstunden">FS/GFS</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Fachbezogene Bemerkungen">FB</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Arbeits- und Sozialverhalten">ASV</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Außerunterrichtliches Engagement">AUE</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Zeugnisbemerkung">ZB</SvwsUiDataTableCell>
                            </SvwsUiDataTableRow>
                        </template>
                        <template #body>
                            <span v-if="klassen.length">
                                <SvwsUiDataTableRow>
                                    <SvwsUiDataTableCell>
                                        <div class="flex items-center gap-1">
                                            <SvwsUiButton type="icon" size="small" @click="klassenCollapsed = !klassenCollapsed">
                                                <SvwsUiIcon>
                                                    <mdi-chevron-down v-if="klassenCollapsed"></mdi-chevron-down>
                                                    <mdi-chevron-up v-else></mdi-chevron-up>
                                                </SvwsUiIcon>
                                            </SvwsUiButton>
                                            Klassen
                                        </div>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell v-for="_ in 9"></SvwsUiDataTableCell>
                                </SvwsUiDataTableRow>                    

                                <SvwsUiDataTableRow :depth="2" :collapsed="klassenCollapsed" :expanded="!klassenCollapsed" v-for="klasse in klassen">
                                    <SvwsUiDataTableCell>{{ klasse.kuerzel }}</SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                        <SvwsUiCheckbox v-model="klasse.editable_teilnoten" />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                        <SvwsUiCheckbox v-model="klasse.editable_noten" />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                        <SvwsUiCheckbox v-model="klasse.editable_mahnungen" />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                        <SvwsUiCheckbox v-model="klasse.editable_fehlstunden" />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                        <SvwsUiRadioOption v-model="klasse.toggleable_fehlstunden" :value="true">FS</SvwsUiRadioOption>
                                        <SvwsUiRadioOption v-model="klasse.toggleable_fehlstunden" :value="false">GFS</SvwsUiRadioOption>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                        <SvwsUiCheckbox v-model="klasse.editable_fb" />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                        <SvwsUiCheckbox v-model="klasse.editable_asv" />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                        <SvwsUiCheckbox v-model="klasse.editable_aue" />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell :tooltip="cellTooltip">                                           
                                        <SvwsUiCheckbox v-model="klasse.editable_zb" />                                         
                                    </SvwsUiDataTableCell>
                                </SvwsUiDataTableRow>
                            </span>

                            <span v-for="(groupedJahrgaenge, key) in jahrgaenge" v-if="Object.entries(jahrgaenge).length">
                                <SvwsUiDataTableRow>
                                    <SvwsUiDataTableCell>
                                        <div class="flex items-center gap-1">
                                            <SvwsUiButton type="icon" size="small" @click="jahgraengeCollapsed[key][0] = !jahgraengeCollapsed[key][0]">
                                                <SvwsUiIcon>
                                                    <mdi-chevron-down v-if="jahgraengeCollapsed[key][0]" />
                                                    <mdi-chevron-up v-else />
                                                </SvwsUiIcon>
                                            </SvwsUiButton>
                                            {{ key }}
                                            <SvwsUiCheckbox @update:modelValue="toggleGroup(groupedJahrgaenge)" :value="true" />  
                                        </div>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell v-for="column in toggleable">
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, column)" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                </SvwsUiDataTableRow>

                                <span v-for="(jahrgang, index) in groupedJahrgaenge">
                                    <SvwsUiDataTableRow :depth="1" :collapsed="jahgraengeCollapsed[key][0]" :expanded="!jahgraengeCollapsed[key][0]">
                                        <SvwsUiDataTableCell>
                                            <div class="flex items-center gap-1 justify-between">
                                                <span class="flex items-center gap-1">
                                                    <SvwsUiButton type="icon" size="small" @click="jahgraengeCollapsed[key][1][index] = !jahgraengeCollapsed[key][1][index]">
                                                        <mdi-chevron-down v-if="jahgraengeCollapsed[key][1][index]"></mdi-chevron-down>
                                                        <mdi-chevron-up v-else></mdi-chevron-up>
                                                    </SvwsUiButton>
                                                    {{ jahrgang.kuerzel }}
                                                </span>
                                                <SvwsUiCheckbox @update:modelValue="toggleJahrgang(jahrgang)" :value="true" />    
                                            </div>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell v-for="column in toggleable">
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, column)" :value="true" />                                            
                                        </SvwsUiDataTableCell>
                                    </SvwsUiDataTableRow>

                                    <SvwsUiDataTableRow :depth="2" :collapsed="jahgraengeCollapsed[key][0] || jahgraengeCollapsed[key][1][index]" :expanded="!jahgraengeCollapsed[key][1][index]" v-for="klasse in jahrgang.klassen">
                                        <SvwsUiDataTableCell>
                                            {{ klasse.kuerzel }}
                                            <SvwsUiCheckbox @update:modelValue="toggleKlasse(klasse)" :value="true" />                  
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                            <SvwsUiCheckbox v-model="klasse.editable_teilnoten" />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                            <SvwsUiCheckbox v-model="klasse.editable_noten" />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                            <SvwsUiCheckbox v-model="klasse.editable_mahnungen" />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                            <SvwsUiCheckbox v-model="klasse.editable_fehlstunden" />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                            <SvwsUiRadioOption v-model="klasse.toggleable_fehlstunden" :value="true">FS</SvwsUiRadioOption>
                                            <SvwsUiRadioOption v-model="klasse.toggleable_fehlstunden" :value="false">GFS</SvwsUiRadioOption>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                            <SvwsUiCheckbox v-model="klasse.editable_fb" />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                            <SvwsUiCheckbox v-model="klasse.editable_asv" />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">    
                                            <SvwsUiCheckbox v-model="klasse.editable_aue" />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell :tooltip="cellTooltip">                                           
                                            <SvwsUiCheckbox v-model="klasse.editable_zb" />                                        
                                        </SvwsUiDataTableCell>
                                    </SvwsUiDataTableRow>
                                </span>
                            </span>
                        </template>
                    </SvwsUiDataTable>

                    <SvwsUiButton @click="saveMatrix()">Speichern</SvwsUiButton>
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