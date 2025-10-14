@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-20 lg:mt-28 max-w-2xl p-4">
        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white p-4 text-center font-bold text-lg">
                💬 Mora AI — Konsultasi Keuangan
            </div>

            <!-- Chat Box -->
            <div id="chat-box" class="p-4 h-[70vh] overflow-y-auto space-y-4 bg-gray-50">
                <div class="flex flex-col justify-center items-center text-center text-gray-500 mt-10">
                    <img src="{{ asset('assets/images/moraaii.png') }}" class="w-full h-40 object-cover mb-3 opacity-90"
                        alt="">
                    <p class="text-sm leading-relaxed">
                        Mulai percakapan dengan <b>Mora AI</b> 👋<br>
                        Konsultasikan semua tentang keuanganmu dengan tenang. <br>
                        @if (auth()->user()->profile->user_type === 'free')
                            Kamu memiliki kuota chat :
                            <span class="font-semibold text-red-500">{{ auth()->user()->profile->quota_ai }}x</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Chat Input -->
            <form id="chat-form" class="flex border-t bg-white">
                <input type="text" id="message" name="message" placeholder="Tanyakan tentang keuanganmu..."
                    class="flex-1 border-none p-3 focus:ring-0 focus:outline-none text-gray-700" required>
                <button type="submit"
                    class="bg-blue-500 text-white px-5 hover:bg-blue-600 transition flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            addMessage('user', message);
            messageInput.value = '';

            const loading = addMessage('ai',
                '<div class="typing-dots"><span>.</span><span>.</span><span>.</span></div>');

            const res = await fetch('{{ route('ai.chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message
                })
            });

            const data = await res.json();
            loading.innerHTML = parseMarkdown(data.reply);
            chatBox.scrollTop = chatBox.scrollHeight;
        });

        function addMessage(sender, content) {
            const div = document.createElement('div');
            div.className = sender === 'user' ?
                'flex justify-end' :
                'flex justify-start';

            const bubble = document.createElement('div');
            bubble.className = sender === 'user' ?
                'bg-blue-500 text-white rounded-2xl px-4 py-2 max-w-[80%] shadow-md' :
                'bg-white border border-gray-200 rounded-2xl px-4 py-2 max-w-[80%] shadow-sm prose prose-sm';

            bubble.innerHTML = typeof content === 'string' ? parseMarkdown(content) : content;
            div.appendChild(bubble);
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
            return bubble;
        }

        // Markdown parser sederhana
        function parseMarkdown(text) {
            if (!text) return '';
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // bold
                .replace(/\*(.*?)\*/g, '<em>$1</em>') // italic
                .replace(/`(.*?)`/g, '<code>$1</code>') // inline code
                .replace(/^- (.*$)/gim, '<li>$1</li>') // list
                .replace(/\n/g, '<br>') // newline
                .replace(/\|(.+)\|/g, '<span class="inline-block font-mono">$1</span>'); // table fix
        }

        // Animasi titik “mengetik”
        const style = document.createElement('style');
        style.textContent = `
        .typing-dots span {
            animation: blink 1s infinite;
            opacity: 0.3;
            margin: 0 2px;
        }
        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes blink {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        .prose strong { color: #1e3a8a; }
        .prose code {
            background: #f3f4f6;
            padding: 2px 4px;
            border-radius: 4px;
            font-size: 0.85em;
        }
        .prose ul, .prose ol { margin-left: 1em; }
        .prose table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 0.5rem;
        }
        .prose table th, .prose table td {
            border: 1px solid #ddd;
            padding: 4px 8px;
            font-size: 0.85em;
        }
        .prose table th {
            background: #f8fafc;
            font-weight: bold;
        }
    `;
        document.head.appendChild(style);
    </script>
@endsection
