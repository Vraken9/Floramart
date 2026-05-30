<!-- resources/views/components/accessibility-filters.blade.php -->

<style>
    /* Dynamic CSS for Accessibility Mode */
    /* Hanya aktif ketika tag html memiliki class 'a11y-active' */
    
    /* Tombol Login/Register Navbar */
    .a11y-active .btn-a11y-auth-login {
        border: 2px solid #9ca3af !important;
        font-weight: 800 !important;
    }
    .a11y-active .btn-a11y-auth-register {
        border: 2px solid #7c4959 !important;
        font-weight: 800 !important;
    }

    /* Tombol Pesan Sekarang di Katalog */
    .a11y-active .btn-a11y-pesan {
        border: 3px solid #166534 !important; /* hijau gelap */
        text-transform: uppercase !important;
        text-decoration: underline !important;
        text-underline-offset: 4px !important;
        font-weight: 900 !important;
        letter-spacing: 0.05em !important;
    }

    /* Penekanan untuk Dashboard Admin & Owner */
    .a11y-active .btn-a11y-admin {
        border: 3px solid #111827 !important; /* warna gelap tegas */
        background-color: #ffffff !important; /* latar putih bersih */
        color: #111827 !important; /* warna teks sangat gelap */
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }
    
    .a11y-active .text-a11y-admin {
        color: #111827 !important;
        font-weight: 900 !important;
        text-decoration: underline !important;
    }

    .a11y-active .input-a11y-admin {
        border: 3px solid #111827 !important;
        background-color: #ffffff !important;
        color: #111827 !important;
        font-weight: 900 !important;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05) !important;
    }
    
    .a11y-active .input-a11y-admin::placeholder {
        color: #4b5563 !important;
        font-weight: 700 !important;
    }

    /* ========================================================= */
    /* ATURAN GLOBAL HIGH CONTRAST (Lebih Halus & Terarah)       */
    /* ========================================================= */
    
    /* 1. Tingkatkan Kontras Teks (Tidak Ekstrem, Hanya Memperjelas) */
    .a11y-active .text-gray-400,
    .a11y-active .text-gray-500, 
    .a11y-active .text-gray-600 {
        color: #374151 !important; /* gray-700, kontras lebih baik tapi tidak hitam pekat */
        font-weight: 600 !important;
    }

    /* 2. Global Links (Hanya Garis Bawah, Pertahankan Warna Asli) */
    .a11y-active a:not([class*="btn-"]):not([class*="bg-"]):not(.navbar-brand) {
        text-decoration: underline !important;
        text-underline-offset: 4px !important;
        text-decoration-thickness: 2px !important;
    }

    /* 3. Perjelas Input Form (Batas Lebih Tegas) */
    .a11y-active input:not([type="checkbox"]):not([type="radio"]), 
    .a11y-active select, 
    .a11y-active textarea {
        border: 2px solid #4b5563 !important; /* abu-abu gelap, bukan hitam murni */
    }

    /* ========================================================= */
    /* ATURAN GLOBAL DARK MODE (Inversi Otomatis ke Semua View)  */
    /* ========================================================= */
    html.dark body {
        background-color: #111827 !important; /* bg-gray-900 */
        color: #f3f4f6 !important; /* text-gray-100 */
    }
    
    /* Konversi Background Terang menjadi Gelap */
    html.dark .bg-white { background-color: #1f2937 !important; border-color: #374151 !important; }
    html.dark .bg-gray-50, html.dark .bg-gray-100 { background-color: #111827 !important; }
    html.dark .bg-gray-200 { background-color: #374151 !important; }
    
    /* Konversi Teks Gelap menjadi Terang */
    html.dark .text-gray-900, html.dark .text-gray-800, html.dark .text-black { color: #f9fafb !important; }
    html.dark .text-gray-700 { color: #e5e7eb !important; }
    html.dark .text-gray-600 { color: #d1d5db !important; }
    html.dark .text-gray-500 { color: #9ca3af !important; }

    /* Konversi Garis Tepi (Borders) */
    html.dark .border-gray-100, html.dark .border-gray-200, html.dark .border-gray-300 { border-color: #374151 !important; }
    html.dark .border-gray-400 { border-color: #4b5563 !important; }

    /* Konversi Elemen Input/Select/Textarea */
    html.dark input:not([type="checkbox"]):not([type="radio"]), 
    html.dark textarea, 
    html.dark select {
        background-color: #374151 !important;
        border-color: #4b5563 !important;
        color: #f3f4f6 !important;
    }
    html.dark input::placeholder, html.dark textarea::placeholder { color: #9ca3af !important; }

    /* Pengecualian Khusus (Tombol Aksen FloraMart & Aksesibilitas) */
    html.dark .bg-\[\#7c4959\] { background-color: #7c4959 !important; }
    html.dark .text-\[\#7c4959\] { color: #d4ccc0 !important; } /* Lebih terang di mode gelap */
    html.dark .border-\[\#7c4959\] { border-color: #926a7a !important; }
</style>

<script>
    // Global Filter Logic (High Contrast UI Mode)
    function toggleAccessibilityMode() {
        const html = document.documentElement;
        const icon1 = document.getElementById('a11yModeIcon');
        const icon2 = document.getElementById('a11yModeIconDashboard');
        
        let isA11yMode = false;

        if (html.classList.contains('a11y-active')) {
            html.classList.remove('a11y-active');
            localStorage.setItem('floramart_colorblind_type', 'Normal');
            isA11yMode = false;
        } else {
            html.classList.add('a11y-active');
            localStorage.setItem('floramart_colorblind_type', 'HighContrast');
            isA11yMode = true;
        }

        // Simpan ke database jika user login (Asynchronous)
        @auth
        fetch('/accessibility-mode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ accessibility_mode: isA11yMode })
        }).catch(err => console.error(err));
        @endauth
    }

    // Auto-apply immediately (blocking script in head/top body to prevent FOUC)
    (function() {
        // Cek dari DB jika login
        @auth
            @if(auth()->user()->accessibility_mode)
                if(!localStorage.getItem('floramart_colorblind_type') || localStorage.getItem('floramart_colorblind_type') === 'Normal') {
                    localStorage.setItem('floramart_colorblind_type', 'HighContrast');
                }
            @endif
        @endauth

        const saved = localStorage.getItem('floramart_colorblind_type');
        if (saved && saved !== 'Normal') {
            document.documentElement.style.visibility = 'hidden'; // Hide momentarily
            document.addEventListener('DOMContentLoaded', () => {
                document.documentElement.classList.add('a11y-active');
                document.documentElement.style.visibility = ''; // Show
            });
        }
    })();

    // Dark Mode Logic
    function toggleDarkMode() {
        const html = document.documentElement;
        const icon = document.getElementById('darkModeIcon');
        
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('floramart_theme', 'light');
            if (icon) {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        } else {
            html.classList.add('dark');
            localStorage.setItem('floramart_theme', 'dark');
            if (icon) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        }
    }

    (function() {
        const theme = localStorage.getItem('floramart_theme');
        if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.addEventListener('DOMContentLoaded', () => {
                const icon = document.getElementById('darkModeIcon');
                if (icon) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            });
        }
    })();
</script>
