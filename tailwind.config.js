/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",              // Scan semua file PHP di root (index, login, dll)
    "./admin/**/*.php",     // Scan folder admin
    "./partials/**/*.php",  // Scan folder partials
    "./assets/**/*.js"      // Scan file JS (buat class yang digenerate JS)
  ],
  theme: {
    extend: {
      colors: {
        goldflare: "#D4AF37",
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
      }
    },
  },
  plugins: [],
};