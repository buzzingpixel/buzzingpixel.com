/* eslint-disable global-require */

const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: {
        enable: false, // We're doing purge as part of the build process
    },
    theme: {
        extend: {
            colors: {},
            fontFamily: {},
        },
    },
    variants: {},
    plugins: [
        require('@tailwindcss/ui'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography'),
    ],
};
