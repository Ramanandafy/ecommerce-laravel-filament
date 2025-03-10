import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Http/**/*.php', // Optionnel si tu utilises des composants Laravel
        './vendor/filament/**/*.blade.php', // Ajoute cette ligne si tu utilises Filament
        './node_modules/preline/dist/*.js', // Ajoute cette ligne pour Preline
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        require('preline/plugin'), // 🔥 Ajoute Preline ici !
    ],
};
