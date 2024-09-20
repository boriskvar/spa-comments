import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/main.js'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        proxy: {
            '/api': {
                target: 'http://spa-comments', // Используйте базовый URL
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, '/api'), // Убедитесь, что /api передается
            }
        }
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
