@extends('layouts.app')

@section('styles')
<style>
  /* Book list styling */
  .book-card {
    transition: transform 0.3s, box-shadow 0.3s;
    border-radius: 10px;
    overflow: hidden;
    height: 100%;
    width: 100%;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
  }

  .book-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1.25rem;
  }

  .book-card .d-flex.justify-content-between.align-items-center {
    margin-top: auto;
  }

  .book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
  }

  .book-cover-container {
    height: 220px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f5f5f5;
    padding: 10px;
  }

  .book-cover-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
  }

  .book-cover {
    max-width: 100%;
    max-height: 200px;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: transform 0.5s;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }

  .book-card:hover .book-cover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
  }  /* Dropdown styling */
  .dropdown-item.active, .dropdown-item:active {
    background-color: var(--primary-color);
    color: white;
  }

  .dropdown-toggle::after {
    margin-left: 0.5em;
  }

  /* Responsive adjustments for book covers */
  @media (max-width: 767px) {
    .book-cover-container {
      height: 200px;
    }

    .book-cover {
      max-height: 180px;
    }

    /* Ensure cards are evenly sized on mobile */
    .col {
      flex-basis: 100%;
      max-width: 100%;
    }
  }

  @media (min-width: 768px) and (max-width: 991px) {
    .book-cover-container {
      height: 240px;
    }

    .book-cover {
      max-height: 220px;
    }

    /* Two cards per row on tablets */
    .col {
      flex-basis: 50%;
      max-width: 50%;
    }
  }

  @media (min-width: 992px) {
    /* Three cards per row on small desktop */
    .row-cols-md-3 > .col {
      flex: 0 0 33.333333%;
      max-width: 33.333333%;
    }

    /* Four cards per row on larger desktop */
    .row-cols-lg-4 > .col {
      flex: 0 0 25%;
      max-width: 25%;
    }
  }

  .book-status {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
  }

  .book-title {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: #333;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 48px; /* Fixed height for two lines */
  }

  .book-author {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 10px;
  }

  .book-genre {
    margin-bottom: 15px;
    font-size: 0.85rem;
  }

  .book-price {
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0;
  }

  .search-container {
    background-color: #f8f9fa;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    margin-bottom: 30px;
  }

  .search-form {
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  }

  .search-input {
    border: none;
    font-size: 1rem;
    padding: 12px 20px;
    border-radius: 50px 0 0 50px;
  }

  .search-btn {
    border-radius: 0 50px 50px 0;
    padding: 12px 25px;
  }

  .no-results {
    text-align: center;
    padding: 50px 20px;
  }

  .no-results i {
    font-size: 4rem;
    opacity: 0.3;
    margin-bottom: 20px;
    color: #6c757d;
  }

  .pagination {
    justify-content: center;
    margin-top: 30px;
  }

  /* Button styling for borrow action */
  .btn-group {
    display: flex;
    flex-wrap: nowrap;
  }

  .card-body .btn {
    white-space: nowrap;
    padding: 0.25rem 0.5rem;
    font-size: 0.8125rem;
  }

  /* Row alignment fixes */
  .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -0.75rem;
    margin-left: -0.75rem;
  }

  .col {
    padding-right: 0.75rem;
    padding-left: 0.75rem;
    margin-bottom: 1.5rem;
  }

  /* Make the buttons stack on smaller card sizes */
  @media (max-width: 370px) {
    .btn-group {
      flex-direction: column;
      align-items: flex-end;
      gap: 5px;
    }

    .btn-group .ms-1 {
      margin-left: 0 !important;
      margin-top: 5px;
    }
  }
</style>
@endsection

