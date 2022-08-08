<script setup lang="ts">
    import { Inertia  } from '@inertiajs/inertia';
    import { onMounted } from 'vue';
    import { useStore } from '../store'
    import Menubar from '../Components/Menubar.vue'
    import TableInput from '../Components/TableInput.vue'
    import FloskelnMenu from "../Components/FloskelnMenu.vue";
    import axios from 'axios';

    const store = useStore();
    let props = defineProps({
        auth: Object,
    });

    onMounted(() => axios.get(route('get_noten')).then(response => store.noten = response.data));

    const dashboard = route('dashboard');
</script>

<template>
    <div>
        <SvwsUiAppLayout :collapsed="store.sidebarCollapsed">
            <template #sidebar>
                <Menubar :auth="props.auth" />
            </template>

            <template #main>
                <div class="w-full dark:bg-zinc-900">
                    <TableInput></TableInput>
                </div>
            </template>

            <template #contentSidebar>
                <FloskelnMenu></FloskelnMenu>
            </template>
        </SvwsUiAppLayout>
    </div>
</template>
