@extends('layouts.app')

@section('styles')
<style>
    :root {
        --primary-color: #11BBAB;
        --primary-color-rgb: 17, 187, 171;
        --secondary-color: #3c4b64;
        --success-color: #28a745;
        --info-color: #17a2b8;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-radius: 0.5rem;
        --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --card-shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --transition-speed: 0.3s;
    }

    .author-profile {
        background-color: #f8f9fa;
        min-height: calc(100vh - 76px);
        padding: 2rem 0;
    }

    .author-header {
        margin-bottom: 2rem;
    }

    .author-card {
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: all var(--transition-speed) ease;
        overflow: hidden;
        border: none;
        margin-bottom: 1.5rem;
        background-color: #fff;
    }

    .author-card:hover {
        box-shadow: var(--card-shadow-hover);
    }

    .author-info {
        padding: 2rem;
    }

    .author-name {
        font-size: 2rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .author-subtitle {
        color: var(--primary-color);
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .author-bio {
        color: #6c757d;
        margin-bottom: 2rem;
        font-size: 1rem;
        line-height: 1.6;
    }

    .author-stat {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(var(--primary-color-rgb), 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.2rem;
    }

    .stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-color);
    }

    .author-avatar-container {
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .author-avatar {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: 5px solid white;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .action-buttons .btn {
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .action-buttons .btn i {
        margin-right: 0.5rem;
    }

    .action-buttons .btn:hover {
        transform: translateY(-2px);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        color: var(--dark-color);
    }

    .section-title i {
        color: var(--primary-color);
        margin-right: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .books-table {
        width: 100%;
    }

    .books-table th {
        background-color: #f8f9fa;
        padding: 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }

    .books-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .books-table tr:last-child td {
        border-bottom: none;
    }

    .books-table tr:hover {
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .status-available {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .status-unavailable {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .btn-view {
        padding: 0.35rem 1rem;
        border-radius: 50px;
        background-color: var(--primary-color);
        color: white;
        font-size: 0.85rem;
        font-weight: 500;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-view:hover {
        background-color: var(--secondary-color);
        color: white;
        transform: translateY(-1px);
    }

    .btn-view i {
        margin-right: 0.3rem;
    }

    .text-end {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    @media (max-width: 991px) {
        .author-info, .author-avatar-container {
            padding: 1.5rem;
        }

        .author-avatar {
            width: 150px;
            height: 150px;
        }

        .author-name {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 767px) {
        .author-card .row {
            flex-direction: column-reverse;
        }

        .author-avatar-container {
            padding-bottom: 0;
        }

        .action-buttons {
            flex-wrap: wrap;
        }
    }
</style>
@endsection

@section('content')
<div class="author-profile">
    <div class="container">
        <!-- Author Header with Actions -->
        <div class="author-header d-flex justify-content-between align-items-center">
            <h1 class="mb-0">
                <i class="fas fa-user-tie text-primary me-2"></i>
                Author Profile
            </h1>

            @auth
                @if(Auth::user()->role == 'admin')
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="authorActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-1"></i> Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="authorActionsDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('authors.edit', $author->id) }}">
                                <i class="fas fa-edit me-2"></i> Edit Author
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{url('/books/create/'.$author->id )}}">
                                <i class="fas fa-plus-circle me-2"></i> Add Book
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('authors.destroy', $author->id) }}" method="POST" id="delete-author-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="dropdown-item text-danger" onclick="confirmDelete()">
                                    <i class="fas fa-trash-alt me-2"></i> Delete Author
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endif
            @endauth
        </div>

        <!-- Author Profile Card -->
        <div class="author-card">
            <div class="row g-0">
                <div class="col-lg-8">
                    <div class="author-info">
                        <h2 class="author-name">{{ $author->name }}</h2>
                        <div class="author-subtitle">Author Profile</div>

                        <p class="author-bio">{{ $author->bio ?: 'No biography available for this author.' }}</p>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Books Written Stat -->
                                <div class="author-stat">
                                    <div class="stat-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <div class="stat-label">Books Written</div>
                                        <div class="stat-value">{{ $author->booksWritten()->count() }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Total Readers Stat -->
                                <div class="author-stat">
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <div class="stat-label">Total Readers</div>
                                        <div class="stat-value">
                                            @php
                                                $totalReaders = 0;
                                                foreach($author->booksWritten as $book) {
                                                    $totalReaders += $book->borrowedBy()->count();
                                                }
                                            @endphp
                                            {{ $totalReaders }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @auth
                            @if(Auth::user()->role == 'admin')
                                <div class="action-buttons">
                                    <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i> Edit Details
                                    </a>
                                    <a href="{{ url('/books/create/'.$author->id) }}" class="btn btn-outline-success">
                                        <i class="fas fa-plus-circle"></i> Add New Book
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="author-avatar-container">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($author->name) }}&background=11BBAB&color=fff&size=250" class="author-avatar" alt="{{ $author->name }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Author's Books Section -->
        <div class="mt-4">
            <h3 class="section-title">
                <i class="fas fa-book"></i>
                Books by {{ $author->name }}
            </h3>

            @if($author->booksWritten()->count() == 0)
                <div class="author-card">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="empty-title">No Books Available</h3>
                        <p class="empty-text">This author doesn't have any books in the library yet.</p>

                        @auth
                            @if(Auth::user()->role == 'admin')
                                <a href="{{ url('/books/create/'.$author->id) }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i> Add First Book
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @else
                <div class="author-card">
                    <div class="table-responsive">
                        <table class="books-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Genre</th>
                                    <th>PDF</th>
                                    <th>Readers</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($author->booksWritten as $book)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-book text-primary me-2"></i>
                                            <span>{{ $book->title }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $book->genre }}</span>
                                    </td>
                                    <td>
                                        @if($book->file)
                                            <i class="fas fa-file-pdf text-danger"></i>
                                        @else
                                            <i class="fas fa-times-circle text-muted"></i>
                                        @endif
                                    </td>
                                    <td>{{ $book->borrowedBy()->count() }}</td>
                                    <td>
                                        <span class="status-badge {{ $book->status == 'availiable' ? 'status-available' : 'status-unavailable' }}">
                                            {{ $book->status == 'availiable' ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('books.show', $book->id) }}" class="btn-view me-2">
                                            <i class="far fa-eye"></i> View
                                        </a>
                                        @auth
                                            @if(Auth::user()->role == 'admin' && $book->file)
                                                <a href="{{ route('books.view', $book->id) }}" class="btn-view" style="background-color: #dc3545;">
                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                </a>
                                            @elseif(Auth::user()->booksBorrowed->contains($book->id))
                                                <a href="{{ route('books.view', $book->id) }}" class="btn-view" style="background-color: #28a745;">
                                                    <i class="fas fa-book-open"></i> Read
                                                </a>
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@auth
    @if(Auth::user()->role == 'admin')
        <!-- Delete Confirmation Modal -->
        <script>
            function confirmDelete() {
                if (confirm('Are you sure you want to delete this author? This action cannot be undone.')) {
                    document.getElementById('delete-author-form').submit();
                }
            }
        </script>
    @endif
@endauth
@endsection