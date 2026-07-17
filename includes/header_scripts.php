<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- AOS Animation CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

<!-- Tailwind Config -->
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          sans: ['Inter', 'sans-serif'],
        },
        colors: {
          pink: {
            light: '#fce4ec',
            mid: '#f48fb1',
            main: '#e8437a',
            dark: '#880e4f',
          },
          bg: '#ffffff',
          text: '#222222',
          'text-muted': '#717171',
        },
        boxShadow: {
          'soft': '0 2px 16px rgba(0,0,0,0.08)',
          'hover': '0 8px 30px rgba(0,0,0,0.12)',
        }
      }
    }
  }
</script>

<style>
:root {
    color-scheme: light;
    --bg: #ffffff;
    --surface: #ffffff;
    --surface-strong: #f8f4f9;
    --text: #222222;
    --muted: #717171;
    --border: rgba(145, 92, 170, 0.12);
    --card: #ffffff;
    --shadow: 0 24px 80px -42px rgba(0,0,0,0.16);
    --accent: #e8437a;
    --pink-main: #e8437a;
    --accent-dark: #880e4f;
    --accent-light: #fce4ec;
}

html.theme-dark {
    color-scheme: dark;
    --bg: #000000;
    --surface: #0a0a0a;
    --surface-strong: #121212;
    --text: #ffffff;
    --muted: #d1d5db;
    --border: rgba(255, 255, 255, 0.14);
    --card: #111111;
    --shadow: 0 24px 80px -42px rgba(0,0,0,0.9);
    --accent: #f472b6;
    --pink-main: #f472b6;
    --accent-dark: #d53f8c;
    --accent-light: #fde2f8;
}

body {
    background-color: var(--bg);
    color: var(--text);
}

body, input, textarea, select, button {
    font-family: 'Inter', sans-serif;
}

.bg-body { background-color: var(--bg); }
.bg-surface { background-color: var(--surface); }
.bg-surface-strong { background-color: var(--surface-strong); }
.border-surface { border-color: var(--border); }
.text-text { color: var(--text); }
.text-text-muted { color: var(--muted); }
.text-muted { color: var(--muted); }
.text-accent { color: var(--accent); }
.text-accent-dark { color: var(--accent-dark); }
.border-accent { border-color: var(--accent); }
.border-accent-light { border-color: var(--accent-light); }
.bg-accent { background-color: var(--accent); }
.bg-accent-light { background-color: var(--accent-light); }
.shadow-soft { box-shadow: var(--shadow); }

button, input, textarea, select {
    transition: all 200ms ease;
}

select {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg fill='%23717171' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5H7z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 20px;
}

.auth-panel,
.profile-card,
.form-card {
    background-color: var(--card);
    border: 1px solid var(--border);
    color: var(--text);
    box-shadow: var(--shadow);
}

.form-input,
.form-textarea,
.form-select {
    background-color: var(--surface-strong);
    color: var(--text);
    border: 1px solid var(--border);
}

.form-input::placeholder,
.form-textarea::placeholder {
    color: var(--muted);
}

.field-label {
    color: var(--muted);
}

html.theme-dark .bg-white,
html.theme-dark .bg-gray-50,
html.theme-dark .bg-pink-light,
html.theme-dark .bg-\[\#fff8fb\] {
    background-color: var(--surface) !important;
}
html.theme-dark .text-pink-dark {
    color: #ffffff !important;
}
html.theme-dark .text-pink-main {
    color: #fda4c8 !important;
}
html.theme-dark .border-gray-100,
html.theme-dark .border-gray-200,
html.theme-dark .border-pink-light {
    border-color: var(--border) !important;
}
html.theme-dark body,
html.theme-dark .text-text,
html.theme-dark .text-text-muted,
html.theme-dark .text-muted,
html.theme-dark .field-label,
html.theme-dark p,
html.theme-dark span,
html.theme-dark h1,
html.theme-dark h2,
html.theme-dark h3,
html.theme-dark h4,
html.theme-dark h5,
html.theme-dark h6,
html.theme-dark label,
html.theme-dark a {
    color: #ffffff !important;
}
html.theme-dark a:hover {
    color: #fda4c8 !important;
}

.icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 9999px;
}
</style>

<script>
function applyTheme(theme) {
  const isDark = theme === 'dark';
  document.documentElement.classList.toggle('theme-dark', isDark);
  localStorage.setItem('eventixTheme', theme);

  const icon = document.querySelector('[data-theme-icon]');
  const toggle = document.querySelector('[data-theme-toggle]');
  if (icon) {
    icon.innerHTML = isDark
      ? '<svg viewBox="0 0 24 24" class="w-4 h-4 fill-current"><path d="M12 3v2m0 14v2m9-9h-2M5 12H3m6.5-6.5-1.4-1.4m9.8 9.8-1.4-1.4M8.1 15.9l-1.4 1.4m9.8-9.8-1.4 1.4M12 8.5A3.5 3.5 0 1 1 8.5 12 3.5 3.5 0 0 1 12 8.5Z"/></svg>'
      : '<svg viewBox="0 0 24 24" class="w-4 h-4 fill-current"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>';
  }
  if (toggle) {
    toggle.setAttribute('aria-pressed', String(isDark));
  }
}
function initTheme() {
  const saved = localStorage.getItem('eventixTheme');
  const theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  applyTheme(theme);
}
function toggleTheme() {
  applyTheme(document.documentElement.classList.contains('theme-dark') ? 'light' : 'dark');
}
function setLanguage(lang) {
  const params = new URLSearchParams(window.location.search);
  params.set('lang', lang);
  window.location.search = params.toString();
}
window.addEventListener('DOMContentLoaded', initTheme);
</script>