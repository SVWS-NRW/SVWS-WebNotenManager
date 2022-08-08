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
        modalVisible: <boolean> false,
        leistung: <leistungType> {},
        floskelgruppe: <string|null> null,
        bemerkung: <string|null> null,
        floskelgruppen: <floskelGruppeType[]> [],
        columns: <columnsType[]> [
            { id: 'kuerzel', title: 'Kürzel', sortable: true },
            { id: 'text', title: 'Text', sortable: true },
        ],
    });

    onMounted(() => axios.get(route('get_floskeln')).then(response => state.floskelgruppen = response.data));

    watch((): bemerkungType => store.bemerkungen,(data: bemerkungType): void => {
        state.leistung = data.leistung;
        state.floskelgruppe = data.floskelgruppe

        axios.get(route('get_bemerkungen', [state.leistung, state.floskelgruppe]))
            .then(response => {
                state.bemerkung = response.data;
                state.modalVisible = true;
            });
    });

    const close = (): boolean => state.modalVisible = false;

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

        return state.bemerkung
            .replace(new RegExp(pattern,"i"), (matched: string) => initialOccurrence[matched.toLowerCase()])
            .replaceAll(new RegExp(pattern ,"gi"), (matched: string) => succeedingOccurrences[matched.toLowerCase()]);
    });

    const setBemerkungen = () => {
        let url: string = route('set_bemerkungen', state.leistung);
        let config: { key: string; value: string } = { key: state.floskelgruppe, value: state.bemerkung };

        axios.post(url, config).then(() =>
            store.updateLeistungBemerkung(state.leistung, state.floskelgruppe, state.bemerkung)
        );
    }

    const updateBemerkung = (bemerkung: string) => state.bemerkung = bemerkung;
    const selectFloskeln = (floskeln: Array<selectedFloskelType>) => state.floskeln = floskeln
    const addFloskeln = (): void => state.floskeln.forEach((floskel: selectedFloskelType) =>
        state.bemerkung = [state.bemerkung.trim(), floskel.data.text.trim()].join(' ')
    )
</script>

<template>
    <aside id="floskel-menu" class="svws-ui-bg-white svws-ui-border-dark" v-if="state.modalVisible">
        <header>
            <div>
                <button type="button" @click="close" class="svws-ui-bg-dark">
                    <SvwsUiIcon class="svws-ui-text-white">
                        <span>Schließen</span>
                        <i-ri-close-line aria-hidden="true"></i-ri-close-line>
                    </SvwsUiIcon>
                </button>
            </div>

            <h1 class="svws-ui-headline-1 svws-ui-text-black">{{ state.leistung.nachname }}, {{ state.leistung.vorname }}</h1>

            <SvwsUiBadge variant="highlight" size="big" class="px-6">
                {{ state.leistung.kurs ? state.leistung.kurs : state.leistung.klasse }}
            </SvwsUiBadge>
        </header>

        <div id="floskel-menu-contents">
            <SvwsUiTextareaInput :value="computedBemerkung" placeholder="Tragen Sie bitte hier Ihre Bemerkungen ein." @update:value="updateBemerkung"></SvwsUiTextareaInput>
            <SvwsUiButton @click="setBemerkungen">Speichern</SvwsUiButton>

            <div v-if="currentFloskelGruppe" id="floskeln-container">
                <h2 class="svws-ui-headline-4 svws-ui-text-black">Floskeln</h2>
                <hr class="svws-ui-border-gray">
                <div id="floskeln-table">
                    <SvwsUiTable :cols="state.columns" :footer="true" :multiSelect="true" :rows="currentFloskelGruppe.floskeln" v-on:update:selectedItems="selectFloskeln">
                        <template v-slot:footer>
                            <SvwsUiButton @click="addFloskeln">Zuweisen</SvwsUiButton>
                        </template>
                    </SvwsUiTable>
                </div>
            </div>
        </div>
    </aside>
</template>



<style scoped>
    #floskel-menu {
        @apply p-6 z-50 fixed top-0 right-0 bottom-0 w-1/2 border-l-2 flex flex-col gap-6
    }

    #floskel-menu > header {
        @apply flex gap-6 items-center relative
    }

    #floskel-menu > header > div {
        @apply absolute top-0 bottom-0 -left-10 flex items-center
    }

    #floskel-menu > header > div > button {
        @apply w-8 h-8 rounded-full flex items-center justify-center
    }

    #floskel-menu > header > div > button > .svws-ui--icon > span {
        @apply sr-only
    }

    #floskel-menu > header > div > button > .svws-ui--icon > svg {
        @apply h-6 w-6
    }

    #floskel-menu > #floskel-menu-contents {
        @apply flex flex-col gap-3
    }

    #floskel-menu > #floskel-menu-contents > button {
        @apply self-start
    }

    #floskel-menu > #floskel-menu-contents > #floskeln-container {
        @apply flex flex-col gap-3 mt-3
    }

    #floskel-menu > #floskel-menu-contents > #floskeln-container > #floskeln-table {
        @apply max-h-96 overflow-y-scroll
    }
</style>
