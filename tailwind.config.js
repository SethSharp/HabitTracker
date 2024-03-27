import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import colors from 'tailwindcss/colors'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: colors.teal[50],
                    100: colors.teal[100],
                    200: colors.teal[200],
                    300: colors.teal[300],
                    400: colors.teal[400],
                    500: colors.teal[500],
                    600: colors.teal[600],
                    700: colors.teal[700],
                    800: colors.teal[800],
                    900: colors.teal[900],
                },
                secondary: {
                    100: colors.gray[100],
                    200: colors.gray[200],
                    300: colors.gray[300],
                    400: colors.gray[400],
                    500: colors.gray[500],
                    600: colors.gray[600],
                    700: colors.gray[700],
                    800: colors.gray[800],
                    900: colors.gray[900],
                },
            },
        },
    },

    plugins: [forms],
}
