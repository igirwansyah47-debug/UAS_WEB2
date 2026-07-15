<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="row h-100" style="min-height: 70vh;">
        <!-- Kontak Sidebar -->
        <div class="col-md-4 border-end bg-white p-0">
            <div class="p-3 border-bottom bg-light">
                <h5 class="mb-0">Daftar Obrolan</h5>
            </div>
            <div class="list-group list-group-flush" style="overflow-y: auto; max-height: 65vh;">
                @forelse($contacts as $contact)
                    <a href="{{ route('chat.index', ['user' => $contact->id]) }}" class="list-group-item list-group-item-action {{ $activeContact && $activeContact->id === $contact->id ? 'active' : '' }}">
                        <div class="d-flex align-items-center">
                            <img src="{{ $contact->avatar ? asset('storage/'.$contact->avatar) : asset('niceadmin/img/noprofil.png') }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0">{{ $contact->name }}</h6>
                                <small class="text-muted">{{ ucfirst($contact->role) }}</small>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted">
                        Belum ada riwayat pesan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Ruang Obrolan -->
        <div class="col-md-8 bg-light d-flex flex-column p-0">
            @if($activeContact)
                <!-- Header Chat -->
                <div class="p-3 border-bottom bg-white d-flex align-items-center">
                    <img src="{{ $activeContact->avatar ? asset('storage/'.$activeContact->avatar) : asset('niceadmin/img/noprofil.png') }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                    <h5 class="mb-0">{{ $activeContact->name }}</h5>
                </div>

                <!-- Box Chat -->
                <div id="chat-box" class="flex-grow-1 p-4" style="overflow-y: auto; max-height: 55vh; background-color: #f8f9fa;">
                    <div class="text-center text-muted"><small>Memuat pesan...</small></div>
                </div>

                <!-- Input Chat -->
                <div class="p-3 border-top bg-white">
                    <form id="chat-form" class="d-flex">
                        @csrf
                        <input type="text" id="chat-input" class="form-control me-2" placeholder="Ketik pesan..." required autocomplete="off">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Kirim</button>
                    </form>
                </div>
            @else
                <div class="d-flex align-items-center justify-content-center h-100 flex-column text-muted" style="min-height: 60vh;">
                    <i class="bi bi-chat-dots" style="font-size: 4rem;"></i>
                    <h4>Pilih obrolan untuk mulai mengirim pesan</h4>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    @if($activeContact)
    <script>
        const activeUserId = {{ $activeContact->id }};
        const currentUserId = {{ Auth::id() }};
        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        
        let lastMessageCount = 0;

        function fetchMessages() {
            fetch(`/chat/${activeUserId}`)
                .then(response => response.json())
                .then(messages => {
                    if (messages.length !== lastMessageCount) {
                        chatBox.innerHTML = '';
                        messages.forEach(msg => {
                            const isMe = msg.sender_id === currentUserId;
                            const alignClass = isMe ? 'text-end' : 'text-start';
                            const bgClass = isMe ? 'bg-primary text-white' : 'bg-white border';
                            const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            
                            const div = document.createElement('div');
                            div.className = `mb-3 ${alignClass}`;
                            div.innerHTML = `
                                <div class="d-inline-block p-2 rounded ${bgClass}" style="max-width: 75%; text-align: left;">
                                    <div>${msg.message}</div>
                                    <small class="${isMe ? 'text-white-50' : 'text-muted'}" style="font-size: 0.7rem;">${time}</small>
                                </div>
                            `;
                            chatBox.appendChild(div);
                        });
                        chatBox.scrollTop = chatBox.scrollHeight;
                        lastMessageCount = messages.length;
                    }
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const text = chatInput.value.trim();
            if(!text) return;

            const formData = new FormData();
            formData.append('message', text);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`/chat/${activeUserId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(msg => {
                chatInput.value = '';
                fetchMessages(); // refresh langsung
            })
            .catch(error => console.error('Error sending message:', error));
        });

        // Polling setiap 3 detik
        fetchMessages();
        setInterval(fetchMessages, 3000);
    </script>
    @endif
    @endpush
</x-app>
