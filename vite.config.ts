import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/ts/app.ts'],
            refresh: true,
        }),
    ],
    server: {
        host: true,
        port: 3001,
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/ts'),
        },
    },
})
