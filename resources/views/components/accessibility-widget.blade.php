<!-- resources/views/components/accessibility-widget.blade.php -->

<style>
    @keyframes visioBlink {
        0% { outline-color: #FFD700; box-shadow: 0 0 15px rgba(255, 215, 0, 0.8); }
        50% { outline-color: #FF4500; box-shadow: 0 0 5px rgba(255, 69, 0, 0.5); }
        100% { outline-color: #FFD700; box-shadow: 0 0 15px rgba(255, 215, 0, 0.8); }
    }
    .visioadapt-highlight-target {
        outline: 4px dashed #FFD700 !important;
        outline-offset: 4px !important;
        animation: visioBlink 1.5s infinite !important;
        position: relative !important;
    }
    .visioadapt-tooltip {
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        background-color: #000;
        color: #FFD700;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        white-space: nowrap;
        z-index: 999999;
        pointer-events: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.5);
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
        const elements = document.querySelectorAll('button, a, div, span, label, p, img');
        let firstFoundElement = null;

        targetsArray.forEach(targetObj => {
            let targetText = targetObj.teks;
            let targetLabel = targetObj.label;
            if (!targetText) return;
            
            let foundElement = null;
            const lowerTarget = targetText.toLowerCase();
            
            for (let el of elements) {
                const text = (el.innerText || el.textContent || el.getAttribute('aria-label') || el.alt || '').toLowerCase();
                if (text.includes(lowerTarget)) {
                    if (!foundElement || foundElement.contains(el) || el.tagName === 'BUTTON' || el.tagName === 'A') {
                        foundElement = el;
                    }
                }
            }

            if (foundElement) {
                foundElement.classList.add("visioadapt-highlight-target");
                const tooltip = document.createElement("div");
                tooltip.className = "visioadapt-tooltip";
                tooltip.textContent = targetLabel || targetText;
                
                try {
                    foundElement.appendChild(tooltip);
                } catch(e) {}

                if (!firstFoundElement) firstFoundElement = foundElement;
            }
        });

        if (firstFoundElement) {
            // firstFoundElement.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Disabled to prevent jumping
        }
    }

    function removeAllHighlights() {
        const targets = document.querySelectorAll('.visioadapt-highlight-target');
        targets.forEach(el => {
            el.classList.remove('visioadapt-highlight-target');
            const tooltips = el.querySelectorAll('.visioadapt-tooltip');
            tooltips.forEach(t => t.remove());
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
        overlay.style.position = "fixed";
        overlay.style.bottom = "80px";
        overlay.style.left = "20px";
        overlay.style.maxWidth = "400px";
        overlay.style.backgroundColor = success ? "#0f172a" : "#7f1d1d"; // Lebih gelap untuk kontras tinggi
        overlay.style.color = "#ffffff";
        overlay.style.padding = "20px";
        overlay.style.borderRadius = "12px";
        overlay.style.boxShadow = "0 10px 25px rgba(0,0,0,0.5)";
        overlay.style.zIndex = "999999";
        overlay.style.borderLeft = success ? "6px solid #10b981" : "6px solid #b91c1c";

        const title = document.createElement("h4");
        title.innerHTML = '<i class="fa-solid fa-robot"></i> Asisten Aksesibilitas';
        title.style.margin = "0 0 8px 0";
        title.style.color = success ? "#10b981" : "#ffffff";
        overlay.appendChild(title);

        const text = document.createElement("p");
        text.innerHTML = displayText.replace(/\n/g, '<br>');
        text.style.fontSize = "15px"; // Diperbesar
        text.style.fontWeight = "600"; // Dipertebal
        text.style.letterSpacing = "0.5px"; // Jarak antar huruf diperlebar
        text.style.margin = "0 0 16px 0";
        text.style.lineHeight = "1.6";
        overlay.appendChild(text);

        const closeBtn = document.createElement("button");
        closeBtn.textContent = "Tutup Panduan";
        closeBtn.style.padding = "8px 16px";
        closeBtn.style.backgroundColor = success ? "#10b981" : "#b91c1c";
        closeBtn.style.color = "white";
        closeBtn.style.border = "none";
        closeBtn.style.borderRadius = "6px";
        closeBtn.style.cursor = "pointer";
        closeBtn.style.fontSize = "14px"; // Diperbesar
        closeBtn.style.fontWeight = "bold";
        closeBtn.style.width = "100%"; // Membentang penuh agar mudah diklik
        closeBtn.onclick = () => {
            overlay.remove();
            removeAllHighlights();
        };
        overlay.appendChild(closeBtn);

        document.body.appendChild(overlay);
    }
</script>
