<script setup lang="ts">
    import { computed, onMounted, reactive, watch } from 'vue'
    import axios, { AxiosPromise, AxiosResponse } from 'axios'

    import { Leistung } from 'resources/js/Interfaces/Leistung'
    import { Floskel } from 'resources/js/Interfaces/Floskel'
    import { Column } from 'resources/js/Interfaces/Column'
    import { Occurrence } from 'resources/js/Interfaces/Occurrence'
    import { Pronoun } from 'resources/js/Interfaces/Pronoun'

    import FloskelTable from "../Dashboard/FloskelTable.vue"


    type Selected = { floskelgruppe: string, leistung: Leistung }

    interface Props { selected?: Selected | null }

    const emit = defineEmits(['close', 'updated'])

    let props = defineProps<Props>()

    let state = reactive({
        bemerkung: <string> '',
        storedBemerkung: <string> '',
        leistung: <Leistung | null> null,
        isDirty: <boolean> false,
        floskeln: <Floskel[]> [],
        columns: <Column[]> [
            { key: 'kuerzel', label: 'Kürzel', sortable: true },
            { key: 'text', label: 'Text', sortable: true },
        ],
    })

    onMounted((): AxiosPromise => axios
        .get(route('get_fachbezogene_floskeln'))
        .then((response: AxiosResponse) => state.floskeln = response.data))


    watch((): Selected => props.selected, (selected: Selected): void => {
        state.leistung = selected?.leistung
        state.bemerkung = state.storedBemerkung = selected?.leistung.fachbezogeneBemerkungen
    })

    const computedBemerkung = computed((): string => {
        state.isDirty = state.bemerkung != state.storedBemerkung

        if (!state.bemerkung) return

        const pattern: RegExp = /\$VORNAME\$ \$NACHNAME\$|\$VORNAME\$|\$Vorname\$|\$NACHNAME\$/

        let pronouns: Pronoun = { m: 'Er', w: 'Sie' };
        let pronoun: string | null = pronouns[state.leistung.geschlecht] !== undefined ? pronouns[state.leistung.geschlecht] : null;

        let initialOccurrence: Occurrence = {
            "$vorname$ $nachname$": [state.leistung.vorname, state.leistung.nachname].join(' '),
            "$vorname$": state.leistung.vorname,
            "$nachname$": state.leistung.nachname,
        };

        let succeedingOccurrences: Occurrence = {
            "$vorname$ $nachname$": pronoun ?? state.leistung.vorname,
            "$vorname$": pronoun ?? state.leistung.vorname,
            "$nachname$": null
        };

        return state.bemerkung
            .replace(new RegExp(pattern,"i"), (matched: string): string => initialOccurrence[matched.toLowerCase()])
            .replaceAll(new RegExp(pattern ,"gi"), (matched: string) : string => succeedingOccurrences[matched.toLowerCase()]);
    });

    const updateBemerkung = (bemerkung: string): string => state.bemerkung = bemerkung;

    const setBemerkungen = (): void => {
        let url: string = route('set_fachbezogene_bemerkung', state.leistung?.id);

        axios.post(url, {'fachbezogeneBemerkungen' : state.bemerkung}).then((): void => {
            emit('updated')
            state.storedBemerkung = state.bemerkung
            state.isDirty = false
        })
    }

    const addFloskeln = (bemerkung: string): string => state.bemerkung = [state.bemerkung, bemerkung].join(' ').trim()

    const deleteConfirmationText: string = "Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren."

    const close = () => {
        if (state.isDirty ? confirm(deleteConfirmationText) : true) {
            emit('close')
        }
    }

    window.addEventListener("beforeunload", e => {
        if (state.isDirty) {
            (e || window.event).returnValue = deleteConfirmationText
            return deleteConfirmationText
        }

        emit('close')
    }, {capture: true})
</script>

<template>
    <aside class="bg-white border-dark p-6 z-50 fixed top-0 right-0 bottom-0 w-1/2 border-l-2 border-black/20 flex flex-col gap-6" v-if="props.selected">
        <header class="flex gap-6 justify-between">
            <div class="flex gap-6 items-center">
                <h1 class="headline-1 text-black">
                    {{ props.selected?.leistung.name }}
                </h1>
                <SvwsUiBadge variant="highlight" size="big" class="px-6 uppercase">
                    {{ props.selected?.leistung.fach }}
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
        </div>


        <div v-if="state.floskeln">
            <FloskelTable :floskeln="state.floskeln" @added="addFloskeln"></FloskelTable>
        </div>
    </aside>
</template>

<style scoped>
    .icon > svg {
        @apply w-8 h-8
    }
</style>