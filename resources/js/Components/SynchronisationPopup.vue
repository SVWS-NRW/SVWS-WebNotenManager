<script setup lang="ts">
    import { Ref, ref } from 'vue';
    import { SvwsUiButton } from '@svws-nrw/svws-ui';

    //TODO: make this work; it's a dummy so far
    let props = defineProps<{
        show: boolean,
    }>()

    const emit = defineEmits(['closePopup'])

    const message = ref('Warnung: es müssen die Einstellungen im zugehörigen SVWS-Server angepasste werden');

    const displayPopup: Ref<boolean> = ref(props.show);

    const closePopup = () => {
        displayPopup.value = false;
        emit(displayPopup)
    };

</script>
<template>
    <div id="synchronisation-popup">
        <div class="popup-area">
            <div class="popup-content">
                <h2 class="popup-title">{{ message }}</h2>
                <div class="popup-buttons-area">
                    <div class="popup-buttons-area">
                        <SvwsUiButton type="secondary" @click="closePopup">Abrechen</SvwsUiButton>
                        <SvwsUiButton type="secondary" @click="xxx">OK</SvwsUiButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    #synchronisation-popup {
        @apply ui-fixed ui-z-[9998] ui-flex ui-justify-evenly ui-gap-3 ui-flex-wrap ui-top-52 ui-w-2/4 ui-leading-[5rem]
    }

    .popup-area {
        @apply ui-w-9/12 ui-h-4/5 ui-bg-white ui-border-[1px] ui-border-gray-100 ui-shadow-xl ui-mx-auto ui-p-20 ui-rounded-md
    }

    .popup-content {
        @apply ui-mx-0 ui-my-5
    }

    .popup-buttons-area {
        @apply ui-flex ui-flex ui-justify-self-auto
    }

    .button {
        @apply ui-inline-block ui-m-2
    }
</style>