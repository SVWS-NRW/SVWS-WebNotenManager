<script setup lang="ts">
    import { computed } from "vue";

    const tbd = () => alert('TBD')

    interface Props { headline?: string|null }
    let props = defineProps<Props>()

    let headline = computed(() => {
        let array = ['Notenmanager']

        if (props.headline != null) {
            array.push(props.headline)
        }

        return array.join(' - ')
    });
</script>

<template>
    <div class="h-24 flex items-center justify-between px-6 w-full">
        <div class="flex gap-6 items-center">
            <h1 class="svws-ui-headline-2 dark:text-zinc-200">{{ headline }}</h1>
            <slot></slot>
        </div>
        <div class="flex gap-3 svws-ui-text-black">
            <SvwsUiIcon @click="tbd">
                <span class="sr-only">Drucken</span>
                <i-ri-printer-line aria-hidden="true"></i-ri-printer-line>
            </SvwsUiIcon>
            <SvwsUiIcon @click="tbd">
                <span class="sr-only">Teilen</span>
                <i-ri-folder-shared-line aria-hidden="true"></i-ri-folder-shared-line>
            </SvwsUiIcon>
        </div>
    </div>
</template>

<style scoped>
    .svws-ui--icon > svg  {
        @apply h-8 w-auto
    }
</style>