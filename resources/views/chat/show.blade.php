<x-app-layout>
    <div class="mx-auto max-w-5xl space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Chat</p>
                <h1 class="text-3xl font-semibold text-[#333333]">Conversation with {{ $seller->name }}</h1>
            </div>
            <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center rounded-xl border border-[#E9E5DD] px-5 py-3 text-sm font-semibold text-[#5A4A3A] hover:border-wood hover:text-wood">Back</a>
        </div>

        <div class="rounded-[28px] bg-white p-6 shadow-soft">
            <div class="space-y-4">
                @foreach($messages as $message)
                    @php
                        $isSender = $message->sender_id === auth()->id();
                    @endphp
                    <div class="flex {{ $isSender ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-[28px] px-5 py-4 text-sm shadow-soft {{ $isSender ? 'bg-[#F5E8DA] text-[#533C1F]' : 'bg-[#F7F3EF] text-[#5A4A3A]' }}">
                            <p>{{ $message->message }}</p>
                            <p class="mt-3 text-xs text-[#8B5E3C]">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <form action="{{ route('chat.store', $seller) }}" method="POST" class="rounded-[28px] bg-white p-6 shadow-soft">
            @csrf
            <div>
                <label class="block text-sm font-medium text-[#5A4A3A]">New message</label>
                <textarea name="message" rows="4" class="form-control mt-2" placeholder="Write your message..."></textarea>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="btn-primary">Send message</button>
            </div>
        </form>
    </div>
</x-app-layout>
