@extends('layouts.app')

@section('styles')
    <link href="{{ asset('cssfile/bodycss.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom CSS for admin dashboard -->
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

        .admin-dashboard {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 2rem 0;
        }

        /* Card Styles */
        .dashboard-card {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: all var(--transition-speed) ease;
            overflow: hidden;
            border: none;
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
            min-width: 100%;
            background-color: #fff;
        }

        .dashboard-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        /* Summary Card Styles */
        .summary-card {
            border-left: 4px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%);
            z-index: 1;
        }

        .summary-card:nth-child(1) {
            border-left-color: var(--primary-color);
        }

        .summary-card:nth-child(2) {
            border-left-color: var(--success-color);
        }

        .summary-card:nth-child(3) {
            border-left-color: var(--info-color);
        }

        .summary-card:nth-child(4) {
            border-left-color: var(--warning-color);
        }

        .bg-primary-light {
            background-color: rgba(var(--primary-color-rgb), 0.15);
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.15);
        }

        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.15);
        }

        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.15);
        }

        .summary-icon {
            height: 50px;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        /* Profile Styles */
        .profile-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
        }

        .profile-img {
            width: 80px;
            height: 80px;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            object-fit: cover;
        }

        .admin-role-badge {
            background-color: var(--secondary-color);
            padding: 0.3rem 0.8rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.8rem;
            color: white;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Action Card Styles */
        .action-card {
            margin-bottom: 1.5rem;
        }

        .action-list .action-item {
            padding: 0.8rem 1rem;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .action-list .action-item:hover {
            background-color: #f8f9fa;
            border-left-color: var(--primary-color);
        }

        .action-icon {
            margin-right: 0.8rem;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .icon-blue {
            background-color: #0d6efd;
        }

        .icon-green {
            background-color: #198754;
        }

        .icon-purple {
            background-color: #6f42c1;
        }

        .icon-orange {
            background-color: #fd7e14;
        }

        /* Info Row Styles */
        .info-row {
            transition: background-color 0.2s ease;
        }

        .info-row:hover {
            background-color: rgba(0,0,0,0.01);
        }

        .info-label {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.85rem;
        }

        .text-uppercase {
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #6c757d;
        }

        /* Enhanced Admin Profile Styles */
        .profile-header {
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .avatar-container {
            display: inline-block;
            position: relative;
        }

        .profile-img.shadow, .character-avatar {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .profile-img.shadow:hover, .character-avatar:hover {
            transform: scale(1.05);
            cursor: pointer;
        }

        .character-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Action Item Styles */
        .action-link {
            text-decoration: none;
            color: inherit;
            display: block;
            transition: all 0.2s ease;
        }

        .action-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            background-color: white;
            transition: all 0.2s ease;
            border: 1px solid #f0f0f0;
        }

        .action-link:hover .action-item {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 1rem;
        }

        .action-content {
            flex-grow: 1;
        }

        .action-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: #343a40;
        }

        .action-subtitle {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .action-arrow {
            color: #adb5bd;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .action-link:hover .action-arrow {
            color: var(--primary-color);
            transform: translateX(3px);
        }

        /* Card Structure */
        .card-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            background-color: #fff;
            display: flex;
            align-items: center;
        }

        .card-body {
            flex: 1;
            padding: 1.25rem;
        }

        /* Table Styles */
        .scrollable-table-container {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            position: relative;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            width: 100%;
        }

        .scrollable-table-container::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollable-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .scrollable-table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .scrollable-table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            table-layout: fixed;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: var(--dark-color);
            z-index: 10;
            box-shadow: 0 1px 0 rgba(0,0,0,0.05);
        }

        .table tbody tr {
            transition: background-color 0.15s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.03);
        }

        .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        /* Button Styles */
        .btn-custom {
            border-radius: 4px;
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            min-width: 32px;
        }

        .btn-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* PDF button specific styling */
        .btn-info.btn-custom {
            background: linear-gradient(135deg, #17a2b8, #138496);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-info.btn-custom:before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
            transition: all 0.6s;
        }

        .btn-info.btn-custom:hover:before {
            left: 100%;
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 2.5rem;
            color: #6c757d;
            background-color: rgba(0,0,0,0.01);
            border-radius: var(--border-radius);
        }

        .empty-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.7;
            color: #d1d3e2;
        }

        /* Utilities */
        .gap-1 {
            gap: 0.25rem;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .border-top {
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        /* Responsive fixes */
        @media (max-width: 991.98px) {
            .dashboard-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 767.98px) {
            .summary-card .card-body {
                padding: 1rem;
            }

            .profile-img {
                width: 60px;
                height: 60px;
            }

            .table-responsive {
                border-radius: var(--border-radius);
            }
        }

        /* Enhancing responsive behavior */
        @media (max-width: 767px) {
            .scrollable-table-container {
                overflow-x: auto;
            }

            .table {
                min-width: 650px; /* Ensures table doesn't get too squished */
            }

            .dashboard-card {
                margin-left: 0;
                margin-right: 0;
                width: 100%;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-header > div:last-child {
                margin-top: 0.5rem;
                width: 100%;
                display: flex;
                justify-content: space-between;
            }
        }

        /* Enhanced responsive behavior to fix overlapping issues */
        @media (min-width: 992px) {
            .admin-dashboard .col-lg-9 {
                padding-left: 2rem; /* Add extra padding to the main content to prevent overlap */
            }

            .admin-dashboard .row {
                --bs-gutter-x: 2rem; /* Increase gutter width for better spacing */
            }
        }

        /* Fix for smaller screens */
        @media (max-width: 991px) {
            .admin-dashboard .col-lg-3 {
                margin-bottom: 2rem;
            }
        }
    </style>
@endsection

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 position-fixed top-0 start-50 translate-middle-x mt-4 z-index-1000"
             style="max-width: 500px; z-index: 1050;" role="alert">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div>
                    <strong>Success!</strong> {{ session()->get('message') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif    <section class="admin-dashboard">
        <div class="container py-4">
            <!-- Statistics Summary Cards - Top Row -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="dashboard-card summary-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="summary-icon bg-primary-light me-3">
                                <i class="fas fa-book fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">{{ $user->booksCreated ? $user->booksCreated->count() : 0 }}</h3>
                                <p class="text-muted mb-0">Books Created</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="dashboard-card summary-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="summary-icon bg-success-light me-3">
                                <i class="fas fa-user-tie fa-lg text-success"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">{{ $user->authorsCreated ? $user->authorsCreated->count() : 0 }}</h3>
                                <p class="text-muted mb-0">Authors Created</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="dashboard-card summary-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="summary-icon bg-info-light me-3">
                                <i class="fas fa-calendar-alt fa-lg text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ date('Y-m-d') }}</h6>
                                <p class="text-muted mb-0">Today's Date</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="dashboard-card summary-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="summary-icon bg-warning-light me-3">
                                <i class="fas fa-clock fa-lg text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold" id="currentTime">00:00:00</h6>
                                <p class="text-muted mb-0">Current Time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Row -->
            <div class="row">
                <!-- Left Sidebar - Admin Profile and Actions -->
                <div class="col-lg-3 mb-4">
                    <!-- Admin Profile Card -->
                    <div class="dashboard-card mb-4">
                        <div class="card-body p-0">
                            <!-- Profile Header -->
                            <div class="profile-header bg-primary text-white p-3 rounded-top d-flex align-items-center">
                                <i class="fas fa-user-shield fs-4 me-2"></i>
                                <h5 class="m-0">Admin Profile</h5>
                            </div>

                            <!-- Profile Content -->
                            <div class="profile-content">
                                <!-- Avatar and Username Section -->
                                <div class="profile-section text-center py-4 position-relative">
                                    <!-- Avatar with border and shadow -->
                                    <div class="avatar-container mx-auto mb-3">
                                        <!-- Character-based avatar with the same style as authors show page -->
                                        <div class="character-avatar rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 96px; height: 96px; border: 4px solid white; background-color: var(--primary-color); color: white; font-size: 36px; font-weight: 600; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>

                                    <h5 class="mb-1 fw-bold">{{ $user->username }}</h5>

                                    <!-- Role Badge -->
                                    <div class="badge bg-success rounded-pill px-3 py-2 text-white">
                                        <i class="fas fa-check-circle me-1"></i>{{ $user->role }}
                                    </div>
                                </div>

                                <!-- User Information -->
                                <div class="admin-info px-4 pb-4">
                                    <!-- Full Name -->
                                    <div class="info-row mb-3">
                                        <p class="info-label mb-1 small text-uppercase text-muted">
                                            <i class="fas fa-id-card text-primary me-2"></i>FULL NAME
                                        </p>
                                        <p class="info-value mb-0 ps-4 fs-5">{{ $user->name }}</p>
                                    </div>

                                    <!-- Email -->
                                    <div class="info-row">
                                        <p class="info-label mb-1 small text-uppercase text-muted">
                                            <i class="fas fa-envelope text-primary me-2"></i>EMAIL
                                        </p>
                                        <p class="info-value mb-0 ps-4 fs-5">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Actions Card -->
                    <div class="dashboard-card action-card">
                        <div class="card-body p-0">
                            <!-- Actions Header -->
                            <div class="bg-secondary text-white p-3 rounded-top d-flex align-items-center">
                                <i class="fas fa-bolt fs-4 me-2"></i>
                                <h5 class="m-0">Quick Actions</h5>
                            </div>

                            <!-- Actions Menu -->
                            <div class="action-list p-2">
                                <?php $id = 0; ?>

                                <!-- Create New Author -->
                                <a href="/authors/create" class="action-link">
                                    <div class="action-item">
                                        <div class="action-icon bg-info text-white">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div class="action-content">
                                            <div class="action-title">Create New Author</div>
                                            <div class="action-subtitle">Add a new author to the system</div>
                                        </div>
                                        <div class="action-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>

                                <!-- Add Book to Existing Author -->
                                <a href="/authors" class="action-link">
                                    <div class="action-item">
                                        <div class="action-icon bg-success text-white">
                                            <i class="fas fa-book-medical"></i>
                                        </div>
                                        <div class="action-content">
                                            <div class="action-title">Add Book to Author</div>
                                            <div class="action-subtitle">Link a new book to an author</div>
                                        </div>
                                        <div class="action-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>

                                <!-- Add Book Without Author -->
                                <a href="{{ route('books.create.for.author', $id) }}" class="action-link">
                                    <div class="action-item">
                                        <div class="action-icon bg-primary text-white">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div class="action-content">
                                            <div class="action-title">Add Book</div>
                                            <div class="action-subtitle">Create a book without an author</div>
                                        </div>
                                        <div class="action-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>

                                <!-- Manage Admin Users -->
                                <a href="{{ url('/users') }}" class="action-link">
                                    <div class="action-item">
                                        <div class="action-icon bg-warning text-white">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <div class="action-content">
                                            <div class="action-title">Manage Users</div>
                                            <div class="action-subtitle">Administrate user accounts</div>
                                        </div>
                                        <div class="action-arrow">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Left Sidebar -->

                <!-- Main Content Column -->
                <div class="col-lg-9">
                    <!-- Content Cards -->
                    <div class="row">
                        <div class="col-12">
                            <!-- Books Created Card -->
                            <div class="dashboard-card">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-book text-primary me-2"></i>
                                        <span class="text-primary fw-bold">Books Created</span>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">{{ $user->booksCreated ? $user->booksCreated->count() : 0 }}</span>
                                        <a href="{{ route('books.create.for.author', 0) }}" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fas fa-plus-circle me-1"></i> Add New
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    @if (!$user->booksCreated || $user->booksCreated->count() == 0)
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-book-open"></i>
                                            </div>
                                            <h5 class="mb-2">No Books Added Yet</h5>
                                            <p class="mb-3">Start creating your digital library by adding books</p>
                                            <a href="{{ route('books.create.for.author', 0) }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-2"></i> Add Your First Book
                                            </a>
                                        </div>
                                    @else
                                        <div class="scrollable-table-container">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" width="35%">Title</th>
                                                        <th scope="col" width="25%">Author</th>
                                                        <th scope="col" width="15%" class="text-center">Status</th>
                                                        <th scope="col" width="25%" class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($user->booksCreated ?? [] as $book)
                                                    <tr>
                                                        <td class="align-middle">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-book text-primary me-2"></i>
                                                                <span class="text-truncate" style="max-width: 180px;" title="{{ $book->title }}">
                                                                    {{ $book->title }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            @if($book->author_id && $book->writtenBy)
                                                                <a href="{{ route('authors.show', $book->author_id) }}" class="text-decoration-none">
                                                                    <span class="text-truncate">{{ $book->writtenBy->name }}</span>
                                                                </a>
                                                            @else
                                                                <span class="text-muted fst-italic">No author</span>
                                                            @endif
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="badge {{ $book->status == 'availiable' ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $book->status == 'availiable' ? 'Available' : 'Unavailable' }}
                                                            </span>
                                                            @if($book->file)
                                                            <span class="badge bg-info ms-1" title="Has PDF"><i class="fas fa-file-pdf"></i></span>
                                                            @endif
                                                        </td>
                                                        <td class="align-middle text-end">
                                                            <div class="d-flex gap-1 justify-content-end flex-nowrap">
                                                                <a href="{{ route('books.show', $book->id) }}"
                                                                    class="btn btn-primary btn-sm btn-custom" title="View Details">
                                                                    <i class="far fa-eye"></i>
                                                                </a>
                                                                @if($book->file)
                                                                <a href="{{ route('books.view', $book->id) }}" target="_blank"
                                                                    class="btn btn-info btn-sm btn-custom" title="View PDF">
                                                                    <i class="fas fa-file-pdf"></i>
                                                                </a>
                                                                @else
                                                                <button type="button" class="btn btn-secondary btn-sm btn-custom"
                                                                    title="No PDF Available" disabled>
                                                                    <i class="fas fa-file-pdf"></i>
                                                                </button>
                                                                @endif
                                                                <a href="{{ route('books.edit', $book->id) }}"
                                                                    class="btn btn-warning btn-sm btn-custom" title="Edit Book">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
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

                            <!-- Authors Created Card -->
                            <div class="dashboard-card mt-4">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-user-tie text-success me-2"></i>
                                        <span class="text-success fw-bold">Authors Created</span>
                                    </div>
                                    <div>
                                        <span class="badge bg-success">{{ $user->authorsCreated ? $user->authorsCreated->count() : 0 }}</span>
                                        <a href="/authors/create" class="btn btn-sm btn-outline-success ms-2">
                                            <i class="fas fa-plus-circle me-1"></i> Add New
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    @if (!$user->authorsCreated || $user->authorsCreated->count() == 0)
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-user-edit"></i>
                                            </div>
                                            <h5 class="mb-2">No Authors Added Yet</h5>
                                            <p class="mb-3">Create authors to organize your books by writer</p>
                                            <a href="/authors/create" class="btn btn-success">
                                                <i class="fas fa-plus-circle me-2"></i> Add Your First Author
                                            </a>
                                        </div>
                                    @else
                                        <div class="scrollable-table-container">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Books Count</th>
                                                        <th scope="col">Bio</th>
                                                        <th scope="col" class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($user->authorsCreated ?? [] as $author)
                                                    <tr>
                                                        <td class="align-middle">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-user-tie text-success me-2"></i>
                                                                <span class="text-truncate" style="max-width: 180px;" title="{{ $author->name }}">
                                                                    {{ $author->name }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="badge bg-light text-dark">
                                                                {{ $author->booksWritten ? $author->booksWritten->count() : 0 }}
                                                            </span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $author->bio ?? 'No biography available' }}">
                                                                {{ $author->bio ? Str::limit($author->bio, 50) : 'No biography available' }}
                                                            </span>
                                                        </td>
                                                        <td class="align-middle text-end">
                                                            <div class="d-flex gap-2 justify-content-end">
                                                                <a href="{{ route('authors.show', $author->id) }}"
                                                                    class="btn btn-info btn-sm btn-custom" title="View Details">
                                                                    <i class="far fa-eye"></i>
                                                                </a>

                                                                <!-- PDF View Button removed as requested -->

                                                                <a href="{{ route('books.create.for.author', $author->id) }}"
                                                                    class="btn btn-success btn-sm btn-custom" title="Add Book">
                                                                    <i class="fas fa-book-medical"></i>
                                                                </a>
                                                                <a href="{{ route('authors.edit', $author->id) }}"
                                                                    class="btn btn-warning btn-sm btn-custom" title="Edit Author">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
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
            </div>

            <!-- Any additional content can go here if needed in the future -->
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        // Display and update current time
        function updateTime() {
            const now = new Date();
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            document.getElementById('currentTime').innerHTML = now.toLocaleTimeString([], options);
            setTimeout(updateTime, 1000);
        }

        // Initialize dashboard on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Start the clock
            updateTime();

            // Initialize tooltips if Bootstrap tooltips are available
            if(typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]:not([data-bs-toggle])'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        placement: 'top',
                        boundary: document.body
                    });
                });
            }

            // Add animation to the alert if present
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('fade');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            }

            // PDF view button enhancements
            document.querySelectorAll('a[href*="books.view"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    // Show loading indicator
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    this.classList.add('disabled');

                    // Restore button after timeout or on window focus (when returning from PDF view)
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.remove('disabled');
                    }, 3000);

                    window.addEventListener('focus', function onFocus() {
                        link.innerHTML = originalHTML;
                        link.classList.remove('disabled');
                        window.removeEventListener('focus', onFocus);
                    });
                });
            });
        });
    </script>
@endsection
