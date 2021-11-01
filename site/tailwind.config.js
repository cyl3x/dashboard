const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    purge: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
    darkMode: false, // or 'media' or 'class'
    theme: {
        textShadow: {
            'default': '0 2px 5px rgba(0, 0, 0, 0.5)',
            'md': '2px 2px 3px rgba(0, 0, 0, 0.3);',
            'lg': '0 2px 10px rgba(0, 0, 0, 0.5)',
        },
        colors: {
            transparent: 'transparent',
            black: 'hsl(210, 27%, 10%)',
            white: '#ffffff',
            gray: colors.gray,
            primary: {
                50: 'hsl(202, 100%, 95%)', // lightest
                100: 'hsl(204, 100%, 86%)', // lighter
                200: 'hsl(206, 93%, 73%)',
                300: 'hsl(208, 88%, 62%)',
                400: 'hsl(210, 83%, 53%)', // light
                500: 'hsl(212, 92%, 43%)', // base
                600: 'hsl(214, 95%, 36%)', // dark
                700: 'hsl(215, 96%, 32%)',
                800: 'hsl(216, 98%, 25%)', // darker
                900: 'hsl(218, 100%, 17%)', // darkest
            },
            neutral: {
                50: 'hsl(216, 33%, 97%)',
                100: 'hsl(214, 15%, 91%)',
                200: 'hsl(210, 16%, 82%)',
                300: 'hsl(211, 13%, 65%)',
                400: 'hsl(211, 10%, 53%)',
                500: 'hsl(211, 12%, 43%)',
                600: 'hsl(209, 14%, 37%)',
                700: 'hsl(209, 18%, 30%)',
                800: 'hsl(209, 20%, 25%)',
                900: 'hsl(210, 24%, 16%)',
                1000: 'hsl(212, 25%, 10%)',
            },
            red: {
                50: 'hsl(360, 100%, 95%)',
                100: 'hsl(360, 100%, 87%)',
                200: 'hsl(360, 100%, 80%)',
                300: 'hsl(360, 91%, 69%)',
                400: 'hsl(360, 83%, 62%)',
                500: 'hsl(356, 75%, 53%)',
                600: 'hsl(354, 85%, 44%)',
                700: 'hsl(352, 90%, 35%)',
                800: 'hsl(350, 94%, 28%)',
                900: 'hsl(348, 94%, 20%)',
            },
            green: {
                50: 'hsl(125, 65%, 93%)',
                100: 'hsl(127, 65%, 85%)',
                200: 'hsl(124, 63%, 74%)',
                300: 'hsl(123, 53%, 55%)',
                400: 'hsl(123, 57%, 45%)',
                500: 'hsl(122, 73%, 35%)',
                600: 'hsl(122, 80%, 29%)',
                700: 'hsl(125, 79%, 26%)',
                800: 'hsl(125, 86%, 20%)',
                900: 'hsl(125, 97%, 14%)',
            },
        },
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                sansextra: ['Rubik', '-apple-system', 'BlinkMacSystemFont', '"Helvetica Neue"', '"Roboto"', 'system-ui', 'sans-serif'],
                header: ['"IBM Plex Sans"', '"Roboto"', 'system-ui', 'sans-serif'],
                mono: ['"IBM Plex Mono"', '"Source Code Pro"', 'SourceCodePro', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
            },
            width: {
                88: '22rem',
                84: '21rem',
            },
            maxWidth: {
                'item-4': '91.75rem',
                '400': '100rem',
                '300': '75rem',
                '84': '21rem',
                '88': '22rem',
            },
            blur: {
                xxs: '1px',
                xs: '2px',
            },
            zIndex: {
                '-10': '-10',
            },
            padding: {
                'full': '100%',
            }
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}