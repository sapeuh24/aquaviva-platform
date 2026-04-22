/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html',
    './src/**/*.{vue,js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        // Aquaviva green palette (matches prototype CSS variables)
        'av-green': {
          900: '#0F2D1A',
          800: '#1A3D2B',
          700: '#1F5035',
          600: '#246040',
          500: '#2D7A50',
          400: '#3A9A64',
          300: '#5CB87A',
          200: '#A8D9BA',
          100: '#DCF0E4',
          50:  '#F2FAF5',
        },
        // Aquaviva gray palette
        'av-gray': {
          800: '#1C2925',
          700: '#2E3D38',
          600: '#4A5C55',
          500: '#6B7D76',
          400: '#9FADA7',
          300: '#CDD5D0',
          200: '#E4E8E6',
          100: '#F0F2F1',
          50:  '#F8F9F8',
        },
      },
      fontFamily: {
        sans: ['-apple-system', 'BlinkMacSystemFont', "'Segoe UI'", 'system-ui', 'sans-serif'],
      },
      fontSize: {
        '2xs': ['10px', '14px'],
        'xs': ['11.5px', '16px'],
        'sm': ['13px', '18px'],
        'base': ['14px', '20px'],
      },
      width: {
        sidebar: '252px',
      },
      height: {
        topbar: '56px',
      },
      boxShadow: {
        'av-sm': '0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04)',
        'av':    '0 4px 12px rgba(0,0,0,.10), 0 2px 4px rgba(0,0,0,.06)',
        'av-lg': '0 12px 32px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06)',
      },
    },
  },
  plugins: [],
}
