<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>{{ $complaint->subject }}</h4>
        <p><strong>Tenant:</strong> {{ $complaint->tenant->name }}</p>
        <p><strong>Kamar:</strong> {{ $complaint->room->property->name }} - {{ $complaint->room->room_type }}</p>
        <p><strong>Status:</strong> {{ $complaint->status }}</p>
        <p><strong>Tanggal:</strong> {{ $complaint->created_at->format('d M Y H:i') }}</p>
        <hr>
        <h5>Deskripsi</h5>
        <p>{{ $complaint->description }}</p>
        @if($complaint->image)
            <img src="{{ asset('storage/'.$complaint->image) }}" style="max-width:300px;">
        @endif
        
        @if(Auth::user()->role === 'owner')
        <hr>
        <form action="{{ route('complaint.update', $complaint) }}" method="post">
            @csrf @method('put')
            <div class="input-group">
                <select name="status" class="form-select">
                    <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button class="btn btn-primary">Update Status</button>
            </div>
        </form>
        @endif
    </div>
</x-app>