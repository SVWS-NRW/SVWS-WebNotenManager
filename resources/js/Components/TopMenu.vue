<script setup lang="ts">
    import { computed } from "vue";
    import {Inertia} from "@inertiajs/inertia";

    const tbd = () => alert('TBD')

    interface Props { headline?: string|null }
    let props = defineProps<Props>()

    let headline = computed(() => {
        let array = ['Notenmanager']

        if (props.headline != null) {
            array.push(props.headline)
        }

        return array.join(' - ')
    })

    const logout = (): void => Inertia.post(route('logout'))
</script>

<template>
    <div class="h-24 flex items-center justify-between px-6 w-full bg-white -my-1.5">
        <div class="flex gap-6 items-center">
            <h1 class="headline-2 dark:text-zinc-200">{{ headline }}</h1>
            <slot></slot>
        </div>
        <div class="flex gap-3 text-black">
            <SvwsUiIcon @click="tbd">
                <span class="sr-only">Drucken</span>
                <i-ri-printer-line aria-hidden="true"></i-ri-printer-line>
            </SvwsUiIcon>

            <button @click="logout" class="cursor-pointer" title="Ausloggen">
                <SvwsUiIcon>
                    <i-ri-logout-box-line aria-hidden="true"></i-ri-logout-box-line>
                </SvwsUiIcon>
            </button>
        </div>
    </div>
</template>

<style scoped>
    .icon > svg  {
        @apply h-8 w-auto
    }
</style>