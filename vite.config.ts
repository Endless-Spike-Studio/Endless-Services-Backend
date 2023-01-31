import {defineConfig} from "vite";
import legacy from "@vitejs/plugin-legacy";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import {NaiveUiResolver} from "unplugin-vue-components/resolvers";
import {resolve as resolvePath} from "path";
import {short as loadShortCommitHash} from "git-rev-sync";

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
        vue(),
        vueJsx(),
        laravel({
            input: ['resources/scripts/main.ts']
        }),
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
    ],
    define: {
        a52e49caf88aba98dca4defc66229ba39fd8edef: " '" + loadShortCommitHash() + "' ".trim()
    }
});