@section('content')
@if ($message = Session::get('success'))
<div class="container mt-4">
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i> {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
@endif

<div class="container py-4">
  <!-- Search Section -->
  <div class="search-container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <h2 class="text-center mb-4"><i class="fas fa-book me-2" style="color: var(--primary-color);"></i> Find Your Next Book</h2>
        <form class="search-form" action="{{ route('books.index') }}" method="GET" role="search">
          <div class="input-group">
            <input type="text" class="form-control search-input" name="term" placeholder="Search by title, author, or genre..." value="{{ request()->get('term') }}">
            <button class="btn btn-primary search-btn" type="submit">
              <i class="fas fa-search me-2"></i> Search
            </button>
          </div>
          @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
          @endif
          @if(request('direction'))
            <input type="hidden" name="direction" value="{{ request('direction') }}">
          @endif
        </form>
      </div>
    </div>
  </div>

  <!-- Book List Section -->
  <div class="books-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3><span style="color: var(--primary-color);">Book</span> List</h3>
      <div class="d-flex align-items-center">
        <div class="dropdown me-3">
          <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-sort me-1"></i> Sort By
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
            <li><a class="dropdown-item {{ request('sort') == 'title' && request('direction') == 'asc' ? 'active' : '' }}"
                  href="{{ route('books.index', ['sort' => 'title', 'direction' => 'asc', 'term' => request('term')]) }}">
                  <i class="fas fa-sort-alpha-down me-2"></i> Title (A-Z)
                </a>
            </li>
            <li><a class="dropdown-item {{ request('sort') == 'title' && request('direction') == 'desc' ? 'active' : '' }}"
                  href="{{ route('books.index', ['sort' => 'title', 'direction' => 'desc', 'term' => request('term')]) }}">
                  <i class="fas fa-sort-alpha-up me-2"></i> Title (Z-A)
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item {{ request('sort') == 'credit_price' && request('direction') == 'asc' ? 'active' : '' }}"
                  href="{{ route('books.index', ['sort' => 'credit_price', 'direction' => 'asc', 'term' => request('term')]) }}">
                  <i class="fas fa-coins me-2"></i> Price (Low to High)
                </a>
            </li>
            <li><a class="dropdown-item {{ request('sort') == 'credit_price' && request('direction') == 'desc' ? 'active' : '' }}"
                  href="{{ route('books.index', ['sort' => 'credit_price', 'direction' => 'desc', 'term' => request('term')]) }}">
                  <i class="fas fa-coins me-2"></i> Price (High to Low)
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item {{ request('sort') == 'created_at' && request('direction') == 'desc' ? 'active' : '' }}"
                  href="{{ route('books.index', ['sort' => 'created_at', 'direction' => 'desc', 'term' => request('term')]) }}">
                  <i class="fas fa-calendar-alt me-2"></i> Newest First
                </a>
            </li>
            <li><a class="dropdown-item {{ request('sort') == 'created_at' && request('direction') == 'asc' ? 'active' : '' }}"
                  href="{{ route('books.index', ['sort' => 'created_at', 'direction' => 'asc', 'term' => request('term')]) }}">
                  <i class="fas fa-calendar-alt me-2"></i> Oldest First
                </a>
            </li>
          </ul>
        </div>
        <span class="badge bg-light text-dark px-3 py-2 me-2">{{ $books->total() }} books found</span>
      </div>
    </div>

    @if($books->count() == 0)
      <div class="no-results">
        <i class="fas fa-search"></i>
        <h3>No books found</h3>
        <p class="text-muted">Try adjusting your search criteria or browse our collection.</p>
      </div>
    @else
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($books as $book)
          <div class="col">
            <div class="card book-card h-100">
              <div class="book-cover-container">
                <div class="book-cover-wrapper">
                  @if($book->image_path)
                    <img src="{{ asset('storage/' . $book->image_path) }}" class="book-cover" alt="{{ $book->title }}">
                  @else
                    <img src="{{ asset('images/default-book.jpg') }}" class="book-cover" alt="{{ $book->title }}">
                  @endif
                </div>

                <!-- Status Badge -->
                <div class="book-status">
                  @if($book->status == 'availiable')
                    <span class="badge bg-success">Available</span>
                  @else
                    <span class="badge bg-danger">Unavailable</span>
                  @endif

                  @if($book->file)
                    <span class="badge bg-info ms-1"><i class="fas fa-file-pdf"></i></span>
                  @endif
                </div>
              </div>

              <div class="card-body">
                <h5 class="book-title">{{ $book->title }}</h5>
                <p class="book-author">
                  <i class="fas fa-user-edit me-1" style="color: var(--primary-color);"></i>
                  @if(empty($book->writtenBy))
                    Anonymous
                  @else
                    {{ $book->writtenBy['name'] }}
                  @endif
                </p>

                @if($book->genre)
                  <p class="book-genre">
                    <span class="badge" style="background-color: var(--primary-color);">{{ $book->genre }}</span>
                  </p>
                @endif

                <div class="d-flex justify-content-between align-items-center">
                  <p class="book-price">
                    <i class="fas fa-coins me-1"></i> {{ $book->credit_price }} credits
                  </p>
                </div>

                <div class="mt-3">
                  <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-info-circle me-1"></i> Details
                  </a>

                  @auth
                    @if($book->status == 'availiable' && !auth()->user()->booksBorrowed->contains($book->id))
                      <form action="{{ route('books.borrow', $book->id) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                          <i class="fas fa-shopping-cart me-1"></i> Borrow
                        </button>
                      </form>
                    @elseif(auth()->user()->booksBorrowed->contains($book->id))
                      <a href="{{ route('books.view', $book->id) }}" class="btn btn-sm btn-primary ms-1">
                        <i class="fas fa-book-open me-1"></i> Read
                      </a>
                    @endif
                  @endauth
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="pagination-container mt-5">
        {{ $books->appends(request()->query())->links() }}
      </div>
    @endif
  </div>
</div>

@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Highlight active sort option in dropdown
    const sortDropdown = document.getElementById('sortDropdown');
    const dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item');
    const activeItem = document.querySelector('.dropdown-menu .dropdown-item.active');

    if (activeItem) {
      // Set the dropdown button text to show the currently selected sort option
      sortDropdown.innerHTML = '<i class="fas fa-sort me-1"></i> ' + activeItem.textContent.trim();
    }

    // Make the dropdown items work properly with click events
    dropdownItems.forEach(item => {
      item.addEventListener('click', function(e) {
        // No need for preventDefault as we want the link to work

        // Add loading indicator
        const originalButtonText = sortDropdown.innerHTML;
        sortDropdown.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sorting...';
        sortDropdown.disabled = true;

        // We'll let the link navigate naturally, but add this visual feedback
        setTimeout(() => {
          sortDropdown.innerHTML = originalButtonText;
          sortDropdown.disabled = false;
        }, 500);
      });
    });
  });
</script>
@endsection