<script setup lang="ts">
    import { ref, Ref } from 'vue'
    import eventBus from '@/event-bus'

    const duration: number = 3000

    type Variant = 'success' | 'error' 

    type Toast = {
        message: string
        variant:Variant
        visible: boolean
    }

    const toast: Ref<Toast> = ref({
        message: '',
        variant: 'success',
        visible: false,
    })

    eventBus.$on('toast-success', (message: string): void => 
        setMessage('success', message)
    )
    
    eventBus.$on('toast-error', (message: string): void => {
        setMessage('error', message)
    })

    const setMessage = (variant: Variant, message: string): void => {
        toast.value = {
            variant: variant,
            message: message,
            visible: true
        }

        setTimeout((): boolean => toast.value.visible = false, duration)
    }
</script>

<template>
    <div 
        class="toast" 
        :class="{ 
            'toast--success': toast.variant === 'success', 
            'toast--error': toast.variant === 'error',
            'toast--visible': toast.visible,
        }"
    >
        {{ toast.message }}
    </div>
</template>

<style scoped>
    .toast {
        @apply 
        ui-absolute ui-top-4 ui-right-4 
        ui-p-4 
        ui-rounded 
        ui-font-bold ui-text-white
        ui-transition
        ui-opacity-0
        ui-pointer-events-none
    }

    .toast--visible {
        @apply ui-opacity-100
    }

    .toast--error {
        @apply ui-bg-red-500 
    }

    .toast--success {
        @apply ui-bg-green-500
    }
</style>