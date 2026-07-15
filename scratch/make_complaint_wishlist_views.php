<?php
$dirs = [
    'resources/views/complaint',
    'resources/views/wishlist',
];
foreach($dirs as $dir) {
    if(!is_dir(__DIR__ . '/../' . $dir)) mkdir(__DIR__ . '/../' . $dir, 0777, true);
}

// Complaint views
file_put_contents(__DIR__ . '/../resources/views/complaint/index.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tenant</th>
                        <th>Kamar</th>
                        <th>Subjek</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$complaints as \$c)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td>{{ \$c->tenant->name }}</td>
                        <td>{{ \$c->room->property->name }} - {{ \$c->room->room_type }}</td>
                        <td>{{ \$c->subject }}</td>
                        <td>
                            @if(\$c->status == 'open') <span class="badge bg-danger">Open</span>
                            @elseif(\$c->status == 'in_progress') <span class="badge bg-warning">In Progress</span>
                            @elseif(\$c->status == 'resolved') <span class="badge bg-success">Resolved</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('complaint.show', \$c) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/complaint/create.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>Komplain Kamar: {{ \$room->property->name }} - {{ \$room->room_type }}</h4>
        <hr>
        <form action="{{ route('complaint.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="room_id" value="{{ \$room->id }}">
            <div class="mb-3">
                <label>Subjek</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label>Lampiran Foto (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button class="btn btn-primary">Kirim Komplain</button>
        </form>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/complaint/show.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>{{ \$complaint->subject }}</h4>
        <p><strong>Tenant:</strong> {{ \$complaint->tenant->name }}</p>
        <p><strong>Kamar:</strong> {{ \$complaint->room->property->name }} - {{ \$complaint->room->room_type }}</p>
        <p><strong>Status:</strong> {{ \$complaint->status }}</p>
        <p><strong>Tanggal:</strong> {{ \$complaint->created_at->format('d M Y H:i') }}</p>
        <hr>
        <h5>Deskripsi</h5>
        <p>{{ \$complaint->description }}</p>
        @if(\$complaint->image)
            <img src="{{ asset('storage/'.\$complaint->image) }}" style="max-width:300px;">
        @endif
        
        @if(Auth::user()->role === 'owner')
        <hr>
        <form action="{{ route('complaint.update', \$complaint) }}" method="post">
            @csrf @method('put')
            <div class="input-group">
                <select name="status" class="form-select">
                    <option value="open" {{ \$complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ \$complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ \$complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button class="btn btn-primary">Update Status</button>
            </div>
        </form>
        @endif
    </div>
</x-app>
EOT);

// Wishlist views
file_put_contents(__DIR__ . '/../resources/views/wishlist/index.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="row">
        @forelse(\$wishlists as \$w)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                @if(\$w->property->image)
                    <img src="{{ asset('storage/'.\$w->property->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ \$w->property->name }}</h5>
                    <p class="card-text">{{ \$w->property->city }}</p>
                    <a href="{{ route('property.show', \$w->property) }}" class="btn btn-primary btn-sm">Lihat Properti</a>
                    <form action="{{ route('wishlist.toggle') }}" method="post" class="d-inline">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ \$w->property->id }}">
                        <button class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p>Wishlist masih kosong.</p>
        </div>
        @endforelse
    </div>
</x-app>
EOT);

echo "Views generated.";
