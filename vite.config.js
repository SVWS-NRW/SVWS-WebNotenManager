import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
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
    ],

    resolve: {
        dedupe: ["vue"],
        alias: {
            '@': '/resources/js',
            // "@svws-nrw/svws-ui": "@/ui-components",
        }
    }
});
