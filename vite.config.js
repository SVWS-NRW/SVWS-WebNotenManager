import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Icons from "unplugin-icons/vite";
import IconsResolver from "unplugin-icons/resolver";
import Components from "unplugin-vue-components/vite";
import * as os from "os";

export default defineConfig({
    server: {
        https: false,
        // host: true,
		host: process.env.LARAVEL_SAIL ? Object.values(os.networkInterfaces()).flat().find(info => info?.internal === false)?.address : undefined,

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
            // "@svws-nrw/svws-ui": "@/ui-components",
        }
    }
});
