import {defineConfig} from "vite";
import legacy from "@vitejs/plugin-legacy";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import {NaiveUiResolver} from "unplugin-vue-components/resolvers";
import InertiaLayoutApplier from "./resources/scripts/vite/layout";
import {resolve as resolvePath} from "path";

export default defineConfig({
    build: {
        minify: 'terser',
        terserOptions: {
            mangle: true
        }
    },
    resolve: {
        alias: {
            '@': resolvePath(__dirname, 'resources')
        }
    },
    plugins: [
        laravel({
            input: ['resources/scripts/main.ts']
        }),
        vue(),
        vueJsx(),
        InertiaLayoutApplier(),
        AutoImport({
            imports: [
                'vue',
                {
                    'naive-ui': [
                        'useDialog',
                        'useMessage',
                        'useNotification',
                        'useLoadingBar'
                    ]
                }
            ]
        }),
        Components({
            resolvers: [
                NaiveUiResolver()
            ]
        }),
        legacy({
            targets: [
                'defaults',
                'chrome 52',
                'ie >= 11'
            ]
        })
    ]
});
