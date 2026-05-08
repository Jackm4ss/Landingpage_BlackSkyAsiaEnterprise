import { defineConfig } from 'vite'
import path from 'path'
import tailwindcss from '@tailwindcss/vite'
import react from '@vitejs/plugin-react'
import { ViteImageOptimizer } from 'vite-plugin-image-optimizer'


function figmaAssetResolver() {
  return {
    name: 'figma-asset-resolver',
    resolveId(id) {
      if (id.startsWith('figma:asset/')) {
        const filename = id.replace('figma:asset/', '')
        return path.resolve(__dirname, 'src/assets', filename)
      }
    },
  }
}

export default defineConfig({
  plugins: [
    figmaAssetResolver(),
    // The React and Tailwind plugins are both required for Make, even if
    // Tailwind is not being actively used – do not remove them
    react(),
    tailwindcss(),
    ViteImageOptimizer({
      logStats: true,
      ansiColors: true,
      includePublic: true,
      cache: true,
      cacheLocation: path.resolve(__dirname, 'node_modules/.cache/vite-image-optimizer'),
      png: {
        quality: 92,
      },
      jpeg: {
        quality: 86,
        mozjpeg: true,
      },
      jpg: {
        quality: 86,
        mozjpeg: true,
      },
      webp: {
        quality: 86,
      },
      avif: {
        quality: 72,
      },
      svg: {
        multipass: true,
        plugins: [
          {
            name: 'preset-default',
            params: {
              overrides: {
                removeViewBox: false,
                cleanupIds: {
                  minify: false,
                  remove: false,
                },
              },
            },
          },
          'sortAttrs',
        ],
      },
    }),
  ],
  resolve: {
    alias: {
      // Alias @ to the src directory
      '@': path.resolve(__dirname, './src'),
    },
  },

  // File types to support raw imports. Never add .css, .tsx, or .ts files to this.
  assetsInclude: ['**/*.svg', '**/*.csv'],
})
