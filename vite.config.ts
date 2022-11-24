import {defineConfig} from "vite";
import legacy from "@vitejs/plugin-legacy";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import {NaiveUiResolver} from "unplugin-vue-components/resolvers";
import hybridly from "hybridly/vite";

export default defineConfig({
    build: {
        outDir: 'dist',
        minify: 'terser',
        terserOptions: {
            mangle: true
        }
    },
    plugins: [
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
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                }
            }
        }),
        vueJsx(),
        hybridly(),
        legacy({
            targets: [
                'defaults',
                'chrome 52',
                'ie >= 11'
            ],
            additionalLegacyPolyfills: [
                'regenerator-runtime/runtime'
            ]
        }),
        laravel({
            input: ['resources/scripts/main.ts'],
            buildDirectory: 'build/../'
        })
    ]
});
