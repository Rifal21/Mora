<footer class="">
    <div class="container mx-auto px-6 py-10">
        <p class="text-center text-sm text-gray-600">
            &copy; {{ date('Y') }} Mora Finance. All rights reserved.
        </p>
    </div>
</footer>
@auth
    <!-- Floating Chat Button -->
    <button id="chatToggleBtn"
        class="fixed xl:bottom-6 bottom-24 right-6 w-16 h-16 rounded-full bg-white shadow-lg border border-gray-200 flex items-center justify-center hover:shadow-xl transition z-50">
        <img src="{{ asset('assets/images/mora icon bg.png') }}" alt="Mora Icon" class="w-10 h-10">
    </button>
@endauth

<!-- Popup Chat -->
{{-- <div id="chatPopup"
    class="fixed bottom-28 right-6 w-80 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden hidden z-[999] opacity-0 translate-y-4 transition-all duration-300">
    <div class="bg-blue-500 text-white px-4 py-3 font-semibold flex justify-between items-center">
        <span>Mora AI Assistant</span>
        <button id="chatCloseBtn" class="text-white hover:text-yellow-300">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Chat Body -->
    <div id="chatMessages" class="p-4 h-72 overflow-y-auto bg-gray-50 space-y-2">
        <div class="flex justify-start">
            <div class="bg-blue-100 text-gray-800 p-2 rounded-xl rounded-bl-none max-w-[80%] text-sm">
                Hai {{ auth()->user()->name ?? 'Sobat Mora' }} 👋<br>
                Ada yang bisa Mora bantu hari ini? <br> Silahkan chat untuk konsultasi ataupun catat keuanganmu.
            </div>
        </div>
    </div>

    <!-- Chat Input -->
    <div class="border-t flex bg-white">
        <input type="text" id="chatInput" placeholder="Ketik pesan..."
            class="flex-1 p-2 text-sm outline-none rounded-bl-2xl">
        <button id="chatSendBtn" class="bg-blue-500 text-white px-4 hover:bg-blue-600 transition rounded-br-2xl">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div> --}}

<!-- Popup Chat -->
<div id="chatPopup"
    class="fixed bottom-28 right-6 w-80 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden hidden z-[999] opacity-0 translate-y-4 transition-all duration-300">
    <div class="bg-blue-500 text-white px-4 py-3 font-semibold flex justify-between items-center">
        <span>Mora AI Assistant</span>
        <form action="{{ route('clear.chat') }}" method="POST" class="absolute top-3 right-12">
            @csrf
            <button class="text-xs text-red-500 hover:text-red-600 bg-white p-1 rounded"><i
                    class="fa-solid fa-trash"></i></button>
        </form>
        <button id="chatCloseBtn" class="text-white hover:text-yellow-300">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Chat Body -->
    <div id="chatMessages" class="p-4 h-72 overflow-y-auto bg-gray-50 space-y-2">

        {{-- Pesan pembuka --}}
        <div class="flex justify-start">
            <div class="bg-blue-100 text-gray-800 p-2 rounded-xl rounded-bl-none max-w-[80%] text-sm">
                Hai {{ auth()->user()->name ?? 'Sobat Mora' }} 👋<br>
                Ada yang bisa Mora bantu hari ini? <br>
                Silahkan chat untuk konsultasi ataupun catat keuanganmu.
            </div>
        </div>

        {{-- Render chat history dari session --}}
        @if (session('ai_chat_history'))
            @foreach (session('ai_chat_history') as $chat)
                {{-- Pesan user --}}
                <div class="flex justify-end">
                    <div
                        class="bg-blue-500 text-white p-2 rounded-xl rounded-br-none max-w-[80%] text-sm whitespace-pre-line break-words">
                        {{ $chat['user'] }}
                    </div>
                </div>

                {{-- Balasan AI --}}
                <div class="flex justify-start">
                    <div
                        class="bg-gray-200 text-gray-800 p-2 rounded-xl rounded-bl-none max-w-[80%] text-sm whitespace-pre-line break-words">
                        {!! $chat['reply'] !!}
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Chat Input -->
    <div class="border-t flex bg-white">
        <input type="text" id="chatInput" placeholder="Ketik pesan..."
            class="flex-1 p-2 text-sm outline-none rounded-bl-2xl">
        <button id="chatSendBtn" class="bg-blue-500 text-white px-4 hover:bg-blue-600 transition rounded-br-2xl">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>


<!-- Loading Animation Styles -->
<style>
    .loading-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        height: 20px;
    }

    .loading-dots span {
        width: 6px;
        height: 6px;
        background-color: #9ca3af;
        border-radius: 50%;
        animation: blink 1s infinite ease-in-out;
    }

    .loading-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .loading-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes blink {

        0%,
        80%,
        100% {
            opacity: 0.2;
        }

        40% {
            opacity: 1;
        }
    }
</style>

<!-- Script -->
<script>
    const chatToggleBtn = document.getElementById('chatToggleBtn');
    const chatPopup = document.getElementById('chatPopup');
    const chatCloseBtn = document.getElementById('chatCloseBtn');
    const chatSendBtn = document.getElementById('chatSendBtn');
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');

    // Toggle Popup Chat
    chatToggleBtn.addEventListener('click', () => {
        const isHidden = chatPopup.classList.contains('hidden');
        if (isHidden) {
            chatPopup.classList.remove('hidden');
            setTimeout(() => {
                chatPopup.classList.remove('opacity-0', 'translate-y-4');
            }, 10);
        } else {
            chatPopup.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => {
                chatPopup.classList.add('hidden');
            }, 300);
        }
    });

    chatCloseBtn.addEventListener('click', () => {
        chatPopup.classList.add('opacity-0', 'translate-y-4');
        setTimeout(() => {
            chatPopup.classList.add('hidden');
        }, 300);
    });

    // Kirim Pesan
    chatSendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    function appendMessage(text, type = 'user') {
        const wrapper = document.createElement('div');
        wrapper.className = `flex ${type === 'user' ? 'justify-end' : 'justify-start'}`;

        const bubble = document.createElement('div');
        bubble.className = `p-2 rounded-xl text-sm max-w-[80%] whitespace-pre-line break-words ${type === 'user'
            ? 'bg-blue-500 text-white rounded-br-none'
            : 'bg-gray-200 text-gray-800 rounded-bl-none'
            }`;
        bubble.innerHTML = text;

        wrapper.appendChild(bubble);
        chatMessages.appendChild(wrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return bubble;
    }

    async function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        appendMessage(message, 'user');
        chatInput.value = '';

        // Tampilkan animasi loading
        const loadingWrapper = document.createElement('div');
        loadingWrapper.className = 'flex justify-start';
        loadingWrapper.innerHTML = `
            <div class="bg-gray-200 text-gray-800 p-2 rounded-xl rounded-bl-none max-w-[80%] text-sm">
                <div class="loading-dots">
                    <span></span><span></span><span></span>
                </div>
            </div>`;
        chatMessages.appendChild(loadingWrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        try {
            const response = await fetch("{{ route('ai.chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message
                })
            });

            const data = await response.json();

            // Hapus loading sebelum menampilkan balasan
            chatMessages.removeChild(loadingWrapper);
            appendMessage(data.reply ?? 'Tidak ada respon.', 'ai');
        } catch (error) {
            chatMessages.removeChild(loadingWrapper);
            appendMessage('⚠️ Terjadi kesalahan, coba lagi nanti.', 'ai');
        }
    }
</script>
