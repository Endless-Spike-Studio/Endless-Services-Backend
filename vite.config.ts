import {defineConfig} from "vite";
import tailwindcss from "tailwindcss";
import legacy from "@vitejs/plugin-legacy";
import autoprefixer from "autoprefixer";
import laravel from "vite-plugin-laravel";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import {NaiveUiResolver} from "unplugin-vue-components/resolvers";
import inertia from "./resources/scripts/vite/inertia-layout";
import {short} from "git-rev-sync";

export default defineConfig({
    base: 'https://cdn.geometrydashchinese.com/static/website/',
    build: {
        outDir: 'dist',
        minify: 'terser',
        terserOptions: {
            mangle: true
        }
    },
    plugins: [
        inertia(),
        vue(),
        vueJsx(),
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
            ],
            additionalLegacyPolyfills: [
                'regenerator-runtime/runtime'
            ]
        }),
        laravel({
            postcss: [
                tailwindcss(),
                autoprefixer()
            ]
        })
    ],
    define: {
        frontend_git_commit_hash: ` '${short()}' `.trim()
    }
})
