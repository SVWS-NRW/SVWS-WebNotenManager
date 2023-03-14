<script setup lang="ts"> // ok
    import { computed, reactive, Ref, ref } from 'vue'
    import { Leistung } from '../Interfaces/Leistung'
    import { formatStringBasedOnGender } from '../Helpers/string.helper'

    const props = defineProps<{
        leistung: Leistung
    }>()

    let state: { bemerkung: string } = reactive({
        bemerkung: <string> props.leistung.fachbezogeneBemerkungen,
    })

    const collapsed: Ref<boolean> = ref(true)

    const open = (): boolean => collapsed.value = false
    const close = (): boolean => collapsed.value = true

    const computedBemerkung = computed((): string => formatStringBasedOnGender(state.bemerkung, props.leistung))
</script>

<template>
    <div class="content-card--blockungsuebersicht ui-flex ui-h-full ui-content-start">
        <div id="bemerkung">
            <SvwsUiIcon @click="open()" class="ui-flex ui-items-center">
                <mdi-checkbox-marked-outline v-if="state.bemerkung"></mdi-checkbox-marked-outline>
                <mdi-checkbox-blank-outline v-else></mdi-checkbox-blank-outline>
            </SvwsUiIcon>
            {{ state.bemerkung }}
        </div>

        <div class="app-layout--main-sidebar" :class="{ 'app-layout--main-sidebar--collapsed': collapsed }">
            <div class="app-layout--main-sidebar--container" v-if="!collapsed">
                <div class="app-layout--main-sidebar--trigger" @click="close">
                    <div class="sidebar-trigger--text">
                        <SvwsUiButton type="icon" class="close-button" v-if="!collapsed">
                            <SvwsUiIcon>
                                <mdi-close></mdi-close>
                            </SvwsUiIcon>
                        </SvwsUiButton>

                        {{ props.leistung.nachname }}, {{ props.leistung.vorname }}
                        <SvwsUiBadge>{{ props.leistung.klasse }}</SvwsUiBadge>
                    </div>
                </div>
                <div class="app-layout--main-sidebar--content">
                    <div id="component">
                        {{ computedBemerkung }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .close-button {
        @apply ui-mr-2 ui-p-[0.1em]
    }

    #buttons {
        @apply ui-flex ui-gap-3 ui-self-end
    }

    #component {
        @apply ui-flex ui-flex-col ui-gap-6
    }

    #bemerkung {
        @apply ui-flex ui-gap-1.5 ui-items-center ui-whitespace-nowrap ui-overflow-hidden
    }
</style>