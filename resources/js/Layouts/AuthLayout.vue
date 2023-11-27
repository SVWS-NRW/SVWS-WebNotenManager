<script setup lang="ts">
    import { SvwsUiAppLayout } from '@svws-nrw/svws-ui'
    import { usePage } from '@inertiajs/inertia-vue3'
</script>

<template>
    <SvwsUiAppLayout :fullwidthContent="true" v-cloak>
        <template #main>
            <div class="component">
                <slot name="main" />

                <footer>
                    <div class="disclaimer">
                        <h3>Powered by SVWS-NRW, Version {{ usePage().props.value.version }}</h3>
                        <h3>NPM-Version {{ usePage().props.value.npm }}</h3>
                        <p>Um eine gute Lesbarkeit zu erzeugen, wird bei SVWS-NRW möglichst auf geschlechtsneutrale Begriffe wie Lehrkräfte, Klassenleitung, Erzieher usw. zurückgegriffen. An Stellen, wo das nicht möglich ist, wird versucht alle Geschlechter gleichermaßen zu verwenden.</p>
                        <nav>
                            <a :href="route('impressum')">Impressum</a>
                            <a :href="route('datenschutz')">Datenschutz</a>
                            <a :href="route('barrierefreiheit')">Barrierefreiheit</a>
                            <a href="https://schulverwaltungsinfos.nrw.de/svws/wiki/index.php?title=WeNoM:Handbuch" target="_blank">
                                Hilfe
                                <span class="sr-only">(öffnet in einem neuen Tab)</span>
                            </a>
                        </nav>
                    </div>
                    <img src="../Images/MSB_NRW_Logo.svg" alt="">
                </footer>
            </div>
        </template>
    </SvwsUiAppLayout>
</template>

<style scoped>
    div.component {
        @apply ui-bg-chalk ui-bg-cover ui-w-full ui-h-full ui-flex ui-flex-col ui-justify-between
    }


    footer {
        @apply ui-bg-white ui-p-8 ui-flex ui-flex-col-reverse md:ui-flex-row ui-gap-8 ui-text-lg ui-items-center
    }

    footer .disclaimer {
        @apply ui-flex ui-flex-col ui-gap-2 ui-grow ui-text-center md:ui-text-left ui-text-sm
    }

    footer .disclaimer h3 {
        @apply ui-text-black/75
    }

    footer .disclaimer p {
        @apply ui-text-black/30 ui-max-w-5xl
    }

    footer .disclaimer nav {
        @apply ui-flex ui-flex-row ui-items-center ui-gap-3 ui-justify-center md:ui-justify-start
    }

    footer .disclaimer nav a {
        @apply
        ui-text-black/30 hover:ui-text-black/75
        focus-visible:ui-outline focus-visible:ui-text-black/75 ui-outline-offset-2 ui-outline-2 ui-outline-black
        ui-underline
        ui-rounded-sm
    }

    footer img {
        @apply ui-h-20 ui-w-auto
    }
</style>