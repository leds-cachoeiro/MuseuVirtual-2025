import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({

    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/css/menu_site.blade.css',
      'resources/css/homeBlade.css',
      'resources/css/rochasBlade.css',
      'resources/css/mineraisBlade.css',
      'resources/css/jazidasBlade.css',
      'resources/css/EspecificoBlade.css',
'resources/js/menu.js',
      'resources/js/home.js',
      'resources/js/baselayout.js',
		    'resources/js/rochas.js',
		    'resources/js/rochaemineral_especificos.js',
		    'resources/js/mineralespecifico.js'
	    ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    base: '/',
    build: {
        outDir: 'public/build',
    },
});
