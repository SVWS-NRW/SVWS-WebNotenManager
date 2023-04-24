<script setup lang="ts">
    import { reactive, ref, computed } from 'vue'
    import AppLayout from '../../Layouts/AppLayout.vue'
    import axios, {AxiosResponse} from 'axios'
    import SettingsMenu from '../../Components/SettingsMenu.vue'

    import { SvwsUiTextInput, SvwsUiButton, SvwsUiCheckbox, SvwsUiTooltip} from '@svws-nrw/svws-ui'

    let props = defineProps({
        auth: Object,
    })

    let jahrgaenge = ref([])
    let klassen = ref([])

    const matrixItems = [
        'editable_teilnoten', 'editable_noten', 'editable_mahnungen', 'editable_fehlstunden',
        'editable_fb', 'editable_asv', 'editable_aue', 'editable_zb',
    ]

    axios.get(route('api.matrix.index'))
        .then((response: AxiosResponse): void => {
            jahrgaenge.value = response.data.jahrgaenge
            klassen.value = response.data.klassen
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
                <SvwsUiCheckbox v-model="settings.lehrer_can_override_fachlehrer" value="true" @update:modelValue="saveSettings($event, 'lehrer_can_override_fachlehrer')">
                    <SvwsUiTooltip>
                        Die Klassenleitung darf alle Leistungsdaten bearbeiten.
                        <template #content>
                            "Die Klassenleitung darf als Vertretung einer Fachlehrkraft auch die Noten, Teilnoten, usw. der Fachlehrkraft editieren. Der Button zum Editieren damit für alle Klassenleitungen sichtbar.
                        </template>
                    </SvwsUiTooltip>
                </SvwsUiCheckbox>

                <table>
                    <thead>
                        <tr>
                            <th>Gruppierung</th>
                            <th>Teilnoten</th>
                            <th>Noten</th>
                            <th>
                                <SvwsUiTooltip>
                                    M
                                    <template #content>
                                        Mahnungen
                                    </template>
                                </SvwsUiTooltip>
                            </th>
                            <th>
                                <SvwsUiTooltip>
                                    FS
                                    <template #content>
                                        Fehlstunden
                                    </template>
                                </SvwsUiTooltip>
                            </th>
                            <th>
                                <SvwsUiTooltip>
                                    FB
                                    <template #content>
                                        Fachbezogene Bemerkungen
                                    </template>
                                </SvwsUiTooltip>
                            </th>
                            <th>
                                <SvwsUiTooltip>
                                    ASV
                                    <template #content>
                                        Arbeits und Sozialverhalten
                                    </template>
                                </SvwsUiTooltip>
                            </th>
                            <th>
                                <SvwsUiTooltip>
                                    AUE
                                    <template #content>
                                        Außerunterrichtliches Engagement
                                    </template>
                                </SvwsUiTooltip>
                            </th>
                            <th>
                                <SvwsUiTooltip>
                                    ZB
                                    <template #content>
                                        Zeugnisbemerkung
                                    </template>
                                </SvwsUiTooltip>
                            </th>
                        </tr>
                    </thead>

                    <tbody v-if="klassen">
                        <tr v-for="klasse in klassen">
                            <td>{{ klasse.kuerzel }}</td>
                            <td v-for="item in matrixItems">
                                <SvwsUiTooltip>
                                    <SvwsUiCheckbox v-model="klasse[item]" @update:modelValue="saveMatrix(klasse, item, $event)"></SvwsUiCheckbox>
                                    <template #content>
                                        Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                    </template>
                                </SvwsUiTooltip>
                            </td>
                        </tr>
                    </tbody>

                    <template v-for="(groupedJahrgaenge, jahrgangKey) in jahrgaenge">
                        <tbody v-for="jahrgang in groupedJahrgaenge">
                            <tr v-for="klasse in jahrgang.klassen">
                                <td>{{ jahrgangKey }} /  {{ jahrgang.kuerzel }} / {{ klasse.kuerzel }}</td>
                                <td v-for="item in matrixItems">
                                    <SvwsUiTooltip>
                                        <SvwsUiCheckbox v-model="klasse[item]" @update:modelValue="saveMatrix(klasse, item, $event)"></SvwsUiCheckbox>
                                        <template #content>
                                            Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
                                        </template>
                                    </SvwsUiTooltip>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </table>
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
        @apply ui-px-6 ui-flex ui-flex-col ui-gap-12
    }

    .button {
        @apply ui-self-start
    }
</style>