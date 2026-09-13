/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Poppins"', '"Segoe UI"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                // Charte graphique LET'S TALK ABOUT (LTA)
                lta: {
                    primary: '#A62957', // Titres, liens actifs, elements de marque
                    secondary: '#BF2A70', // Accents, hover, elements d'appel a l'action forts
                    button: '#A66F93', // Fond des boutons
                    'button-hover': '#8F5A7E',
                    dark: '#2F2440', // Navbar / Sidebar / encadrement d'images et mini-articles
                    'dark-light': '#3E3153',
                    blush: '#BF8484', // Accents doux, badges, fonds de section alternes
                    cream: '#FBF6F8', // Fond de page general (tres leger, chaleureux)
                },
            },
            boxShadow: {
                card: '0 10px 25px -10px rgba(47, 36, 64, 0.35)',
            },
        },
    },
    plugins: [],
};
