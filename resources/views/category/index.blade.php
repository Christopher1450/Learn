@extends('layouts.app', ['noMarginTop' => true])

@section('content')
<div class="container" style="padding-top: 20px;padding-bottom: 40px;">
    <h2 class="mb-3">Kategori</h2>

    <form action="{{ route('category.index') }}" method="POST" class="mb-4 d-flex gap-2">
        @csrf
        <input type="text" name="name" placeholder="Category Name" class="form-control w-25" required>
        <button class="btn btn-primary">Submit</button>
    </form>

    <div style="max-height: 520px; overflow-y: auto;" class="border rounded p-2 bg-white">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
            <tr>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($categories as $cat)
    <tr>
        <td>
            @if(request('edit') == $cat->id)
                <form action="{{ route('category.update', $cat->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $cat->name }}" class="form-control" required>
            @else
                {{ $cat->name }}
            @endif
        </td>
        <td class="d-flex align-items-center gap-2">
        @if(request('edit') == $cat->id)
                    <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                    <a href="{{ route('category.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                </form>
            @else
                <a href="{{ route('category.index', ['edit' => $cat->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm mt-3">Delete</button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
