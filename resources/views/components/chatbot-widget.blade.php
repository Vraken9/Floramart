<!-- resources/views/components/chatbot-widget.blade.php -->

<div x-data="chatbot()" class="fixed bottom-6 right-6 z-50 font-sans">
    <!-- Chat Button -->
    <button 
        @click="toggleChat"
        class="w-14 h-14 bg-[#7c4959] hover:bg-[#5d3642] text-white rounded-full flex items-center justify-center shadow-lg transform transition hover:scale-110 focus:outline-none focus:ring-4 focus:ring-[#7c4959]/50"
        x-show="!isOpen"
        x-transition.scale.origin.bottom.right
    >
        <i class="fa-solid fa-comments text-2xl"></i>
    </button>

    <!-- Chat Window -->
    <div 
        x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-90"
        class="bg-white w-80 sm:w-96 rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200 mb-2"
        style="height: 500px; max-height: 80vh;"
        x-cloak
    >
        <!-- Header -->
        <div class="bg-[#7c4959] text-white p-4 flex justify-between items-center shadow-md z-10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-robot text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg leading-tight">FloraBot</h3>
                    <p class="text-xs text-white/80">Asisten Ahli Bunga Anda</p>
                </div>
            </div>
            <button @click="toggleChat" class="text-white/80 hover:text-white transition-colors focus:outline-none">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 rounded-full bg-[#7c4959]/10 flex items-center justify-center flex-shrink-0 mt-1">
                    <i class="fa-solid fa-robot text-[#7c4959] text-sm"></i>
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-sm text-gray-800">
                    <p>Halo! Saya FloraBot 👋</p>
                    <p class="mt-1">Saya bisa membantu Anda merekomendasikan bunga untuk berbagai acara seperti Wisuda, Pernikahan, Ulang Tahun, atau Duka Cita. Ada yang bisa saya bantu hari ini?</p>
                </div>
            </div>

            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex items-start justify-end space-x-2' : 'flex items-start space-x-2'">
                    <!-- AI Avatar (only if role is ai) -->
                    <div x-show="msg.role === 'ai'" class="w-8 h-8 rounded-full bg-[#7c4959]/10 flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fa-solid fa-robot text-[#7c4959] text-sm"></i>
                    </div>

                    <!-- Message Bubble -->
                    <div 
                        :class="msg.role === 'user' ? 'bg-[#7c4959] text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-sm' : 'bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-sm text-gray-800'"
                        x-html="formatMessage(msg.content)"
                    ></div>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div x-show="isLoading" class="flex items-start space-x-2">
                <div class="w-8 h-8 rounded-full bg-[#7c4959]/10 flex items-center justify-center flex-shrink-0 mt-1">
                    <i class="fa-solid fa-robot text-[#7c4959] text-sm"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 flex space-x-2 items-center">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <form @submit.prevent="sendMessage" class="flex items-center space-x-2">
                <input 
                    type="text" 
                    x-model="newMessage" 
                    placeholder="Ketik pertanyaan Anda..." 
                    class="flex-1 bg-gray-100 border-transparent focus:bg-white focus:border-[#7c4959] focus:ring-2 focus:ring-[#7c4959]/20 rounded-full px-4 py-2 text-sm transition-all"
                    :disabled="isLoading"
                >
                <button 
                    type="submit" 
                    class="w-10 h-10 bg-[#7c4959] text-white rounded-full flex items-center justify-center hover:bg-[#5d3642] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#7c4959] disabled:opacity-50"
                    :disabled="isLoading || newMessage.trim() === ''"
                >
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
            <div class="text-center mt-2">
                <span class="text-[10px] text-gray-400">Didukung oleh AI Gemini</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatbot', () => ({
            isOpen: false,
            newMessage: '',
            isLoading: false,
            messages: [],
            
            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    setTimeout(() => this.scrollToBottom(), 100);
                }
            },
            
            scrollToBottom() {
                const container = document.getElementById('chat-messages');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            },
            
            formatMessage(text) {
                // Simple markdown formatting for bold and line breaks
                let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                formatted = formatted.replace(/\n/g, '<br>');
                return formatted;
            },
            
            async sendMessage() {
                if (this.newMessage.trim() === '') return;
                
                const userText = this.newMessage;
                this.messages.push({ role: 'user', content: userText });
                this.newMessage = '';
                this.isLoading = true;
                this.scrollToBottom();
                
                try {
                    const response = await fetch('/api/chatbot/ask', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: userText })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        this.messages.push({ role: 'ai', content: data.reply });
                    } else {
                        this.messages.push({ role: 'ai', content: "Maaf, terjadi kesalahan: " + (data.error || 'Server tidak merespons.') });
                    }
                } catch (error) {
                    console.error("Chatbot Error:", error);
                    this.messages.push({ role: 'ai', content: "Gagal terhubung ke server. Silakan coba lagi nanti." });
                } finally {
                    this.isLoading = false;
                    setTimeout(() => this.scrollToBottom(), 100);
                }
            }
        }));
    });
</script>
