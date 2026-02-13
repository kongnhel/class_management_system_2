import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from '@tailwindcss/vite'; 

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    // server: {
    //     host: "0.0.0.0", // Important: Listen on all network interfaces
    //     hmr: {
    //         host: "192.168.171.179", // e.g., '192.168.1.100'
    //         // host: "10.10.17.156", // e.g., '192.168.1.100'
    //         // host: "10.10.14.196", // e.g., '192.168.1.100'
    //     },
    // },
});

// export default defineConfig({
