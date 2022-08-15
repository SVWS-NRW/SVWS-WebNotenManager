import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Icons from "unplugin-icons/vite";
import IconsResolver from "unplugin-icons/resolver";
import Components from "unplugin-vue-components/vite";

export default defineConfig({
    server: {
        https: false,
        // host: true,
    },

    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.ts',
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        Components({
            resolvers: [IconsResolver()]
        }),
        Icons()

    ],

    resolve: {
        dedupe: ["vue"],
        alias: {
            '@': '/resources/js',
            "@svws-nrw/svws-ui": "@/svws-ui-components",
        }
    }
});
