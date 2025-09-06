@extends('layouts.app')
@section('title', 'Chat Detail')
@section('content')

    <div style="width: 100%; max-width: 800px; margin: 0 auto; font-family: sans-serif;">
        <h3 style="margin-bottom: 20px;">
            Chat với {{ $chat->user->name ?? 'N/A' }} ({{ $chat->user->email ?? 'N/A' }})
        </h3>

        <!-- Box chat -->
        <div style="border: 1px solid #ccc; border-radius: 20px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div class="chat-box" style="height: 500px; overflow-y: auto; background: #f0f2f5; padding: 15px;">
                @foreach ($chat->messages as $message)
                    <div
                        style="display:flex; justify-content: {{ $message->sender === 'user' ? 'flex-start' : 'flex-end' }}; margin-bottom:6px;">
                        <div
                            style="
                        background: {{ $message->sender === 'user' ? '#fff' : '#0084ff' }};
                        color: {{ $message->sender === 'user' ? '#000' : '#fff' }};
                        padding: 6px 10px;
                        border-radius: {{ $message->sender === 'user' ? '16px 16px 16px 4px' : '16px 16px 4px 16px' }};
                        max-width: 60%;
                        line-height: 1.4;
                        word-wrap: break-word;
                        white-space: pre-wrap;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                    ">
                            {{ $message->content }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Form nhập tin -->
            <div style="background: #fff; padding: 10px; border-top: 1px solid #ddd;">
                <form id="chatForm" method="POST" action="{{ route('chats.send', $chat->id) }}"
                    style="display:flex; gap:10px;">
                    @csrf
                    <input type="text" name="message" id="messageInput" placeholder="Aa"
                        style="flex:1; border:1px solid #ddd; border-radius:20px; padding:10px 15px; outline:none; font-size:14px;">
                    <button type="submit"
                        style="background:#0084ff; border:none; color:#fff; border-radius:50%; width:40px; height:40px; cursor:pointer; font-size:16px;">
                        ➤
                    </button>
                </form>
            </div>
        </div>

        <a href="{{ route('chats.index') }}"
            style="display:inline-block; margin-top:15px; text-decoration:none; color:#555;">
            ⬅ Quay lại
        </a>
    </div>

    <!-- Echo & Pusher -->
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.14.0/echo.iife.js"></script>

    <script>
        window.Pusher = Pusher;
        const chatId = {{ $chat->id }};
        const chatBox = document.querySelector('.chat-box');

        const echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });
        echo.connector.pusher.connection.bind("connected", () => {
            console.log("✅ Pusher connected");
        });
        const channel = echo.private(`chat.${chatId}`);
        channel.subscribed(() => console.log('✅ Subscribed to private channel chat.' + chatId));
        channel.listen('.NewMessage', (e) => {
            const div = document.createElement('div');
            const isUser = e.message.sender === 'user';
            div.style = `display:flex; justify-content:${isUser ? 'flex-start' : 'flex-end'}; margin-bottom:6px;`;
            div.innerHTML = `
            <div style="
                background: ${isUser ? '#fff' : '#0084ff'};
                color: ${isUser ? '#000' : '#fff'};
                padding: 6px 10px;
                border-radius: ${isUser ? '16px 16px 16px 4px' : '16px 16px 4px 16px'};
                max-width: 60%;
                line-height: 1.4;
                word-wrap: break-word;
                white-space: pre-wrap;
                box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            ">${e.message.content}</div>
        `;
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        });

        chatBox.scrollTop = chatBox.scrollHeight;

        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            if (!message) return;

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message
                })
            }).catch(console.error);

            input.value = '';
        });
    </script>

@endsection
