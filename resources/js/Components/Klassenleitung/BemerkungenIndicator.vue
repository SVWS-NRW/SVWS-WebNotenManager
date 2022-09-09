<script setup lang="ts">
    const emit = defineEmits(['open'])
    const open = () => emit('open')

    let props = defineProps({
        bemerkung: Boolean,
    })
</script>

<template>
    <button @click="open">
        <SvwsUiIcon v-if="props.bemerkung">
            <span class="sr-only">Ist ausgefüllt</span>
            <i-ri-checkbox-line aria-hidden="true"></i-ri-checkbox-line>
        </SvwsUiIcon>

        <SvwsUiIcon v-else>
            <span class="sr-only">Ist nicht ausgefüllt</span>
            <i-ri-checkbox-blank-line aria-hidden="true"></i-ri-checkbox-blank-line>
        </SvwsUiIcon>
    </button>
</template>

<style scoped>
    .svws-ui--icon > svg {
        @apply w-7 h-7
    }
</style>
