import {defineConfig} from 'vite'
// @ts-ignore
import tailwindcss from 'tailwindcss'
import legacy from '@vitejs/plugin-legacy'
import autoprefixer from 'autoprefixer'
import laravel from 'vite-plugin-laravel'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import inertia from './resources/scripts/vite/inertia-layout'

export default defineConfig({
    build: {
        target: 'es6'
    },
    plugins: [
        inertia(),
        vue(),
        vueJsx(),
        legacy({
            targets: ['chrome 52'],
            additionalLegacyPolyfills: ['regenerator-runtime/runtime']
        }),
        laravel({
            postcss: [
                tailwindcss(),
                autoprefixer()
            ]
        })
    ]
});
