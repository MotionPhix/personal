import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.ts',
      // publicDirectory: 'public_html',
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
  // build: {
  //   outDir: 'public_html/build',
  // },
  server: {
    hmr: {
      host: 'localhost',
    },
  },

  resolve: {
    dedupe: ['@inertiajs/vue3']
  }
});
