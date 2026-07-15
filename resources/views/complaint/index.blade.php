<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
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
                    @foreach ($complaints as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $c->tenant->name }}</td>
                        <td>{{ $c->room->property->name }} - {{ $c->room->room_type }}</td>
                        <td>{{ $c->subject }}</td>
                        <td>
                            @if($c->status == 'open') <span class="badge bg-danger">Open</span>
                            @elseif($c->status == 'in_progress') <span class="badge bg-warning">In Progress</span>
                            @elseif($c->status == 'resolved') <span class="badge bg-success">Resolved</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('complaint.show', $c) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>