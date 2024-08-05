const colors = require('tailwindcss/colors');

module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './resources/**/*.html', // Added support for HTML files
  ],
  theme: {
    extend: {
      colors: {
        primary: colors.blue[600], // Custom primary color
        secondary: colors.green[500], // Custom secondary color
        accent: colors.red[500], // Custom accent color
      },
      spacing: {
        '128': '32rem', // Custom spacing size
        '144': '36rem', // Custom spacing size
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'], // Custom font
        serif: ['Georgia', 'serif'], // Custom font
      },
      boxShadow: {
        'custom-light': '0 4px 6px rgba(0, 0, 0, 0.1)', // Custom box-shadow
        'custom-dark': '0 4px 6px rgba(0, 0, 0, 0.3)', // Custom box-shadow
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'), // Adds styles for form elements
    require('@tailwindcss/typography'), // Adds styles for typography
    require('@tailwindcss/aspect-ratio'), // Adds aspect-ratio utilities
  ],
}
