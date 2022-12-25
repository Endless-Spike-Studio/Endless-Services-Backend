/** @type {import('tailwindcss').Config} */

module.exports = {
    darkMode: 'media',
    content: [
        './resources/**/*.{js,jsx,ts,tsx,vue,php}'
    ],
    theme: {
        extend: {
            fontFamily: {
                'sans': ['Harmony Sans SC', 'Harmony Sans', ...require('tailwindcss/defaultTheme').fontFamily.sans]
            },
            transitionProperty: {
                'width': 'width'
            }
        }
    },
    plugins: [],
    corePlugins: {
        preflight: false
    }
}
