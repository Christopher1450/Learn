@extends('layouts.app')

@section('content')
<div class="container">
    <h2>List of Borrowed Books</h2>
    <a href="{{ route('borrowings.create') }}" class="btn btn-primary mb-3">Borrow a New Book</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Book</th>
                <th>Borrowed Date</th>
                <th>Return Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->book->title }}</td>
                <td>{{ $borrowing->borrowed_at }}</td>
                <td>{{ $borrowing->returned_at ?? 'Not Returned' }}</td>
                <td>
                    @if(!$borrowing->returned_at)
                        <form action="{{ route('borrowings.return', $borrowing->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Return</button>
                        </form>
                    @else
                        <span class="badge bg-secondary">Returned</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
