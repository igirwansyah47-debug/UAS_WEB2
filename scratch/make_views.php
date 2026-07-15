<?php
$dirs = [
    'resources/views/property',
    'resources/views/room',
    'resources/views/facility',
];
foreach($dirs as $dir) {
    if(!is_dir(__DIR__ . '/../' . $dir)) mkdir(__DIR__ . '/../' . $dir, 0777, true);
}

// Property views
file_put_contents(__DIR__ . '/../resources/views/property/index.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('property.create') }}">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$properties as \$property)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td>{{ \$property->name }}</td>
                        <td>{{ \$property->city }}</td>
                        <td>{{ \$property->address }}</td>
                        <td>
                            <a href="{{ route('property.show', \$property) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('property.edit', \$property) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('property.destroy', \$property) }}" method="post" class="d-inline">
                                @csrf @method('delete')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/property/create.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('property.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
            <div class="mb-3"><label>Address</label><input type="text" name="address" class="form-control" required></div>
            <div class="mb-3"><label>City</label><input type="text" name="city" class="form-control" required></div>
            <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/property/edit.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('property.update', \$property) }}" method="post" enctype="multipart/form-data">
            @csrf @method('put')
            <div class="mb-3"><label>Name</label><input type="text" name="name" value="{{ \$property->name }}" class="form-control" required></div>
            <div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ \$property->description }}</textarea></div>
            <div class="mb-3"><label>Address</label><input type="text" name="address" value="{{ \$property->address }}" class="form-control" required></div>
            <div class="mb-3"><label>City</label><input type="text" name="city" value="{{ \$property->city }}" class="form-control" required></div>
            <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/property/show.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>{{ \$property->name }}</h4>
        <p>{{ \$property->description }}</p>
        <p>{{ \$property->address }}, {{ \$property->city }}</p>
        @if(\$property->image) <img src="{{ asset('storage/'.\$property->image) }}" width="200"> @endif
        <hr>
        <h5>Rooms</h5>
        <ul>
            @foreach(\$property->rooms as \$room)
            <li>{{ \$room->room_type }} - Rp {{ number_format(\$room->price, 0, ',', '.') }}</li>
            @endforeach
        </ul>
    </div>
</x-app>
EOT);

// Room views
file_put_contents(__DIR__ . '/../resources/views/room/index.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('room.create') }}">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$rooms as \$room)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td>{{ \$room->property->name }}</td>
                        <td>{{ \$room->room_type }}</td>
                        <td>{{ \$room->price }}</td>
                        <td>{{ \$room->available_stock }} / {{ \$room->quantity }}</td>
                        <td>
                            <a href="{{ route('room.show', \$room) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('room.edit', \$room) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('room.destroy', \$room) }}" method="post" class="d-inline">
                                @csrf @method('delete')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/room/create.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('room.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Property</label>
                <select name="property_id" class="form-control" required>
                    @foreach(\$properties as \$p) <option value="{{ \$p->id }}">{{ \$p->name }}</option> @endforeach
                </select>
            </div>
            <div class="mb-3"><label>Type</label><input type="text" name="room_type" class="form-control" required></div>
            <div class="mb-3"><label>Price</label><input type="number" name="price" class="form-control" required></div>
            <div class="mb-3"><label>Quantity</label><input type="number" name="quantity" class="form-control" required></div>
            <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
            
            <div class="mb-3">
                <label>Facilities</label>
                @foreach(\$facilities as \$f)
                <div>
                    <label><input type="checkbox" name="facilities[]" value="{{ \$f->id }}"> {{ \$f->name }}</label>
                </div>
                @endforeach
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/room/edit.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('room.update', \$room) }}" method="post" enctype="multipart/form-data">
            @csrf @method('put')
            <div class="mb-3">
                <label>Property</label>
                <select name="property_id" class="form-control" required>
                    @foreach(\$properties as \$p) 
                        <option value="{{ \$p->id }}" {{ \$room->property_id == \$p->id ? 'selected' : '' }}>{{ \$p->name }}</option> 
                    @endforeach
                </select>
            </div>
            <div class="mb-3"><label>Type</label><input type="text" name="room_type" value="{{ \$room->room_type }}" class="form-control" required></div>
            <div class="mb-3"><label>Price</label><input type="number" name="price" value="{{ \$room->price }}" class="form-control" required></div>
            <div class="mb-3"><label>Quantity</label><input type="number" name="quantity" value="{{ \$room->quantity }}" class="form-control" required></div>
            <div class="mb-3"><label>Available Stock</label><input type="number" name="available_stock" value="{{ \$room->available_stock }}" class="form-control" required></div>
            <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
            
            <div class="mb-3">
                <label>Facilities</label>
                @foreach(\$facilities as \$f)
                <div>
                    <label>
                        <input type="checkbox" name="facilities[]" value="{{ \$f->id }}" 
                        {{ in_array(\$f->id, \$room->facilities->pluck('id')->toArray()) ? 'checked' : '' }}> 
                        {{ \$f->name }}
                    </label>
                </div>
                @endforeach
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/room/show.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <h4>{{ \$room->room_type }} - {{ \$room->property->name }}</h4>
        <p>Price: Rp {{ number_format(\$room->price, 0, ',', '.') }}</p>
        <p>Stock: {{ \$room->available_stock }} / {{ \$room->quantity }}</p>
        @if(\$room->image) <img src="{{ asset('storage/'.\$room->image) }}" width="200"> @endif
        <hr>
        <h5>Facilities</h5>
        <ul>
            @foreach(\$room->facilities as \$facility)
            <li><i class="{{ \$facility->icon }}"></i> {{ \$facility->name }}</li>
            @endforeach
        </ul>
    </div>
</x-app>
EOT);

// Facility views
file_put_contents(__DIR__ . '/../resources/views/facility/index.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('facility.create') }}">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Icon</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$facilities as \$facility)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td>{{ \$facility->name }}</td>
                        <td><i class="{{ \$facility->icon }}"></i> {{ \$facility->icon }}</td>
                        <td>
                            <a href="{{ route('facility.edit', \$facility) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('facility.destroy', \$facility) }}" method="post" class="d-inline">
                                @csrf @method('delete')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/facility/create.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('facility.store') }}" method="post">
            @csrf
            <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label>Icon (e.g. bx-wifi)</label><input type="text" name="icon" class="form-control"></div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>
EOT);

file_put_contents(__DIR__ . '/../resources/views/facility/edit.blade.php', <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('facility.update', \$facility) }}" method="post">
            @csrf @method('put')
            <div class="mb-3"><label>Name</label><input type="text" name="name" value="{{ \$facility->name }}" class="form-control" required></div>
            <div class="mb-3"><label>Icon</label><input type="text" name="icon" value="{{ \$facility->icon }}" class="form-control"></div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app>
EOT);

echo "Views generated.";
