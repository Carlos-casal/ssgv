import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import react from "@vitejs/plugin-react";
import path from "path";

export default defineConfig({
    plugins: [
        react(),
        symfonyPlugin({
            stimulus: true
        }),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./assets"),
            // SOLUCIÓN DE ERROR: Elimina errores de Webpack loaders en entorno Vite
            "./webpack/loader!@symfony/stimulus-bridge/controllers.json": path.resolve(__dirname, "./assets/controllers.json"),
            "@symfony/stimulus-bridge/controllers.json": path.resolve(__dirname, "./assets/controllers.json"),
        }
    },
    build: {
        manifest: true,
        outDir: "public/build",
        rollupOptions: {
            input: {
                app: "./assets/app.js",
            },
        },
    },
});
