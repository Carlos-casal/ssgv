/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        primary: {
          // DEFAULT: '#3B82F6', // Azul principal (blue-500)
          DEFAULT: '#00529B', // Azul principal (más oscuro)
          light: '#60A5FA', // Azul claro (blue-400)
          dark: '#1E40AF', // Azul oscuro (blue-800)
        },
        accent: {
          // DEFAULT: '#F97316', // Naranja principal (orange-500)
          DEFAULT: '#FF7900', // Naranja principal (más vibrante)
          light: '#FB923C', // Naranja claro (orange-400)
          dark: '#C2410C', // Naranja oscuro (orange-700)
        },
        // Añadimos colores para éxito, error y aviso que combinen bien
        success: '#10B981', // emerald-500
        error: '#EF4444', // red-500
        warning: '#F59E0B', // amber-500
      },
    },
  },
  plugins: [],
};
