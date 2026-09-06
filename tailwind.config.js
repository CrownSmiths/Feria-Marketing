/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./**/*.php",     // Scans all PHP files in your root and subdirectories
        "./src/**/*.php", // Or restrict it to your templates/views folder
        "./*.html",
        "./*.js"
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
