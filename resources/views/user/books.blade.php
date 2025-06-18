
@extends('layouts.app')

@section('styles')
<style>
  .book-cover {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 4px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    transition: transform 0.3s;
  }

  .book-cover:hover {
    transform: scale(1.05);
  }

  .books-table td {
    vertical-align: middle;
  }

  .book-title {
    font-weight: 600;
    color: #333;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 5px;
  }

  .book-author {
    font-size: 0.9rem;
    color: #666;
  }

  .due-date {
    font-weight: 500;
  }

  .overdue {
    color: #dc3545;
    font-weight: bold;
  }

  .action-buttons {
    display: flex;
    gap: 8px;
  }

  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
  }

  .empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.7;
  }
</style>
@endsection

@section('content')
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fas fa-check-circle me-2"></i> {{ session()->get('message') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="container py-4">
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
          <h4 class="m-0"><i class="fas fa-book me-2" style="color: var(--primary-color);"></i> My Borrowed Books</h4>
          <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-search me-1"></i> Browse More Books
          </a>
        </div>
        <div class="card-body">
          @if($user->booksBorrowed()->count() == 0)
            <div class="empty-state">
              <i class="fas fa-books"></i>
              <h3>No Books Borrowed Yet</h3>
              <p class="mb-4">You haven't borrowed any books yet. Browse our collection to find your next read!</p>
              <a href="{{ route('books.index') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i> Explore Books
              </a>
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover books-table">
                <thead class="table-light">
                  <tr>
                    <th scope="col" width="90">Cover</th>
                    <th scope="col">Book Details</th>
                    <th scope="col" width="150">Due Date</th>
                    <th scope="col" width="180">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($user->booksBorrowed as $book)
                    @php
                      // Calculate if book is overdue
                      $dueDate = auth()->user()->getExpirydate($book->id);
                      $isOverdue = \Carbon\Carbon::parse($dueDate)->isPast();
                    @endphp
                    <tr>
                      <td>
                        <a href="{{ route('books.show', $book->id) }}">
                          @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}" alt="{{ $book->title }}" class="book-cover">
                          @else
                            <img src="{{ asset('images/default-book.jpg') }}" alt="{{ $book->title }}" class="book-cover">
                          @endif
                        </a>
                      </td>
                      <td>
                        <div class="book-title">{{ $book->title }}</div>
                        <div class="book-author">
                          @if(empty($book->writtenBy))
                            <span class="text-muted">Anonymous</span>
                          @else
                            {{ $book->writtenBy['name'] }}
                          @endif
                        </div>
                        @if($book->file)
                          <span class="badge bg-info text-white mt-2">
                            <i class="fas fa-file-pdf me-1"></i> PDF Available
                          </span>
                        @endif
                      </td>
                      <td>
                        <div class="due-date {{ $isOverdue ? 'overdue' : '' }}">
                          @if($isOverdue)
                            <i class="fas fa-exclamation-circle me-1"></i>
                          @else
                            <i class="fas fa-calendar-alt me-1"></i>
                          @endif
                          {{ \Carbon\Carbon::parse($dueDate)->format('M d, Y') }}
                        </div>
                        @if($isOverdue)
                          <div class="small text-danger mt-1">Overdue</div>
                        @elseif(\Carbon\Carbon::parse($dueDate)->diffInDays(\Carbon\Carbon::now()) <= 2)
                          <div class="small text-warning mt-1">Due soon</div>
                        @endif
                      </td>
                      <td>
                        <div class="action-buttons">
                          <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-info-circle me-1"></i> Details
                          </a>
                          @if($book->file)
                            <a href="{{ route('books.view', $book->id) }}" class="btn btn-primary btn-sm">
                              <i class="fas fa-book-open me-1"></i> Read
                            </a>
                          @endif
                          <form action="{{ route('books.return', $book->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm">
                              <i class="fas fa-undo-alt me-1"></i> Return
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                                    @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection