<script setup lang="ts">
    import { computed, reactive, watch } from "vue";
    import axios from "axios";

    import FloskelTable from "../FloskelTable.vue"

    type schueler = { id: Number, vorname: string, nachname: string, geschlecht: string, bemerkung: object }
    type bemerkung = string|null
    type selected = { floskelgruppe: string, schueler: schueler }
    type occurrenceType = { '$vorname$ $nachname$': string; '$vorname$': string, '$nachname$': string|null };

    type floskel = { gruppe: string, id: number, kuerzel: string, text: string };
    type floskelgruppe = { kuerzel: string, floskeln: floskel };
    type columns = { id: string, title: string, sortable: boolean };

    interface Props { selected?: selected|null, floskelgruppen }
    interface State { schueler?: schueler|null, bemerkung?: String, storedBemerkung?: String, draft: boolean, floskelgruppen }

    const emit = defineEmits(['close', 'updated'])

    let props = defineProps<Props>()

    let state = reactive({
        bemerkung: <bemerkung> '',
        storedBemerkung: <bemerkung> '',
        schueler : <schueler> null,
        draft: false,
        floskelgruppen: [],
        columns: <columns[]> [
            { id: 'kuerzel', title: 'Kürzel', sortable: true },
            { id: 'text', title: 'Text', sortable: true },
        ],
    })

    watch(() => props.selected, (selected: selected) : void => {
        state.schueler = selected?.schueler
        state.bemerkung = state.storedBemerkung = selected?.schueler[selected?.floskelgruppe]
        state.floskelgruppen = props.floskelgruppen
    })

    const close = () => {
        let confirmation: string = "Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren."
        if (state.draft ? confirm(confirmation) : true) emit('close')
    }

    const computedBemerkung = computed((): string => {
        state.draft = state.bemerkung != state.storedBemerkung

        if (!state.bemerkung) return

        const pattern: RegExp = /\$VORNAME\$ \$NACHNAME\$|\$VORNAME\$|\$Vorname\$|\$NACHNAME\$/
        let schueler = state.schueler

        let pronouns: { m: string, w: string } = { m: 'Er', w: 'Sie' };
        let pronoun: string|null = pronouns[schueler.geschlecht] !== undefined ? pronouns[schueler.geschlecht] : null;

        let initialOccurrence: occurrenceType = {
            "$vorname$ $nachname$": [schueler.vorname, schueler.nachname].join(' '),
            "$vorname$": schueler.vorname,
            "$nachname$": schueler.nachname,
        };

        let succeedingOccurrences: occurrenceType = {
            "$vorname$ $nachname$": pronoun ?? schueler.vorname,
            "$vorname$": pronoun ?? schueler.vorname,
            "$nachname$": null
        };

        return state.bemerkung
            .replace(new RegExp(pattern,"i"), (matched: string) => initialOccurrence[matched.toLowerCase()])
            .replaceAll(new RegExp(pattern ,"gi"), (matched: string) => succeedingOccurrences[matched.toLowerCase()]);
    });

    const updateBemerkung = (bemerkung: string): string => state.bemerkung = bemerkung;

    const setBemerkungen = (): void => {
        let url: string = route('set_schueler_bemerkung', state.schueler.id);

        let config: object = {
            [props.selected?.floskelgruppe] : state.bemerkung
        };

        axios.post(url, config).then((): void => {
            emit('updated')
            state.storedBemerkung = state.bemerkung
            state.draft = false
        })
    }

    const currentFloskelGruppe = computed((): void =>
        state.floskelgruppen.find(floskelgruppe => floskelgruppe.kuerzel == props.selected.floskelgruppe)
    );

    const addFloskeln = (bemerkung: string): string => state.bemerkung = [state.bemerkung, bemerkung].join(' ').trim()
</script>

<template>
    <aside class="svws-ui-bg-white svws-ui-border-dark p-6 z-50 fixed top-0 right-0 bottom-0 w-1/2 border-l-2 flex flex-col gap-6" v-if="props.selected">
        <header class="flex gap-6 items-center relative">
            <div class="absolute top-0 bottom-0 -left-10 flex items-center">
                <button type="button" @click="close" class="svws-ui-bg-dark w-8 h-8 rounded-full flex items-center justify-center">
                    <SvwsUiIcon class="svws-ui-text-white">
                        <span class="sr-only">Schließen</span>
                        <i-ri-close-line aria-hidden="true"></i-ri-close-line>
                    </SvwsUiIcon>
                </button>
            </div>

            <h1 class="svws-ui-headline-1 svws-ui-text-black">{{ state.schueler?.nachname }}, {{ state.schueler?.vorname }}</h1>

            <SvwsUiBadge variant="highlight" size="big" class="px-6 uppercase">
                {{ props.selected?.floskelgruppe }}
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
                <FloskelTable floskelgruppe="zb" :floskelgruppen="props.floskelgruppen" @added="addFloskeln"></FloskelTable>
            </div>
        </div>
    </aside>
</template>

<style scoped>
    .svws-ui--icon > svg {
        @apply w-7 h-7
    }
</style>