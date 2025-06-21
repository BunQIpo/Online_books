@extends('layouts.app')

@section('title', 'Welcome to EBOOK - Your Online Library')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <!-- Hero Background with Overlay -->
    <div class="position-absolute w-100 h-100" style="
        background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(17,187,171,0.7) 100%),
        url('{{ asset('images/library-bg.jpg') }}') no-repeat center center;
        background-size: cover;
        z-index: -1;">
    </div>

    <div class="container py-5">
        <div class="row min-vh-75 align-items-center py-5">
            <!-- Hero Content -->
            <div class="col-lg-7 text-white" data-aos="fade-right" data-aos-delay="100">
                <h1 class="display-3 fw-bold mb-3">Discover Your Next Great Read</h1>
                <p class="lead mb-4">Explore thousands of books. Borrow, read, and return with ease - all from the comfort of your home.</p>

                <!-- Search Form -->
                <form class="mt-4 bg-white p-3 rounded shadow-lg" action="{{ route('books.index') }}" method="GET" role="search">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="fas fa-search text-primary fs-5"></i>
                        </span>
                        <input class="form-control form-control-lg border-0" name="term" placeholder="Search for books, authors, or genres..." id="term">
                        <button class="btn btn-primary btn-lg px-4" type="submit">Search</button>
                    </div>
                </form>

            </div>

            <!-- Hero Image -->
            <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left" data-aos-delay="200">
                <div class="position-relative">
                    <div class="position-absolute top-0 end-0 w-75 h-75 bg-primary rounded-circle opacity-10"></div>
                    <img src="https://png.pngtree.com/png-vector/20230912/ourmid/pngtree-man-reading-book-png-image_10028302.png" alt="Reader with books" class="img-fluid position-relative" style="z-index: 1;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Books Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center" data-aos="fade-up">
                <h2 class="fw-bold">Featured Books</h2>
                <p class="text-muted">Explore our latest and most popular titles</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Dynamic Featured Books -->
            @forelse($featuredBooks as $index => $book)
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($book->created_at > now()->subDays(7))
                            <div class="bg-primary text-white px-3 py-1 position-absolute top-0 end-0 m-2 rounded-pill">New</div>
                        @elseif($book->borrowedBy->count() > 5)
                            <div class="bg-danger text-white px-3 py-1 position-absolute top-0 end-0 m-2 rounded-pill">Popular</div>
                        @endif
                        <div class="p-3 text-center">
                            <img src="{{ $book->image_url }}" alt="{{ $book->title }} Cover" class="img-fluid mb-3" style="height: 200px; object-fit: cover;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ Str::limit($book->title, 30) }}</h5>
                            <p class="card-text text-muted small">
                                @if($book->writtenBy)
                                    By {{ $book->writtenBy->name }}
                                @else
                                    By Unknown Author
                                @endif
                            </p>
                            <div class="d-flex justify-content-end align-items-center mt-3">
                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-primary btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-book-open fa-4x mb-3 text-secondary"></i>
                        <h4>No featured books available at the moment</h4>
                        <p>Check back soon or explore our library!</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-4" data-aos="fade-up">
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg px-4">Browse All Books</a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center" data-aos="fade-up">
                <h2 class="fw-bold">How It Works</h2>
                <p class="text-muted">Borrowing books has never been easier</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-light p-4 rounded-4 shadow-sm h-100">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-search text-white fa-2x"></i>
                    </div>
                    <h4>1. Find</h4>
                    <p class="text-muted">Search our extensive library of books by title, author, or genre</p>
                </div>
            </div>

            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-light p-4 rounded-4 shadow-sm h-100">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-book-open text-white fa-2x"></i>
                    </div>
                    <h4>2. Borrow</h4>
                    <p class="text-muted">Borrow the books you want with just a few clicks using your credits</p>
                </div>
            </div>

            <div class="col-md-4 text-center mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-light p-4 rounded-4 shadow-sm h-100">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-undo text-white fa-2x"></i>
                    </div>
                    <h4>3. Return</h4>
                    <p class="text-muted">Return books when you're finished and get your credits back</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0" data-aos="fade-right">
                <h2 class="fw-bold mb-3">Ready to start reading?</h2>
                <p class="lead">Join thousands of book lovers and get access to our entire library.</p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-2">Sign Up</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">Login</a>
                @else
                    <a href="{{ route('books.index') }}" class="btn btn-light btn-lg px-4">Explore Books</a>
                @endguest
            </div>
        </div>
    </div>
</section>

<!-- Custom CSS -->
<style>
    .hero-section {
        min-height: 75vh;
        display: flex;
        align-items: center;
    }

    .min-vh-75 {
        min-height: 75vh;
    }

    .rounded-4 {
        border-radius: 1rem;
    }

    /* Override body background color (if needed) */
    body {
        background-color: #ffffff !important;
    }
</style>
@endsection
