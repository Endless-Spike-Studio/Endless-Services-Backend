import {defineConfig} from 'vite'
import tailwindcss from 'tailwindcss'
import legacy from '@vitejs/plugin-legacy'
import autoprefixer from 'autoprefixer'
import laravel from 'vite-plugin-laravel'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import inertia from './resources/scripts/vite/inertia-layout'
import eslintPlugin from 'vite-plugin-eslint'

export default defineConfig({
    plugins: [
        inertia(),
        vue(),
        vueJsx(),
        legacy({
            targets: [
                'defaults',
                'chrome 52',
                'ie >= 11'
            ],
            additionalLegacyPolyfills: ['regenerator-runtime/runtime']
        }),
        laravel({
            postcss: [
                tailwindcss(),
                autoprefixer()
            ]
        }),
        eslintPlugin({
            exclude: '**'
        })
    ]
})
