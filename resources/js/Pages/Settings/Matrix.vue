<script setup lang="ts">
    import { ref, Ref, onBeforeMount, watch } from 'vue'
    import axios, { AxiosResponse } from 'axios'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import SettingsMenu from '@/Components/SettingsMenu.vue'
    import { apiSuccess, apiError } from '@/Helpers/api.helper'

    import {
        SvwsUiButton, SvwsUiCheckbox, SvwsUiTooltip, SvwsUiRadioOption,
        SvwsUiDataTable, SvwsUiDataTableRow, SvwsUiDataTableCell,
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
    }[keyof Klasse]

    const settings: Ref<Settings> = ref({
        lehrer_can_override_fachlehrer: false,
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
        jahgraengeCollapsed.value[key] = [defaultCollapsed, Array(obj[key].length).fill(true)]
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

    const klassenGlobalToggle = ref<ToggleColumnType>(true)
    const klassenToggle: Ref<{[key: number]: ToggleColumnType}> = ref({})
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
    })

    const jahrgangKlassenToggle: Ref<{[key: number]: ToggleColumnType}> = ref({})
    const jahrgangsKlassenColumnsToggle: Ref<{[key: number]: {[key: string]: ToggleColumnType}}> = ref({})
    const jahrgangsGroupsColumnsToggle: Ref<{[key: string]: {[key: string]: ToggleColumnType}}> = ref({})
    const jahrgangToggle: Ref<{[key: number]:  ToggleColumnType}> = ref({})
    const jahrgangGroupToggle: Ref<{[key: string]: ToggleColumnType}> = ref({})

    watch(klassen, (): void => {
        updateKlassenToggleState(klassen.value, klassenToggle)

        toggleable.forEach((column: ToggleableKeys): ToggleColumnType =>
            updateColumnToggleState(klassenColumnsToggle.value, klassen.value, column)
        )

        klassenGlobalToggle.value = checkState(
            Object.values(klassen.value).reduce((count: number, klasse: Klasse): number =>
                 count + checkboxes().filter((column: ToggleableKeys): boolean => klasse[column]).length
            , 0),
            checkboxes().length * klassen.value.length,
        )
    }, { deep: true })

    watch(jahrgaenge, (): void => {
        Object.entries(jahrgaenge.value).forEach(([key, jahrgangGroup]: [string, Jahrgang[]]): void => {
            jahrgangsGroupsColumnsToggle.value[key] = {}
            toggleable.forEach((column: ToggleableKeys): any =>
                jahrgangsGroupsColumnsToggle.value[key][column] = checkState(
                    jahrgangGroup.reduce((count: number, jahrgang: Jahrgang): number =>
                        count + jahrgang.klassen.filter((klasse: Klasse): boolean => klasse[column]).length,
                    0),
                    jahrgangGroup.reduce((count: number, jahrgang: Jahrgang): number =>
                        count + jahrgang.klassen.length,
                    0),
                )
            )

            jahrgangGroupToggle.value[key] = checkState(
                jahrgangGroup.reduce((count: number, jahrgang: Jahrgang): number =>
                    count + jahrgang.klassen.reduce((count: number, klasse: Klasse): number =>
                        count + checkboxes().filter((column: ToggleableKeys): boolean => klasse[column]).length
                    , 0)
                , 0),
                jahrgangGroup.reduce((count: number, jahrgang: Jahrgang): number =>
                    count + jahrgang.klassen.length
                , 0) * checkboxes().length
            )

            jahrgangGroup.forEach((jahrgang: Jahrgang): void => {
                jahrgangToggle.value[jahrgang.id] = checkState(
                    jahrgang.klassen.reduce((count: number, klasse: Klasse): number => {
                        return count + checkboxes().filter((column: ToggleableKeys): boolean => klasse[column]).length
                    }, 0),
                jahrgang.klassen.length * checkboxes().length,
                )

                jahrgangsKlassenColumnsToggle.value[jahrgang.id] = {}
                toggleable.forEach((column: ToggleableKeys): ToggleColumnType =>
                    updateColumnToggleState(jahrgangsKlassenColumnsToggle.value[jahrgang.id], jahrgang.klassen, column)
                )

                updateKlassenToggleState(jahrgang.klassen, jahrgangKlassenToggle)
            })
        })
    }, { deep: true })

    const toggleAllKlassen = (): void => klassen.value.forEach((klasse: Klasse): void =>
        checkboxes().forEach((column: ToggleableKeys): boolean => klasse[column] = klassenGlobalToggle.value === true)
    )

    const toggleKlasse = (klasse: Klasse): void => checkboxes().forEach((column: ToggleableKeys): boolean =>
        klasse[column] = klassenToggle.value[klasse.id] === true)

    const toggleKlassenColumn = (column: ToggleableKeys): void =>
        klassen.value.forEach((klasse: Klasse): boolean =>
            klasse[column] = klassenColumnsToggle.value[column] === true
        )

    const toggleJahrgangsColumn = (jahrgang: Jahrgang, column: ToggleableKeys): void =>
        jahrgang.klassen.forEach((klasse: Klasse): boolean =>
             klasse[column] = jahrgangsKlassenColumnsToggle.value[jahrgang.id][column] === true
        )

    const toggleJahrgangsKlassenRow = (klasse: Klasse): void => checkboxes().forEach((column: ToggleableKeys): boolean =>
        klasse[column] = jahrgangKlassenToggle.value[klasse.id] === true
    )

    const toggleGroupColumn = (groupedJahrgaenge: Jahrgang[], column: ToggleableKeys, key: string) =>
        groupedJahrgaenge.forEach((jahrgang: Jahrgang): void =>
            jahrgang.klassen.forEach((klasse: Klasse): boolean =>
                klasse[column] = jahrgangsGroupsColumnsToggle.value[key][column] === true
            )
        )

    const toggleJahrgangGroup = (jahrgaenge: Jahrgang[], key: string): void =>
        jahrgaenge.forEach((jahrgang: Jahrgang): void =>
            jahrgang.klassen.forEach((klasse: Klasse): void => checkboxes().forEach((column: ToggleableKeys) =>
                klasse[column] = jahrgangGroupToggle.value[key] === true)
            )
        )

    const toggleJahrgang = (jahrgang: Jahrgang): void =>
        jahrgang.klassen.forEach((klasse: Klasse) => checkboxes().forEach((column: ToggleableKeys): boolean | string =>
            klasse[column] = jahrgangToggle.value[jahrgang.id] === true
        ))

    const updateKlassenToggleState = (klassen: Klasse[], toggle: any): void =>
        klassen.forEach((klasse: Klasse): ToggleColumnType =>
            toggle.value[klasse.id] = checkState(
                checkboxes().filter((item: ToggleableKeys): boolean => klasse[item]).length,
                checkboxes().length,
            )
        )

    const updateColumnToggleState = (toggleObject: any, items: Klasse[], column: ToggleableKeys): ToggleColumnType =>
        toggleObject[column] = checkState(items.filter((item: Klasse): boolean => item[column]).length, items.length)

    const checkState = (count: number, total: number): ToggleColumnType => {
        if (count == total) return true
        if (count == 0) return false
        return 'indeterminate'
    }

    const checkboxes = (): ToggleableKeys[] => toggleable.filter((column: ToggleableKeys): boolean => column !== 'toggleable_fehlstunden')
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
                            <!-- Klassen ohne Jahrgaenge -->
                            <span v-if="klassen.length">
                                <SvwsUiDataTableRow>
                                    <SvwsUiDataTableCell>
                                        <div class="flex items-center gap-1">
                                            <SvwsUiButton
                                                type="icon"
                                                size="small"
                                                @click="klassenCollapsed = !klassenCollapsed"
                                            >
                                            <mdi-chevron-down v-if="klassenCollapsed" />
                                            <mdi-chevron-up v-else />
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
                                        <SvwsUiCheckbox
                                            v-model="klassenColumnsToggle['editable_noten']"
                                            @update:modelValue="toggleKlassenColumn('editable_noten')"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="klassenColumnsToggle['editable_mahnungen']"
                                            @update:modelValue="toggleKlassenColumn('editable_mahnungen')"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="klassenColumnsToggle['editable_fehlstunden']"
                                            @update:modelValue="toggleKlassenColumn('editable_fehlstunden')"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiRadioOption
                                            v-model="klassenColumnsToggle['toggleable_fehlstunden']"
                                            @update:modelValue="toggleKlassenColumn('toggleable_fehlstunden')"
                                            :value="true"
                                        >
                                            FS
                                        </SvwsUiRadioOption>
                                        <SvwsUiRadioOption
                                            v-model="klassenColumnsToggle['toggleable_fehlstunden']"
                                            @update:modelValue="toggleKlassenColumn('toggleable_fehlstunden')"
                                            :value="false"
                                        >
                                            GFS
                                        </SvwsUiRadioOption>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="klassenColumnsToggle['editable_fb']"
                                            @update:modelValue="toggleKlassenColumn('editable_fb')"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="klassenColumnsToggle['editable_asv']"
                                            @update:modelValue="toggleKlassenColumn('editable_asv')"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="klassenColumnsToggle['editable_aue']"
                                            @update:modelValue="toggleKlassenColumn('editable_aue')"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="klassenColumnsToggle['editable_zb']"
                                            @update:modelValue="toggleKlassenColumn('editable_zb')"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                </SvwsUiDataTableRow>

                                <SvwsUiDataTableRow
                                    :depth="2"
                                    :collapsed="klassenCollapsed"
                                    :expanded="!klassenCollapsed"
                                    v-for="klasse in klassen"
                                >
                                    <SvwsUiDataTableCell>
                                        {{ klasse.kuerzel }}
                                        <SvwsUiCheckbox
                                            v-model="klassenToggle[klasse.id]"
                                            @update:modelValue="toggleKlasse(klasse)"
                                            :value="true"
                                        />
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
                                        <SvwsUiRadioOption
                                            v-model="klasse.toggleable_fehlstunden"
                                            :value="true"
                                        >FS</SvwsUiRadioOption>
                                        <SvwsUiRadioOption
                                            v-model="klasse.toggleable_fehlstunden"
                                            :value="false"
                                        >GFS</SvwsUiRadioOption>
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
                            <span
                                v-for="(groupedJahrgaenge, key) in jahrgaenge"
                                v-if="Object.entries(jahrgaenge).length"
                            >
                                <SvwsUiDataTableRow v-show="false">
                                    <SvwsUiDataTableCell>
                                        <div class="flex items-center gap-1">
                                            <SvwsUiButton
                                                type="icon"
                                                size="small"
                                                @click="jahgraengeCollapsed[key][0] = !jahgraengeCollapsed[key][0]"
                                            >
                                                <mdi-chevron-down v-if="jahgraengeCollapsed[key][0]" />
                                                <mdi-chevron-up v-else />
                                            </SvwsUiButton>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangGroupToggle[key]"
                                                @update:modelValue="toggleJahrgangGroup(groupedJahrgaenge, key)"
                                                :value="true"
                                            />
                                            {{ key }}
                                        </div>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="jahrgangsGroupsColumnsToggle[key]['editable_teilnoten']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_teilnoten', key)"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="jahrgangsGroupsColumnsToggle[key]['editable_noten']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_noten', key)"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="jahrgangsGroupsColumnsToggle[key]['editable_mahnungen']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_mahnungen', key)"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="jahrgangsGroupsColumnsToggle[key]['editable_fehlstunden']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_fehlstunden', key)"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiRadioOption
                                            v-model="jahrgangsGroupsColumnsToggle[key]['toggleable_fehlstunden']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'toggleable_fehlstunden', key)"
                                            :value="true"
                                        >FS</SvwsUiRadioOption>
                                        <SvwsUiRadioOption
                                            v-model="jahrgangsGroupsColumnsToggle[key]['toggleable_fehlstunden']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'toggleable_fehlstunden', key)"
                                            :value="false"
                                        >GFS</SvwsUiRadioOption>
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="jahrgangsGroupsColumnsToggle[key]['editable_fb']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_fb', key)"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="jahrgangsGroupsColumnsToggle[key]['editable_asv']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_asv', key)"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="jahrgangsGroupsColumnsToggle[key]['editable_aue']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_aue', key)"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                    <SvwsUiDataTableCell>
                                        <SvwsUiCheckbox
                                            v-model="jahrgangsGroupsColumnsToggle[key]['editable_zb']"
                                            @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_zb', key)"
                                            :value="true"
                                        />
                                    </SvwsUiDataTableCell>
                                </SvwsUiDataTableRow>

                                <span v-for="(jahrgang, index) in groupedJahrgaenge">
                                    <SvwsUiDataTableRow
                                        :depth="1"
                                        :collapsed="jahgraengeCollapsed[key][0]"
                                        :expanded="!jahgraengeCollapsed[key][0]"
                                    >
                                        <SvwsUiDataTableCell>
                                            <div class="flex items-center gap-1 justify-between">
                                                <SvwsUiButton
                                                    type="icon"
                                                    size="small"
                                                    @click="jahgraengeCollapsed[key][1][index] = !jahgraengeCollapsed[key][1][index]"
                                                >
                                                    <mdi-chevron-down v-if="jahgraengeCollapsed[key][1][index]" />
                                                    <mdi-chevron-up v-else />
                                                </SvwsUiButton>
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangToggle[jahrgang.id]"
                                                    @update:modelValue="toggleJahrgang(jahrgang)"
                                                    :value="true"
                                                />
                                                {{ jahrgang.kuerzel }}
                                            </div>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_teilnoten']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_teilnoten')"
                                                :value="true"
                                            />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_noten']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_noten')"
                                                :value="true"
                                            />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_mahnungen']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_mahnungen')"
                                                :value="true"
                                            />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_fehlstunden']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_fehlstunden')"
                                                :value="true"
                                            />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiRadioOption
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['toggleable_fehlstunden']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'toggleable_fehlstunden')"
                                                :value="true"
                                            >FS</SvwsUiRadioOption>
                                            <SvwsUiRadioOption
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['toggleable_fehlstunden']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'toggleable_fehlstunden')"
                                                :value="false"
                                            >GFS</SvwsUiRadioOption>
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_fb']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_fb')"
                                                :value="true"
                                            />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_asv']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_asv')"
                                                :value="true"
                                            />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_aue']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_aue')"
                                                :value="true"
                                            />
                                        </SvwsUiDataTableCell>
                                        <SvwsUiDataTableCell>
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_zb']"
                                                @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_zb')"
                                                :value="true"
                                            />
                                        </SvwsUiDataTableCell>
                                    </SvwsUiDataTableRow>

                                    <SvwsUiDataTableRow
                                        :depth="2"
                                        :collapsed="jahgraengeCollapsed[key][0] || jahgraengeCollapsed[key][1][index]"
                                        :expanded="!jahgraengeCollapsed[key][1][index]"
                                        v-for="klasse in jahrgang.klassen"
                                    >
                                        <SvwsUiDataTableCell>
                                            <div class="flex items-center gap-1">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangKlassenToggle[klasse.id]"
                                                    @update:modelValue="toggleJahrgangsKlassenRow(klasse)"
                                                    :value="true"
                                                />
                                                {{ klasse.kuerzel }}
                                            </div>
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
                                            <SvwsUiRadioOption
                                                v-model="klasse.toggleable_fehlstunden"
                                                :value="true"
                                            >FS</SvwsUiRadioOption>
                                            <SvwsUiRadioOption
                                                v-model="klasse.toggleable_fehlstunden"
                                                :value="false"
                                            >GFS</SvwsUiRadioOption>
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

    /* testing here */
    .checkbox--checked:not(.checkbox--bw) .icon, .checkbox--indeterminate:not(.checkbox--bw) .icon {
    --tw-text-opacity: 1;
    @apply ui-bg-black !important;
}
</style>
