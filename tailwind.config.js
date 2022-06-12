const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    presets: [require("./vendor/wireui/wireui/tailwind.config.js")],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/wireui/wireui/resources/**/*.blade.php",
        "./vendor/wireui/wireui/ts/**/*.ts",
        "./vendor/wireui/wireui/src/View/**/*.php",
        "./vendor/wire-elements/modal/resources/views/*.blade.php",
    ],
    safelist: ["md:max-w-2xl", "lg:max-w-3xl"],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
