module.exports = {
    theme: {
        extend: {
            keyframes: {
                successform: {
                    "0%": { backgroundColor: "rgb(74, 222, 128)" }, // bg-green-300
                    "100%": { backgroundColor: "transparent" },
                },
            },
            animation: {
                "success-form": "successform 2s ease-in-out forwards",
            },
        },
    },
}
