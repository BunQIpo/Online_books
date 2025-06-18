@extends('layouts.app')

@section('title', 'Showing ' . $book->title)

@section('content')
<style>
  /* Book Details Page Specific Styles */
  .book-image-container {
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
  }

  .book-image-container:hover {
    transform: scale(1.02);
  }

  .badge {
    font-weight: 500;
  }

  /* Fancy animated gradient border for cards on hover */
  .card {
    transition: all 0.3s ease;
    position: relative;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  .breadcrumb-item + .breadcrumb-item::before {
    color: var(--primary-color);
  }

  /* Custom styling for buttons */
  .btn {
    border-radius: 6px;
    font-weight: 500;
    letter-spacing: 0.3px;
    transition: all 0.3s;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(17, 187, 171, 0.4);
  }

  .btn-success:hover, .btn-danger:hover, .btn-warning:hover {
    transform: translateY(-2px);
  }
</style>

<!-- Book Detail Header Banner -->
<div class="container-fluid bg-light py-4 mb-4 shadow-sm">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Books</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($book->title, 40) }}</li>
          </ol>
        </nav>
        <h1 class="display-5 fw-bold text-primary">{{ $book->title }}</h1>
        @if(!empty($book->writtenBy))
        <p class="lead">by <a href="{{ route('authors.show', $book->writtenBy->id ?? 1) }}" class="text-decoration-none">{{ $book->writtenBy['name'] ?? 'Anonymous' }}</a></p>
        @else
        <p class="lead">by Anonymous</p>
        @endif
      </div>
      <div class="col-md-4 text-end">
        @if($book->status == 'availiable')
          <span class="badge bg-success py-2 px-3 fs-6"><i class="fas fa-check-circle me-1"></i> Available</span>
        @else
          <span class="badge bg-danger py-2 px-3 fs-6"><i class="fas fa-times-circle me-1"></i> Unavailable</span>
        @endif
      </div>
    </div>
  </div>
</div>

@if(session()->has('message'))
<div class="container">
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i> {{ session()->get('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
@endif

<div class="container mb-5">
  <div class="row">
    <!-- Left Column - Book Image -->
    <div class="col-lg-4 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center">
          <div class="book-image-container mb-3 position-relative">
            @if($book->image_path)
              <img src="{{ asset('storage/' . $book->image_path) }}" alt="{{ $book->title }}" class="img-fluid rounded shadow" style="max-height: 350px; object-fit: cover;">
            @else
              <img src="{{ asset('images/default-book.jpg') }}" alt="{{ $book->title }}" class="img-fluid rounded shadow" style="max-height: 350px; object-fit: cover;">
            @endif
          </div>

          @auth
            @if(auth()->user()->booksBorrowed->contains($book->id))
              <a class="btn btn-lg btn-success w-100 mb-3" href="{{url('/books/view', $book->id)}}">
                <i class="fas fa-book-open me-2"></i> Read Book
              </a>
            @endif
          @endauth

          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="badge bg-primary p-2 rounded-pill">
              <i class="fas fa-bookmark me-1"></i> {{$book->genre}}
            </div>
            <div class="badge bg-secondary p-2 rounded-pill">
              <i class="fas fa-users me-1"></i> {{ $book->borrowedBy->count() }} borrowers
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column - Book Details -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
          <h2 class="card-title h3 mb-0">About this Book</h2>
        </div>
        <div class="card-body">
          <p class="card-text fs-5 mb-4">{{$book->description}}</p>

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="p-3 border rounded bg-light">
                <h3 class="h5 text-primary"><i class="fas fa-tag me-2"></i> Genre</h3>
                <p class="mb-0 fs-5">{{$book->genre}}</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 border rounded bg-light">
                <h3 class="h5 text-primary"><i class="fas fa-user me-2"></i> Author</h3>
                <p class="mb-0 fs-5">
                  @if(empty($book->writtenBy))
                    Anonymous
                  @else
                    {{$book->writtenBy['name']}}
                  @endif
                </p>
              </div>
            </div>
          </div>

          <div class="d-flex align-items-center p-3 mb-4 bg-light rounded border-start border-5 border-primary">
            <div class="me-auto">
              <h3 class="h4 mb-0 text-primary">Credits Required: {{$book->credit_price}}</h3>
            </div>
            @auth
              <div class="ms-3 d-grid gap-2 d-md-block">
                <form action="{{ route('books.borrow', $book->id) }}" method="POST" class="d-inline">
                  @csrf
                  @if($book->status == 'availiable')
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-shopping-cart me-2"></i> Borrow for {{$book->credit_price}} credits
                    </button>
                  @else
                    <button type="submit" class="btn btn-primary" disabled>
                      <i class="fas fa-shopping-cart me-2"></i> Borrow for {{$book->credit_price}} credits
                    </button>
                  @endif
                </form>
                @if(auth()->user()->booksBorrowed->contains($book->id))
                  <form action="{{ route('books.extend', $book->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    @if($book->status == 'availiable')
                      <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-calendar-plus me-2"></i> Extend ({{number_format($book->credit_price / 3, 2)}} credits)
                      </button>
                    @else
                      <button type="submit" class="btn btn-warning text-white" disabled>
                        <i class="fas fa-calendar-plus me-2"></i> Extend
                      </button>
                    @endif
                  </form>
                @endif
              </div>
            @else
              <a href="{{ route('login') }}" class="btn btn-outline-primary">
                <i class="fas fa-sign-in-alt me-2"></i> Login to Borrow
              </a>
            @endauth
          </div>
          @auth
            @if(auth()->user()->role == 'admin')
              <!-- Admin Actions -->
              <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                  <h2 class="h4 mb-0">Admin Controls</h2>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <!-- Status Toggle -->
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                          <h5 class="card-title"><i class="fas fa-toggle-on me-2"></i>Availability</h5>
                          <form action="{{ route('books.status', $book->id) }}" method="POST">
                            @csrf
                            @if($book->status == 'availiable')
                              <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-ban me-2"></i> Make Unavailable
                              </button>
                            @else
                              <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-check-circle me-2"></i> Make Available
                              </button>
                            @endif
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- File Upload -->
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                          <h5 class="card-title"><i class="fas fa-file-upload me-2"></i>Upload Book</h5>
                          <form action="{{ route('books.upload', $book->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                              <input id="file" accept="application/pdf" type="file" class="form-control form-control-sm" name="file">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                              <i class="fas fa-upload me-2"></i> Upload
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Edit/Delete -->
                    <div class="col-md-4">
                      <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                          <h5 class="card-title"><i class="fas fa-edit me-2"></i>Book Management</h5>
                          <div class="d-grid gap-2">
                            <a class="btn btn-success" href="{{ route('books.edit', $book->id) }}">
                              <i class="fas fa-pencil-alt me-2"></i> Edit Book
                            </a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this book?')">
                                <i class="fas fa-trash-alt me-2"></i> Delete Book
                              </button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          @endauth

          <!-- Related Books Section (Placeholder) -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
              <h2 class="h4 mb-0">You may also like</h2>
            </div>
            <div class="card-body">
              <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> More book recommendations would appear here based on genre and author.
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection