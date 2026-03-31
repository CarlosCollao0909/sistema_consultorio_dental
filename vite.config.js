import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [tailwindcss()],
    publicDir: false,
    base: '/build/',
    build: {
        outDir: './public/build',
        emptyOutDir: true,
        rollupOptions: {
            input: {
                main: './src/js/main.js',
                account: './src/js/account.js',
                admin: './src/js/admin.js',
            },
            output: {
                entryFileNames: 'js/[name].js',
                assetFileNames: (assetInfo) => {
                    const name = assetInfo.names?.[0] || '';
                    if (name.endsWith('.woff2') || name.endsWith('.woff') || name.endsWith('.ttf')) {
                        return 'fonts/[name].[ext]';
                    }
                    return 'css/[name].[ext]';
                }

            }

        }
    }
})
