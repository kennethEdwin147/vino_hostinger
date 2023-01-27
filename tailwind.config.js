/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./template/*/*.html"],
  theme: {
    extend: {},
  },
  plugins: [require('@tailwindcss/typography')],
}

/* npx tailwindcss -i ./public/css/input.css -o ./public/css/tailwind11.css --watch --minify
 */