import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [tailwindcss()],
    publicDir: false,
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
                assetFileNames: 'css/[name].[ext]',
            }
        }
    }
})
