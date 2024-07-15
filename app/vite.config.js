import { defineConfig } from "vite";
import php from "vite-plugin-php";

export default defineConfig({
  plugins: [php()],
  build: {
    manifest: true,
    outDir: "dist",
    rollupOptions: {
      input: ["./js/main.js", "./js/burger.js", "./js/game.js", "./js/lore.js"],

      plugins: [php()],
    },
  },
});
