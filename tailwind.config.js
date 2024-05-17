/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "./assets/**/*.js",
      "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
        boxShadow: {
            '3xl': '0 35px 60px -15px rgba(0, 0, 0, 0.3)',
        },
        colors: {
            fond: '#111827'
        }
    },
  },
  plugins: [],
}