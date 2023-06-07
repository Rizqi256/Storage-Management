@extends('layouts.app')

@section('content')
<div class="container">
    <form method="GET" action="{{ route('files.index') }}" class="search-form">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
        <button type="submit">Search</button>
    </form>
    <h1>Inventaris</h1>
    <a href="{{ route('files.create') }}" class="btn btn-primary">Add New File</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($files as $file)
            <tr>
                <td>{{ $file->id }}</td>
                <td>{{ $file->name }}</td>
                <td>{{ $file->description }}</td>
                <td>{{ $file->quantity }}</td>
                <td>
                    @if($file->photo)
                        <img src="{{ Storage::url($file->photo) }}" alt="{{ $file->name }}" width="100">
                    @else
                        No photo
                    @endif
                </td>
                <td>
                    <a href="{{ route('files.edit', $file->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('files.destroy', $file->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    
                    {{ $files->links() }}
                    <div class="user-photo">
                        <img src="{{ asset('storage/photos/' ) }}" alt="Uploaded photo">
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection