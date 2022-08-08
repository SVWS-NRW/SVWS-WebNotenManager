<script setup lang="ts">
    import { useStore } from "../store";

    const store = useStore();
    const props = defineProps(['leistung', 'floskelgruppe']);

    const openMenu = () => store.openBemerkungMenu(props.leistung, props.floskelgruppe);
    const isPresent = () => props.leistung[props.floskelgruppe]
</script>

<template>
    <button @click="openMenu">
        <SvwsUiIcon v-if="isPresent()">
            <span>Ist ausgefüllt</span>
            <i-ri-checkbox-line aria-hidden="true"></i-ri-checkbox-line>
        </SvwsUiIcon>

        <SvwsUiIcon v-else>
            <span>Ist nicht ausgefüllt</span>
            <i-ri-checkbox-blank-line aria-hidden="true"></i-ri-checkbox-blank-line>
        </SvwsUiIcon>
    </button>
</template>

<style scoped>
    .svws-ui--icon > span {
        @apply sr-only
    }

    .svws-ui--icon > svg {
        @apply w-7 h-7
    }
</style>
