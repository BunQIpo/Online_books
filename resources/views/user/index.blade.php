@extends('layouts.app')

@section('styles')
<style>
  .user-card {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 5px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
  }

  .user-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
  }

  .user-card .card-header {
    background: linear-gradient(135deg, var(--primary-color), #0d9488);
    padding: 1rem;
    color: white;
  }

  .user-avatar {
    width: 50px;
    height: 50px;
    background-color: #f8f9fa;
    color: #6c757d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
  }

  .user-role-badge {
    padding: 0.35rem 0.65rem;
    border-radius: 50rem;
    font-size: 0.75rem;
    font-weight: 600;
  }

  .user-role-admin {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
  }

  .user-role-user {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
  }

  .search-container {
    background-color: #f8f9fa;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
  }

  .search-form {
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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
    padding: 3rem;
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }

  .no-results i {
    font-size: 4rem;
    opacity: 0.3;
    margin-bottom: 1rem;
    color: #6c757d;
  }
</style>
@endsection

@section('content')
<div class="container py-4">
  @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i> {{ session()->get('message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i> {{ session()->get('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Search Section -->
  <div class="search-container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <h2 class="text-center mb-4"><i class="fas fa-users me-2" style="color: var(--primary-color);"></i> Manage Users</h2>
        <form class="search-form" action="{{ route('users.index') }}" method="GET" role="search">
          <div class="input-group">
            <input type="text" class="form-control search-input" name="term" placeholder="Search by user name or email..." value="{{ request()->get('term') }}">
            <button class="btn btn-primary search-btn" type="submit">
              <i class="fas fa-search me-2"></i> Search
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- User List Section -->
  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h3 class="mb-0">User <span style="color: var(--primary-color);">List</span></h3>
          <span class="badge bg-light text-dark px-3 py-2">{{ $users->count() }} users found</span>
        </div>
        <div class="card-body p-0">
          @if($users->count() == 0)
            <div class="no-results">
              <i class="fas fa-search"></i>
              <h3>No users found</h3>
              <p class="text-muted">Try adjusting your search criteria.</p>
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Credits</th>
                    <th>Books Borrowed</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="user-avatar me-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                          </div>
                          <div>
                            <h6 class="mb-0">{{ $user->name }}</h6>
                            @if($user->username)
                              <small class="text-muted">{{ $user->username }}</small>
                            @endif
                          </div>
                        </div>
                      </td>
                      <td>{{ $user->email }}</td>
                      <td>
                        <span class="user-role-badge {{ $user->role == 'admin' ? 'user-role-admin' : 'user-role-user' }}">
                          {{ ucfirst($user->role) }}
                        </span>
                      </td>
                      <td>{{ $user->credits ?? 0 }} credits</td>
                      <td>{{ $user->booksBorrowed ? $user->booksBorrowed->count() : 0 }}</td>
                      <td>
                        <div class="d-flex">
                          @if($user->role == 'user')
                            <form action="{{ route('user.makeAdmin', $user->id) }}" method="POST" class="me-2">
                              @csrf
                              <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-user-shield me-1"></i> Make Admin
                              </button>
                            </form>
                          @endif

                          @if(Auth::id() != $user->id)
                            <form action="{{ route('user.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? All borrowed books will be returned.')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-user-times me-1"></i> Delete
                              </button>
                            </form>
                          @endif
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