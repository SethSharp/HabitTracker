module.exports = {
	extends: [
        "eslint:recommended",
        "plugin:vue/vue3-recommended",
        "plugin:@typescript-eslint/eslint-recommended",
        "prettier"
    ],
    plugins: ["unused-imports"],
    rules: {
        "vue/component-tags-order": [
            "error",
            {
                order: ["script", "template", "style"],
            },
        ],
        "vue/multi-word-component-names": "off",
        "vue/component-api-style": ["error", ["script-setup"]],
        "vue/component-name-in-template-casing": "error",
        // "vue/block-lang": [
        //   "error",
        //   {
        //     script: { lang: "ts" },
        //   },
        // ],
        "vue/define-macros-order": "error",
        "vue/no-unused-refs": "error",
        "vue/no-useless-v-bind": "error",
        "vue/no-v-html": "off",
        "vue/require-default-prop": "off",
        'vue/valid-v-slot': ['error', {
            allowModifiers: true,
        }],

        "tailwindcss/no-custom-classname": "off",

        "no-undef": "off",
        "no-console": "error"
    },
    parser: "vue-eslint-parser",
	parserOptions: {
        parser: "@typescript-eslint/parser",
	},
}
