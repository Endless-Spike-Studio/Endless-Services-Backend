import {defineConfig} from "vite";
import legacy from "@vitejs/plugin-legacy";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import {NaiveUiResolver} from "unplugin-vue-components/resolvers";
import hybridlyImports from "hybridly/auto-imports";
import hybridlyResolver from "hybridly/resolver";
import {resolve as resolvePath} from "path";
import hybridly from "hybridly/vite";
import run from "vite-plugin-run";

export default defineConfig({
    build: {
        outDir: 'dist',
        minify: 'terser',
        terserOptions: {
            mangle: true
        }
    },
    esbuild: {
        jsxFactory: 'h',
        jsxFragment: 'Fragment'
    },
    resolve: {
        alias: {
            '@': resolvePath(__dirname, 'resources')
        }
    },
    plugins: [
        AutoImport({
            imports: [
                'vue',
                hybridlyImports,
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
                NaiveUiResolver(),
                hybridlyResolver()
            ],
            dirs: ['./resources/views/components'],
            directoryAsNamespace: true
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
        hybridly({
            layout: {
                directory: resolvePath(__dirname, 'resources/views/layouts')
            }
        }),
        run({
            name: 'generate typescript',
            run: ['php', 'artisan', 'typescript:transform'],
            condition: (file) => ['Data.php', 'Enums'].some(kw => {
                return file.includes(kw);
            })
        }),
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
