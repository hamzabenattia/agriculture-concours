/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      'colors': {
        'primary': '#2674A8',
        'secondary': '#146BA5',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
