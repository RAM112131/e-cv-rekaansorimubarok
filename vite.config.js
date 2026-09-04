import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
        react(),
    ],
    assetsInclude: ['**/*.glb'],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    optimizeDeps: {
        include: [
            'react',
            'react-dom',
            'three',
            '@react-three/fiber',
            '@react-three/drei',
            '@react-three/rapier'
        ],
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    if (id.includes('node_modules')) {
                        if (id.includes('three') || id.includes('@react-three')) {
                            return 'three-vendors';
                        }
                        if (id.includes('react')) {
                            return 'vendor';
                        }
                    }
                }
            }
        },
        minify: 'esbuild',
        cssCodeSplit: true
    }
});