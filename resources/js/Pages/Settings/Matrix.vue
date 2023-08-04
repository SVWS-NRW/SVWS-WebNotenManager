<script setup lang="ts">
    import { ref, Ref, onBeforeMount, reactive, watch } from 'vue'
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

    type ToggleColumnType = boolean | 'indeterminate'

    interface ToggleColumns {
        editable_teilnoten: ToggleColumnType
        editable_noten: ToggleColumnType
        editable_mahnungen: ToggleColumnType
        editable_fehlstunden: ToggleColumnType
        toggleable_fehlstunden: ToggleColumnType
        editable_fb: ToggleColumnType
        editable_asv: ToggleColumnType
        editable_aue: ToggleColumnType
        editable_zb: ToggleColumnType
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
    const setTableCollapseValues = (obj: Record<string, any[]>): void => Object.keys(obj).forEach((key: string) => 
        jahgraengeCollapsed.value[key] = [defaultCollapsed, Array(obj[key].length).fill(defaultCollapsed)]
    )

    const save = (): void => {
        const klassenArray = Object.values(jahrgaenge.value)
            .flat()
            .map((item: Jahrgang): Klasse[] => item.klassen)
            .flat()
            .concat(klassen.value)

        axios.put(route('api.matrix.update'), {klassen: klassenArray})    
            .then((): void => apiSuccess())
            .catch((error: any): void => apiError(error, 'Ein Problem ist aufgetreten bei Speichern von der Matrix'))

        axios.put(route('api.settings.bulk_update', {group: 'matrix'}), {settings: settings.value})
            .then((): void => apiSuccess())
            .catch((error: any): void => apiError(
                error, 
                'Ein Problem ist aufgetreten bei Speichern von "Die Klassenleitung darf alle Leistungsdaten bearbeiten."'
            ))  
    }        

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
        (column: ToggleableKeys): boolean => klasse[item] = !klasse[item]
    )    

    const toggleAllKlassen = (): void => 
        klassen.value.forEach((klasse: Klasse): void => 
            toggleable.forEach((column: ToggleableKeys): boolean =>
                klasse[column] = klassenGlobalToggle.value === true
            )
        )    

    const toggleKlassenColumn = (column: ToggleableKeys) => 
        klassen.value.forEach((klasse: Klasse): boolean => 
            klasse[column] = klassenColumnsToggle.value[column] === true
        )

    const toggleJahrgang = (jahrgang: Jahrgang) => jahrgang.klassen.forEach(
        (klasse: Klasse): void => toggleKlasse(klasse)
    )

    const toggleGroup = (jahrgaenge: Jahrgang[]) => jahrgaenge.forEach(
        (jahrgang: Jahrgang): void => toggleJahrgang(jahrgang)
    )
   
    const toggleJahrgangColumn = (jahrgang: Jahrgang, column: ToggleableKeys): void => 
        jahrgang.klassen.forEach((klasse: Klasse): boolean => klasse[column] = !klasse[column]
    )

    const toggleGroupColumn = (jahrgaenge: Jahrgang[], column: ToggleableKeys) => 
        jahrgaenge.forEach((jahrgang: Jahrgang): void => toggleJahrgangColumn(jahrgang, column)
    )

    const checkState = (count: number, total: number): ToggleColumnType => {
        if (count == total) return true       
        if (count == 0) return false       
        return 'indeterminate'
    }  

    const klassenGlobalToggle = ref<ToggleColumnType>(true) 

    const klassenColumnsToggle = ref<ToggleColumns>({
        editable_teilnoten: false,
        editable_noten: false,
        editable_mahnungen: false,
        editable_fehlstunden: false,
        toggleable_fehlstunden: false,
        editable_fb: false,
        editable_asv: false,
        editable_aue: false,
        editable_zb: false,
    });

    watch(klassen, (): void => {
        toggleable.forEach((column: ToggleableKeys): ToggleColumnType => 
            klassenColumnsToggle.value[column] = checkState(
                klassen.value.filter((klasse: Klasse): boolean => klasse[column] === true).length,
                klassen.value.length
            )
        )

        klassenGlobalToggle.value = checkState(
            toggleable.filter((item: ToggleableKeys): boolean => klassenColumnsToggle.value[item] === true).length,
            toggleable.length,
        )
    }, { deep: true })

    


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
                                "Die Klassenleitung darf als Vertretung einer Fachlehrkraft auch die Noten, Teilnoten, 
                                usw. der Fachlehrkraft editieren. Der Button zum Editieren damit für alle Klassenleitungen sichtbar.
                            </template>
                        </SvwsUiTooltip>
                    </SvwsUiCheckbox>

                    {{  klassen }}

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
                                <SvwsUiDataTableCell thead tooltip="Arbeits und Sozialverhalten">ASV</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Außerunterrichtliches Engagement">AUE</SvwsUiDataTableCell>
                                <SvwsUiDataTableCell thead tooltip="Zeugnisbemerkung">ZB</SvwsUiDataTableCell>
                            </SvwsUiDataTableRow>
                        </template>

                        <template #body>
                            <!-- Klassen ohne Jahrgaenge -->
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
                                            <SvwsUiCheckbox 
                                                v-model="klassenGlobalToggle" 
                                                @update:modelValue="toggleAllKlassen()" 
                                                :value="true" 
                                            />  
                                        </div>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox 
                                            v-model="klassenColumnsToggle['editable_teilnoten']" 
                                            @update:modelValue="toggleKlassenColumn('editable_teilnoten')" 
                                            :value="true" 
                                        />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_noten']" @update:modelValue="toggleKlassenColumn('editable_noten')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_mahnungen']" @update:modelValue="toggleKlassenColumn('editable_mahnungen')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_fehlstunden']" @update:modelValue="toggleKlassenColumn('editable_fehlstunden')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>                                       
                                        <SvwsUiRadioOption v-model="klassenColumnsToggle['toggleable_fehlstunden']" @update:modelValue="toggleKlassenColumn('toggleable_fehlstunden')" :value="true">FS</SvwsUiRadioOption>
                                        <SvwsUiRadioOption v-model="klassenColumnsToggle['toggleable_fehlstunden']" @update:modelValue="toggleKlassenColumn('toggleable_fehlstunden')" :value="false">GFS</SvwsUiRadioOption>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_fb']" @update:modelValue="toggleKlassenColumn('editable_fb')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_asv']" @update:modelValue="toggleKlassenColumn('editable_asv')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_aue']" @update:modelValue="toggleKlassenColumn('editable_aue')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_zb']" @update:modelValue="toggleKlassenColumn('editable_zb')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                </SvwsUiDataTableRow>                    

                                <SvwsUiDataTableRow :depth="2" :collapsed="klassenCollapsed" :expanded="!klassenCollapsed" v-for="klasse in klassen">
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

                            <!-- Klassen mit Jahrgaenge -->
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
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_teilnoten')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_noten')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_mahnungen')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_fehlstunden')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>                                       
                                        <SvwsUiRadioOption @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'toggleable_fehlstunden')" :value="true">FS</SvwsUiRadioOption>
                                        <SvwsUiRadioOption @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'toggleable_fehlstunden')" :value="false">GFS</SvwsUiRadioOption>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_fb')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_asv')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_aue')" :value="true" />                                            
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_zb')" :value="true" />                                            
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
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, 'editable_teilnoten')" :value="true" />                                            
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, 'editable_noten')" :value="true" />                                            
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, 'editable_mahnungen')" :value="true" />                                            
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, 'editable_fehlstunden')" :value="true" />                                            
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>                                       
                                            <SvwsUiRadioOption @update:modelValue="toggleJahrgangColumn(jahrgang, 'toggleable_fehlstunden')" :value="true">FS</SvwsUiRadioOption>
                                            <SvwsUiRadioOption @update:modelValue="toggleJahrgangColumn(jahrgang, 'toggleable_fehlstunden')" :value="false">GFS</SvwsUiRadioOption>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, 'editable_fb')" :value="true" />                                            
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, 'editable_asv')" :value="true" />                                            
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, 'editable_aue')" :value="true" />                                            
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox @update:modelValue="toggleJahrgangColumn(jahrgang, 'editable_zb')" :value="true" />                                            
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

                    <SvwsUiButton @click="save()">Speichern</SvwsUiButton>
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