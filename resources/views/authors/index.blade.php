@extends('layouts.app')

@section('title', 'Authors Collection')

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

    .authors-page {
        background-color: #f8f9fa;
        min-height: calc(100vh - 76px);
        padding: 2rem 0;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: var(--dark-color);
        display: flex;
        align-items: center;
    }

    .page-title i {
        color: var(--primary-color);
        margin-right: 0.5rem;
    }

    /* Card Styles */
    .content-card {
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: all var(--transition-speed) ease;
        overflow: hidden;
        border: none;
        margin-bottom: 1.5rem;
        width: 100%;
        background-color: #fff;
    }

    .content-card:hover {
        box-shadow: var(--card-shadow-hover);
    }

    .search-card {
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .search-card .form-control {
        border-radius: 50px;
        padding-left: 1rem;
        height: 45px;
        font-size: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: none;
    }

    .search-card .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
    }

    .search-card .btn {
        border-radius: 50px;
        padding: 0.375rem 1.5rem;
        height: 45px;
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        transition: all 0.2s ease;
    }

    .search-card .btn:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        transform: translateY(-1px);
    }

    .search-icon {
        color: #adb5bd;
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }

    /* Author List Styles */
    .author-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .author-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f1f1;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .author-item:last-child {
        border-bottom: none;
    }

    .author-item:hover {
        background-color: rgba(var(--primary-color-rgb), 0.05);
    }

    .author-info {
        display: flex;
        align-items: center;
    }

    .author-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .author-name {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 0.2rem;
    }

    .author-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .author-actions .btn {
        border-radius: 50px;
        padding: 0.375rem 1.25rem;
        transition: all 0.2s ease;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .author-actions .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .author-actions .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        transform: translateY(-1px);
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
        font-size: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .authors-page {
            padding: 1rem 0;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .search-card {
            padding: 1rem;
        }

        .author-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .author-actions {
            margin-top: 1rem;
            align-self: flex-end;
        }
    }
</style>
@endsection

@section('content')
<div class="authors-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h1 class="page-title mb-4">
                    <i class="fas fa-user-tie"></i>
                    Authors Collection
                </h1>

                <!-- Search Card -->
                <div class="content-card search-card">
                    <form action="{{ route('authors.index') }}" method="GET" role="search">
                        <div class="row g-2">
                            <div class="col-12 col-md-10">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search search-icon"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" name="term" placeholder="Search authors by name..." value="{{ request('term') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Authors List Card -->
                <div class="content-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <i class="fas fa-list text-primary me-2"></i>
                            <span class="fw-bold">Author List</span>
                        </div>
                        <div>
                            <span class="badge bg-primary">
                                @php
                                    $authorCount = 0;
                                    if ($authors) {
                                        $authorCount = method_exists($authors, 'total') ? $authors->total() : ($authors->count() ?? 0);
                                    }
                                @endphp
                                {{ $authorCount }}
                            </span>
                            <a href="{{ route('authors.create') }}" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="fas fa-plus-circle me-1"></i> Add Author
                            </a>
                        </div>
                    </div>

                    @if(!$authors || ($authors->count() ?? 0) == 0)
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="far fa-user-circle"></i>
                            </div>
                            <h3 class="empty-title">No Authors Found</h3>
                            <p class="empty-text">There are no authors in the system yet or none match your search.</p>
                            <a href="{{ route('authors.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i> Add Your First Author
                            </a>
                        </div>
                    @else
                        <ul class="author-list">
                            @foreach ($authors as $author)
                                <li class="author-item">
                                    <div class="author-info">
                                        <div class="author-avatar">
                                            {{ strtoupper(substr($author->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="author-name">{{ $author->name }}</div>
                                            <div class="author-meta">
                                                @php
                                                    $bookCount = $author->booksWritten ? $author->booksWritten->count() : 0;
                                                @endphp
                                                {{ $bookCount }} {{ Str::plural('book', $bookCount) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="author-actions">
                                        <a href="{{ route('authors.show', $author->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Pagination -->
                        @if(method_exists($authors, 'links'))
                            <div class="d-flex justify-content-center p-3">
                                {{ $authors->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection