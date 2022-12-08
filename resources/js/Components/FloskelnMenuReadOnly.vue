<script setup lang="ts">
    import { Schueler } from 'resources/js/Interfaces/Schueler'

    let props = defineProps<{
        schueler?: Schueler | null
    }>()

    const emit = defineEmits(['close', 'updated'])
    const close = () => emit('close')

</script>

<template>
    <aside class="bg-white border-dark p-6 z-50 fixed top-0 right-0 bottom-0 w-1/2 border-l-2 border-black/20 flex flex-col gap-6" v-if="props.schueler">
        <header class="flex gap-6 justify-between">
            <div class="flex gap-6 items-center">
                <h1 class="headline-1 text-black">
                    {{ props.schueler.nachname }}, {{ props.schueler.vorname }}
                </h1>
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
                <p>{{ props.schueler.fachbezogeneBemerkungen ?? 'Keine Angabe' }}</p>
            </div>
        </div>
    </aside>
</template>

<style scoped>
    .icon > svg {
        @apply w-8 h-8
    }
</style>