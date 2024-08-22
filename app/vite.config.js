

import { defineConfig } from 'vite';
import php from 'vite-plugin-php';
import sass from 'sass';

export default defineConfig({
  plugins: [php()],
  css: {
    preprocessorOptions: {
      scss: {
        implementation: sass,
      },
    },
  },
  build: {
    manifest: true,
    outDir: "dist",
    rollupOptions: {
      input: ["./js/main.js", "./js/burger.js", "./js/game.js", "./js/lore.js"],
      plugins: [php()],
    },
  },
  
  logLevel: 'info', 
  
});