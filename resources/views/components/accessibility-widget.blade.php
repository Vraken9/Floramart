<!-- resources/views/components/accessibility-widget.blade.php -->

<style>
    .visioadapt-tooltip {
        /* Styling is now handled by Tailwind utility classes dynamically */
    }
    
    #a11y-widget-container {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 99999;
        font-family: 'Inter', sans-serif;
        transition: top 0.1s ease-out;
    }
    .a11y-faux-fixed {
        position: absolute !important;
        bottom: auto !important;
    }
    #a11y-button {
        width: 50px;
        height: 50px;
        background-color: #7c4959;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        cursor: pointer;
        transition: transform 0.3s, background-color 0.3s;
        border: none;
    }
    #a11y-button:hover {
        transform: scale(1.1);
        background-color: #5d3642;
    }
    #a11y-menu {
        position: absolute;
        bottom: 60px;
        left: 0;
        background: white;
        border-radius: 12px;
        width: 250px;
        padding: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        display: none;
        flex-direction: column;
        gap: 12px;
        border: 1px solid #e5e7eb;
    }
    #a11y-menu.show {
        display: flex;
    }
    .a11y-title {
        font-weight: bold;
        font-size: 14px;
        color: #374151;
        margin: 0 0 4px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .a11y-btn-ai {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: opacity 0.2s;
    }
    .a11y-btn-ai:hover { opacity: 0.9; }
    .a11y-btn-ai:disabled { background: #9ca3af; cursor: not-allowed; }
</style>

<div id="a11y-widget-container">
    <div id="a11y-menu">
        <h4 class="a11y-title"><i class="fa-solid fa-eye"></i> Asisten Aksesibilitas</h4>
        <p style="font-size: 11px; color: #6b7280; margin: 0;">Gunakan AI (Gemini) untuk membaca layar dan menyorot tombol penting untuk Anda.</p>
        <button id="a11y-ai-btn" class="a11y-btn-ai" onclick="analyzeScreenWithAI()">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Analisa Layar dengan AI
        </button>
    </div>
    
    <button id="a11y-button" onclick="document.getElementById('a11y-menu').classList.toggle('show')">
        <i class="fa-solid fa-universal-access fa-lg"></i>
    </button>
</div>

<!-- Library html2canvas for capturing screen -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // AI VISION ANALYSIS
    function analyzeScreenWithAI() {
        const btn = document.getElementById('a11y-ai-btn');
        // Ambil tipe buta warna dari localStorage (yang diset oleh Navbar)
        const type = localStorage.getItem('floramart_colorblind_type') || 'Normal';
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menangkap Layar...';

        document.getElementById('a11y-menu').style.opacity = '0';

        html2canvas(document.body, { useCORS: true, logging: false }).then(canvas => {
            document.getElementById('a11y-menu').style.opacity = '1';
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menganalisa...';
            
            const dataUrl = canvas.toDataURL("image/jpeg", 0.5);
            const base64Data = dataUrl.split(',')[1];

            fetch('/api/accessibility/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    image_base64: base64Data,
                    colorblind_type: type
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                
                if (data.error) {
                    displayOverlay("Terjadi kesalahan: " + data.error, false);
                } else if (data.result) {
                    displayOverlay(data.result, true);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                displayOverlay("Koneksi gagal. Pastikan API tersambung.", false);
                console.error(err);
            });
        });
    }

    // Faux-fixed logic when filter is applied
    function updateWidgetPosition() {
        const widget = document.getElementById('a11y-widget-container');
        const type = localStorage.getItem('floramart_colorblind_type') || 'Normal';
        
        if (type !== 'Normal') {
            widget.classList.add('a11y-faux-fixed');
            const topPos = window.scrollY + window.innerHeight - 70; // 50px widget + 20px bottom
            widget.style.top = topPos + 'px';
        } else {
            widget.classList.remove('a11y-faux-fixed');
            widget.style.top = '';
        }
    }

    window.addEventListener('scroll', updateWidgetPosition);
    window.addEventListener('resize', updateWidgetPosition);
    document.addEventListener('DOMContentLoaded', updateWidgetPosition);
    // Observe localStorage changes if possible, or just click events
    document.addEventListener('click', () => setTimeout(updateWidgetPosition, 100));

    // Highlight Logic
    function highlightDOMElements(targetsArray) {
        if (!targetsArray || targetsArray.length === 0) return;
        // Hanya target elemen yang benar-benar bisa diklik
        const elements = document.querySelectorAll('button, a, input[type="submit"], input[type="button"], [role="button"]');
        let firstFoundElement = null;

        targetsArray.forEach(targetObj => {
            let targetText = targetObj.teks;
            let actionId = targetObj.action_id;
            let targetLabel = targetObj.label;
            
            if (!targetText && !actionId) return;
            
            let foundElement = null;

            // 1. Prioritize finding element by action_id (CSS attribute selector)
            if (actionId) {
                const preciseEls = document.querySelectorAll(`[data-a11y="${actionId}"]`);
                for (let el of preciseEls) {
                    // Task 1: Pastikan elemen target benar-benar terlihat (mengatasi filter mobile yang disembunyikan di desktop)
                    if (el.offsetWidth > 0 || el.offsetHeight > 0 || el.getClientRects().length > 0) {
                        foundElement = el;
                        break;
                    }
                }
            }

            // 2. Fallback to text searching if action_id is not found or not provided
            if (!foundElement && targetText) {
                const lowerTarget = targetText.toLowerCase().trim();
                for (let el of elements) {
                    const text = (el.innerText || el.value || el.getAttribute('aria-label') || el.title || '').toLowerCase().trim();
                    if (text === lowerTarget || text.includes(lowerTarget)) {
                        foundElement = el;
                        if (text === lowerTarget) break; // Perfect match
                    }
                }
            }

            if (foundElement) {
                // Tailwind classes for beautiful highlight ring and Z-Index 60 as requested (Color-Blind safe Blue)
                foundElement.classList.add("ring-4", "ring-blue-500", "ring-offset-2", "ring-offset-white", "animate-pulse", "relative", "z-[60]");
                
                const tooltip = document.createElement("div");
                // Background solid bg-slate-800 text-white p-3 rounded-lg shadow-2xl border border-slate-600 w-max max-w-sm
                tooltip.className = "visioadapt-tooltip absolute bg-slate-800 text-white p-3 rounded-lg shadow-2xl border border-slate-600 text-sm font-semibold z-[100] pointer-events-none w-max max-w-sm";
                tooltip.textContent = targetLabel || targetText;

                // Position based on actionId
                if (actionId === 'auth-area') {
                    tooltip.classList.add('top-full', 'right-0', 'mt-3');
                } else if (actionId === 'filter-area') {
                    // Specific design for filter area requested by user
                    tooltip.className = "visioadapt-tooltip absolute top-full left-0 md:left-1/2 md:-translate-x-1/2 mt-4 z-[100] w-max max-w-md bg-slate-600/95 text-white text-sm p-3 rounded-lg shadow-xl border border-slate-500 pointer-events-none";
                } else {
                    // Default fallback
                    tooltip.classList.add('top-full', 'left-1/2', '-translate-x-1/2', 'mt-3');
                }
                
                try {
                    foundElement.appendChild(tooltip);
                    
                    // Hack to prevent overflow cutoff on product cards
                    const parentCard = foundElement.closest('.overflow-hidden');
                    if (parentCard) {
                        parentCard.classList.remove('overflow-hidden');
                        parentCard.classList.add('visioadapt-temp-overflow');
                    }
                } catch(e) {}

                if (!firstFoundElement) firstFoundElement = foundElement;
            }
        });

        if (firstFoundElement) {
            // firstFoundElement.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Disabled to prevent jumping
        }
    }

    function removeAllHighlights() {
        const targets = document.querySelectorAll('.animate-pulse.ring-blue-500, .animate-pulse.ring-emerald-500');
        targets.forEach(el => {
            el.classList.remove("ring-4", "ring-blue-500", "ring-emerald-500", "ring-offset-2", "ring-offset-white", "animate-pulse", "relative", "z-[50]", "z-[60]", "z-[999998]");
        });
        
        const tooltips = document.querySelectorAll('.visioadapt-tooltip');
        tooltips.forEach(t => t.remove());

        // Restore overflow-hidden to product cards
        document.querySelectorAll('.visioadapt-temp-overflow').forEach(el => {
            el.classList.remove('visioadapt-temp-overflow');
            el.classList.add('overflow-hidden');
        });
    }

    function displayOverlay(message, success = true) {
        removeAllHighlights();
        const existing = document.getElementById("visio-adapt-overlay");
        if (existing) existing.remove();

        let displayText = message;
        let targetsArray = [];

        if (success) {
            try {
                let cleanJson = message.replace(/```json/g, '').replace(/```/g, '').trim();
                const parsed = JSON.parse(cleanJson);
                if (parsed.pesan) displayText = parsed.pesan;
                if (parsed.tombol_penting && Array.isArray(parsed.tombol_penting)) {
                    targetsArray = parsed.tombol_penting;
                }
            } catch (e) {
                console.log("Response bukan JSON murni", e);
            }
        }

        if (targetsArray.length > 0) highlightDOMElements(targetsArray);

        const overlay = document.createElement("div");
        overlay.id = "visio-adapt-overlay";
        // Convert to Tailwind UI classes (Color-blind safe border blue)
        overlay.className = "fixed bottom-[80px] left-5 max-w-sm md:max-w-md z-[999999] p-5 md:p-6 rounded-2xl shadow-2xl backdrop-blur-md border-l-4 " + 
                            (success ? "bg-slate-900 border-blue-500" : "bg-red-900/95 border-red-500");

        const title = document.createElement("h4");
        title.innerHTML = '<i class="fa-solid fa-robot mr-2"></i> Panduan AI';
        title.className = "flex items-center text-lg font-bold mb-3 " + (success ? "text-blue-400" : "text-red-400");
        overlay.appendChild(title);

        const text = document.createElement("div");
        
        // Task 2: Typography chunking with safe colors
        let paragraphs = displayText.split(/\n\n+/);
        let htmlContent = '';
        paragraphs.forEach(p => {
            if (p.trim() !== '') {
                let pText = p.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<span class="text-blue-400 font-bold">$1</span>');
                htmlContent += `<p class="mb-4">${pText}</p>`;
            }
        });
        
        text.innerHTML = htmlContent;
        text.className = "text-slate-200 text-sm md:text-base leading-relaxed mb-5 font-medium"; 
        overlay.appendChild(text);

        const closeBtn = document.createElement("button");
        closeBtn.textContent = "Tutup Panduan";
        closeBtn.className = "w-full py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm flex items-center justify-center " +
            (success ? "bg-transparent border border-slate-500 text-slate-200 hover:bg-white hover:text-slate-900 hover:border-white" : "bg-red-500 text-white hover:bg-red-600");
        
        closeBtn.onclick = () => {
            overlay.remove();
            removeAllHighlights();
        };
        overlay.appendChild(closeBtn);

        document.body.appendChild(overlay);
    }
</script>
