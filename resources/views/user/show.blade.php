
@extends('layouts.app')

@section('styles')
<style>
    :root {
        --primary-color: #11BBAB;
        --primary-color-rgb: 17, 187, 171;
        --secondary-color: #3c4b64;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-radius: 0.5rem;
        --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --card-shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --transition-speed: 0.3s;
    }

    .user-profile {
        background-color: #f8f9fa;
        min-height: calc(100vh - 76px);
        padding: 2rem 0;
    }

    .user-header {
        margin-bottom: 2rem;
    }

    .user-card {
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: all var(--transition-speed) ease;
        overflow: hidden;
        border: none;
        margin-bottom: 1.5rem;
        background-color: #fff;
    }

    .user-card:hover {
        box-shadow: var(--card-shadow-hover);
    }

    .user-info {
        padding: 2rem;
    }

    .user-name {
        font-size: 2rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .user-subtitle {
        color: var(--primary-color);
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .user-stat {
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

    .avatar-container {
        position: relative;
        width: 180px;
        height: 180px;
        margin: 0 auto 2rem;
    }

    .avatar {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .edit-profile-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: var(--border-radius);
        font-weight: 500;
        transition: all var(--transition-speed) ease;
        margin-top: 1.5rem;
    }

    .edit-profile-btn:hover {
        background-color: #0ea396;
        transform: translateY(-2px);
    }

    /* Borrowed Books Styling */
    .character-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--primary-color);
        color: white;
        font-weight: bold;
    }

    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }

    .book-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .card-img-top {
        transition: transform 0.5s;
    }

    .book-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session()->get('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="user-profile">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="user-card">
                    <div class="user-info text-center">
                        <div class="avatar-container">
                            <div class="character-avatar avatar d-flex align-items-center justify-content-center" style="background-color: var(--primary-color); color: white; font-size: 72px; font-weight: 600;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <h1 class="user-name">{{ $user->username }}</h1>
                        <p class="user-subtitle">{{ ucfirst($user->role) }}</p>

                        <a href="{{ route('user.edit', $user->id) }}" class="btn edit-profile-btn">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="user-card">
                    <div class="card-header bg-white p-3">
                        <h2 class="h4 mb-0">Profile Information</h2>
                    </div>
                    <div class="user-info">
                        <div class="user-stat">
                            <div class="stat-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="stat-label">Full Name</div>
                                <div class="stat-value">{{ $user->name }}</div>
                            </div>
                        </div>

                        <div class="user-stat">
                            <div class="stat-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div class="stat-label">Email</div>
                                <div class="stat-value">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="user-stat">
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <div class="stat-label">Books Borrowed</div>
                                <div class="stat-value">{{ $user->booksBorrowed()->count() }}</div>
                            </div>
                        </div>

                        <div class="user-stat">
                            <div class="stat-icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div>
                                <div class="stat-label">Credits</div>
                                <div class="stat-value">{{ $user->credits }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowed Books Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="user-card">
                    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-book-reader me-2 text-primary"></i>
                            Books Borrowed
                        </h2>
                        <span class="badge bg-primary">{{ $user->booksBorrowed()->count() }}</span>
                    </div>
                    <div class="card-body">
                        @if($user->booksBorrowed()->count() > 0)
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                @foreach($user->booksBorrowed as $book)
                                    <div class="col">
                                        <div class="card h-100 shadow-sm">
                                            <div style="height: 160px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                @if($book->image_path)
                                                    <img src="{{ asset('storage/' . $book->image_path) }}"
                                                         alt="{{ $book->title }}" class="card-img-top"
                                                         style="max-height: 150px; width: auto; object-fit: contain;">
                                                @else
                                                    <div class="text-center p-4">
                                                        <i class="fas fa-book fa-3x text-secondary opacity-50"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title" style="height: 48px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                                    {{ $book->title }}
                                                </h5>
                                                <p class="card-text mb-1">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user-edit me-1 text-primary"></i>
                                                        @if($book->author_id && $book->writtenBy)
                                                            {{ $book->writtenBy->name }}
                                                        @else
                                                            Anonymous
                                                        @endif
                                                    </small>
                                                </p>
                                                <p class="card-text mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                                        Expires: {{ $user->getExpiryDate($book->id) }}
                                                    </small>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-info-circle me-1"></i> Details
                                                    </a>
                                                    @if($book->file)
                                                        <a href="{{ route('books.view', $book->id) }}" class="btn btn-primary btn-sm" target="_blank">
                                                            <i class="fas fa-book-open me-1"></i> Read
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-books fa-3x text-light mb-3"></i>
                                <h5 class="text-muted">No books borrowed yet</h5>
                                <p class="text-muted mb-4">Start exploring our collection and borrow books that interest you.</p>
                                <a href="{{ route('books.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> Browse Books
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection