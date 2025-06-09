@extends('layouts.admin')

@section('title', 'Chỉnh sửa FAQ')

@section('content')
    <div class="container">
        <h1 class="mb-4">Frequently Asked Questions</h1>

        <div class="mb-3">
            <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">Add New FAQ</a>
        </div>

        @if($faqs->isEmpty())
            <p>No FAQs available.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $faq)
                        <tr>
                            <td>{{ $faq->question }}</td>
                            <td>{{ $faq->answer }}</td>
                            <td>
                                <a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $faqs->links() }}
        @endif
    </div>