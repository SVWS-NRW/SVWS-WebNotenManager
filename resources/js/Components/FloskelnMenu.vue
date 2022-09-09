<script setup lang="ts">
    import { onMounted, reactive, watch, computed } from 'vue';
    import axios from 'axios';
    import { useStore } from "../store";

    type leistungType = { vorname: string, nachname: string, geschlecht: string, kurs: string|null, klasse: string|null };
    type columnsType = { id: string, title: string, sortable: boolean };
    type floskelType = { gruppe: string, id: number, kuerzel: string, text: string };
    type selectedFloskelType = { data: floskelType, selected: boolean };
    type floskelGruppeType = { kuerzel: string, floskeln: floskelType };
    type occurrenceType = { '$vorname$ $nachname$': string; '$vorname$': string, '$nachname$': string|null };
    type bemerkungType = { leistung: leistungType, floskelgruppe: string };

    const store = useStore();
    const state = reactive({
        floskeln: [],
        draft: <boolean> false,
        modalVisible: <boolean> false,
        leistung: <leistungType> {},
        floskelgruppe: <string|null> null,
        bemerkung: <string|null> null,
        storedBemerkung: <string|null> null,
        floskelgruppen: <floskelGruppeType[]> [],
        columns: <columnsType[]> [
            { id: 'kuerzel', title: 'Kürzel', sortable: true },
            { id: 'text', title: 'Text', sortable: true },
        ],
    });

    onMounted(() => axios.get(route('get_floskeln')).then(response => state.floskelgruppen = response.data));

    watch((): bemerkungType => store.bemerkungen,(data: bemerkungType): void => {
        state.leistung = data.leistung
        state.floskelgruppe = data.floskelgruppe
        state.draft = false

        axios.get(route('get_bemerkungen', [state.leistung, state.floskelgruppe]))
            .then(response => {
                state.bemerkung = state.storedBemerkung = response.data;
                state.modalVisible = true;
            });
    });

    const close = (): void => {
        let confirmation: string = "Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren."
        state.draft = state.modalVisible = (state.draft ? !confirm(confirmation) : false)
    }

    const currentFloskelGruppe = computed(() =>
        state.floskelgruppen.find(floskelgruppe => floskelgruppe.kuerzel == state.floskelgruppe)
    );

    const computedBemerkung = computed((): string => {
        const pattern: RegExp = /\$VORNAME\$ \$NACHNAME\$|\$VORNAME\$|\$Vorname\$|\$NACHNAME\$/
        let leistung = state.leistung

        let pronouns: { m: string, w: string } = { m: 'Er', w: 'Sie' };
        let pronoun: string|null = pronouns[leistung.geschlecht] !== undefined ? pronouns[leistung.geschlecht] : null;

        let initialOccurrence: occurrenceType = {
            "$vorname$ $nachname$": [leistung.vorname, leistung.nachname].join(' '),
            "$vorname$": leistung.vorname,
            "$nachname$": leistung.nachname,
        };

        let succeedingOccurrences: occurrenceType = {
            "$vorname$ $nachname$": pronoun ?? leistung.vorname,
            "$vorname$": pronoun ?? leistung.vorname,
            "$nachname$": null
        };

        state.draft = state.bemerkung != state.storedBemerkung

        return state.bemerkung
            .replace(new RegExp(pattern,"i"), (matched: string) => initialOccurrence[matched.toLowerCase()])
            .replaceAll(new RegExp(pattern ,"gi"), (matched: string) => succeedingOccurrences[matched.toLowerCase()]);
    });

    const setBemerkungen = () => {
        let url: string = route('set_bemerkungen', state.leistung);
        let config: { key: string; value: string } = { key: state.floskelgruppe, value: state.bemerkung };

        axios.post(url, config).then(() => {
            store.updateLeistungBemerkung(state.leistung, state.floskelgruppe, state.bemerkung)
            state.storedBemerkung = state.bemerkung
            state.draft = false
        })
    }

    const updateBemerkung = (bemerkung: string) => state.bemerkung = bemerkung;
    const selectFloskeln = (floskeln: Array<selectedFloskelType>) => state.floskeln = floskeln
    const addFloskeln = (): void => state.floskeln.forEach((floskel: selectedFloskelType) =>
        state.bemerkung = [state.bemerkung.trim(), floskel.data.text.trim()].join(' ')
    )
</script>

<template>
    <aside class="svws-ui-bg-white svws-ui-border-dark p-6 z-50 fixed top-0 right-0 bottom-0 w-1/2 border-l-2 flex flex-col gap-6" v-if="state.modalVisible">
        <header class="flex gap-6 items-center relative">
            <div class="absolute top-0 bottom-0 -left-10 flex items-center">
                <button type="button" @click="close" class="svws-ui-bg-dark w-8 h-8 rounded-full flex items-center justify-center">
                    <SvwsUiIcon class="svws-ui-text-white">
                        <span class="sr-only">Schließen</span>
                        <i-ri-close-line aria-hidden="true"></i-ri-close-line>
                    </SvwsUiIcon>
                </button>
            </div>

            <h1 class="svws-ui-headline-1 svws-ui-text-black">{{ state.leistung.nachname }}, {{ state.leistung.vorname }}</h1>

            <SvwsUiBadge variant="highlight" size="big" class="px-6">
                {{ state.leistung.kurs ? state.leistung.kurs : state.leistung.klasse }}
            </SvwsUiBadge>
        </header>

        <div class="flex flex-col h-full gap-12">
            <div class="h-1/3 flex flex-col gap-3">
                <SvwsUiTextareaInput class="flex-1 h-full" :value="computedBemerkung" placeholder="Tragen Sie bitte hier Ihre Bemerkungen ein." @update:value="updateBemerkung"></SvwsUiTextareaInput>
                <div class="flex gap-3">
                    <SvwsUiButton @click="setBemerkungen" :type="state.draft ? 'primary' : 'secondary'">Speichern</SvwsUiButton>
                    <SvwsUiButton @click="close" v-show="state.draft" type="secondary">Verwerfen</SvwsUiButton>
                </div>
            </div>

            <div v-if="currentFloskelGruppe" class="h-1/3 flex flex-col gap-3 mt-3">
                <h2 class="svws-ui-headline-4 svws-ui-text-black">Floskeln</h2>
                <hr class="svws-ui-border-gray">
                <div class="h-full overflow-y-scroll">
                    <SvwsUiTable :cols="state.columns" :footer="true" :multiSelect="true" :rows="currentFloskelGruppe.floskeln" v-on:update:selectedItems="selectFloskeln">
                        <template v-slot:footer>
                            <SvwsUiButton @click="addFloskeln" :type="state.floskeln.length > 0 ? 'primary' : 'secondary'">Zuweisen</SvwsUiButton>
                        </template>
                    </SvwsUiTable>
                </div>
            </div>
        </div>
    </aside>
</template>

<style scoped>
    .svws-ui--icon > svg {
        @apply h-6 w-6
    }
</style>
