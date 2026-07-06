/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./templates/**/*.html",
    "./patterns/**/*.php",
    "./inc/**/*.php",
    "./*.php"
  ],
  theme: {
    extend: {
      colors: {
        sagicc: '#0052FF', // Azul Sagicc
        secondary: '#0F172A', // Oscuro secundario
      },
      fontFamily: {
        sans: ['Inter', 'Outfit', 'sans-serif'],
      }
    },
  },
  plugins: [],
}
