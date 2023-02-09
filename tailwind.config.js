/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./template/*/*.html", "./node_modules/flowbite/**/*.js"],
  theme: {
    extend: {},
  },
  plugins: [require('@tailwindcss/typography')],
}

/* npx tailwindcss -i ./public/css/input.css -o ./public/css/tailwindww2.css --watch --minify
 */