<template>
    <AppLayout title="Teilleistungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>

            <div class="content-area">
                <!-- <SvwsUiTable :items=...
                </SvwsUiTable> -->
                HELLO, WORLD
            </div>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosPromise, AxiosResponse } from 'axios';
    // TODO: import what's neeeded and delete what is unnecessary
    import { computed, onMounted, Ref, ref } from 'vue';
    import { mapFilterOptionsHelper, multiSelectHelper, searchHelper } from '@/Helpers/tableHelper';
    import { SvwsUiHeader, DataTableColumn, SvwsUiTable, SvwsUiMultiSelect, SvwsUiTextInput } from '@svws-nrw/svws-ui';
    import { Schueler } from '@/Interfaces/Interface';

    const title = 'Notenmanager - Teilleistungen';

    // TODO: build display elements and functions

</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6 ml-6
    }

    .content-area {
        @apply mx-4 overflow-auto ml-6
    }
</style>

