@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Kategori</h2>

    <form action="{{ route('category.update', $category->id) }}" method="POST" class="d-flex gap-2">
        @csrf
        @method('PUT')
        <input type="text" name="name" class="form-control w-0" value="{{ $category->name }}" required>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('category.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
