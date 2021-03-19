/* eslint-disable global-require */

const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: {
        enable: false, // We're doing purge as part of the build process
    },
    theme: {
        extend: {
            colors: {
                'aqua-haze': '#eef5f3',
                'aqua-haze-2': '#e4ebe9',
                'aqua-island': '#99dbca',
                'caribbean-green': '#00bf8f',
                jade: '#00a67c',
                'deep-sea': '#008c69',
                'aqua-deep': '#005c45',
                'spring-wood': '#f6f2ee',
                champagne: '#fae8d8',
                'gold-sand': '#e6b894',
                whiskey: '#d89c6d',
                meteor: '#cf640e',
                'pumpkin-skin': '#b7580c',
                'rich-gold': '#9f4d0b',
                'totem-pole': '#9f290b',
                geyser: '#d6e0e0',
                submarine: '#bbc8c8',
                'blue-smoke': '#788480',
                'nandor-light': '#626f6c',
                nandor: '#545f5c',
                'mine-shaft': '#313131',
                'mine-shaft-dark': '#232323',
                'cod-gray': '#171717',
                'lightest-red': '#eeadad',
                'lighter-red': '#ee908f',
                'light-red': '#ca5153',
                red: '#870f12',
                'dark-red': '#540a0c',
            },
            fontFamily: {},
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        a: {
                            color: theme('colors.pumpkin-skin'),
                            textDecoration: 'underline',
                            '&:hover': {
                                color: theme('colors.totem-pole'),
                            }
                        },
                        'ul>li>:first-child': {
                            marginTop: 0,
                            marginBottom: 0,
                        },
                        'ul>li>:last-child': {
                            marginTop: 0,
                            marginBottom: 0,
                        },
                    }
                }
            }),
        },
    },
    variants: {},
    plugins: [
        require('@tailwindcss/ui'),
        require('@tailwindcss/aspect-ratio'),
        // Customize: https://github.com/tailwindlabs/tailwindcss-typography#customization
        // https://github.com/tailwindlabs/tailwindcss-typography/blob/master/src/styles.js
        require('@tailwindcss/typography'),
    ],
};
