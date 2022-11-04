<script setup lang="ts">
    import {computed, reactive, ref, watch} from "vue";

    import axios from "axios";
    import FloskelTable from "./FloskelTable.vue"


    type schueler = { id: Number, vorname: string, nachname: string, geschlecht: string, bemerkung: object }
    type bemerkung = string|null
    type selected = { floskelgruppe: string, schueler: schueler }
    type occurrenceType = { '$vorname$ $nachname$': string; '$vorname$': string, '$nachname$': string|null };
    type floskel = { gruppe: string, id: number, kuerzel: string, text: string };
    type floskelgruppe = { kuerzel: string, floskeln: floskel };
    type columns = { id: string, title: string, sortable: boolean };

    interface Props { selected?: selected|null, floskelgruppen: floskelgruppe[] }
    interface State { schueler?: schueler|null, bemerkung?: String, storedBemerkung?: String, isDirty: boolean, floskelgruppen }

    const emit = defineEmits(['close', 'updated'])

    let props = defineProps<Props>()

    let state = reactive({
        bemerkung: <bemerkung> '',
        storedBemerkung: <bemerkung> '',
        schueler : <schueler> null,
        isDirty: false,
        floskelgruppen: [],
        columns: <columns[]> [
            { id: 'kuerzel', title: 'Kürzel', sortable: true },
            { id: 'text', title: 'Text', sortable: true },
        ],
    })

    watch(() => props.selected, (selected: selected): void => {
        state.schueler = selected?.schueler
        state.bemerkung = state.storedBemerkung = selected?.schueler[selected?.floskelgruppe]
        state.floskelgruppen = props.floskelgruppen
    })

    const computedBemerkung = computed((): string => {
        state.isDirty = state.bemerkung != state.storedBemerkung

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

            state.isDirty = false
        })
    }

    const currentFloskelGruppe = computed((): void => state.floskelgruppen.find(
        floskelgruppe => floskelgruppe.kuerzel == props.selected.floskelgruppe
    ));

    const addFloskeln = (bemerkung: string): string => state.bemerkung = [state.bemerkung, bemerkung].join(' ').trim()

    const deleteConfirmation: string = "Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren."

    const close = () => {
        if (state.isDirty ? confirm(deleteConfirmation) : true) {
            emit('close')
        }
    }

    window.addEventListener("beforeunload", e => {
        if (state.isDirty) {
            (e || window.event).returnValue = deleteConfirmation
            return deleteConfirmation
        }

        emit('close')
    }, {capture: true})
</script>

<template>
    <aside class="bg-white border-dark p-6 z-50 fixed top-0 right-0 bottom-0 w-1/2 border-l-2 border-black/20 flex flex-col gap-6" v-if="props.selected">
        <header class="flex gap-6 justify-between">
            <div class="flex gap-6 items-center">
                <h1 class="headline-1 text-black">
                    {{ state.schueler?.nachname }}, {{ state.schueler?.vorname }}
                </h1>

                <SvwsUiBadge variant="highlight" size="big" class="px-6 uppercase">
                    {{ props.selected?.floskelgruppe }}
                </SvwsUiBadge>
            </div>

            <SvwsUiButton @click="close" type="transparent">
                <SvwsUiIcon>
                    <span class="sr-only">Schließen</span>
                    <i-ri-close-line aria-hidden="true"></i-ri-close-line>
                </SvwsUiIcon>
            </SvwsUiButton>
        </header>
        <div class="flex flex-col gap-12">
            <div class="h-1/2 flex flex-col gap-3">
                <SvwsUiTextareaInput resizeable="none" class="flex-1" :modelValue="computedBemerkung" placeholder="Tragen Sie bitte hier Ihre Bemerkungen ein." @update:modelValue="updateBemerkung"></SvwsUiTextareaInput>
                <div class="flex gap-3">
                    <SvwsUiButton @click="setBemerkungen" :type="state.isDirty ? 'primary' : 'secondary'">Speichern</SvwsUiButton>
                    <SvwsUiButton @click="close" v-show="state.isDirty" type="secondary">Verwerfen</SvwsUiButton>
                </div>
            </div>

            <div v-if="currentFloskelGruppe">
                <FloskelTable floskelgruppe="zb" :floskelgruppen="props.floskelgruppen" @added="addFloskeln"></FloskelTable>
            </div>
        </div>
    </aside>
</template>

<style scoped>
    .icon > svg {
        @apply w-8 h-8
    }
</style>