<script setup lang="ts">
    import { formatStringBasedOnGender } from '../Helpers/bemerkungen.helper'
    import { CellRef, setCellRefs, navigateTable } from '../Helpers/tableNavigationHelper';
    import { Leistung, Schueler } from '../types'

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const props = defineProps<{
        model: Schueler | Leistung,
        bemerkung: string | null,
        rowIndex: number,
    }>()

    let element: CellRef = undefined

    const emit = defineEmits<EmitsOptions>()
    const clicked = (): void => emit('clicked')
    const formattedBemerkung = (): string => formatStringBasedOnGender(props.bemerkung, props.model)

    const navigate = (direction: string): Promise<void> => navigateTable(direction, props.rowIndex, element)
</script>

<template>
    <button
        @click="clicked()"
        @keydown.up.stop.prevent="navigate('up')"
        @keydown.down.stop.prevent="navigate('down')"
        @keydown.enter.stop.prevent="navigate('down')"
        @keydown.left.stop.prevent="navigate('left')"
        @keydown.right.stop.prevent="navigate('right')"
        @keydown.tab.stop.prevent="navigate('right')"
        :ref="(el: CellRef): CellRef => {element = el; setCellRefs(element, props.rowIndex); return el}"
    >
        <span v-if="props.bemerkung" class="indicator">
            <span class="icon">
                   <mdi-checkbox-marked-outline></mdi-checkbox-marked-outline>
            </span>
            <span class="indicator__bemerkung">
                {{ formattedBemerkung() }}
            </span>
        </span>

        <span class="icon" v-else>
           <mdi-checkbox-blank-outline></mdi-checkbox-blank-outline>
        </span>
    </button>
</template>

<style scoped>
    button {
        @apply ui-max-w-full
    }

    .indicator {
        @apply ui-flex ui-gap-1.5 ui-items-center ui-justify-start
    }

    .indicator__bemerkung {
        @apply ui-truncate
    }
</style>
