@extends('layouts.app')

@section('title', 'Create New Password - EBOOK')

@section('content')
<!-- Create New Password Section with Background -->
<section class="position-relative overflow-hidden py-5">
    <!-- Background with Overlay -->
    <div class="position-absolute w-100 h-100" style="
        background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(17,187,171,0.7) 100%),
        url('{{ asset('images/library-bg.jpg') }}') no-repeat center center;
        background-size: cover;
        top: 0;
        left: 0;
        z-index: -1;">
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-header bg-white text-center p-4 border-bottom-0">
                        <h3 class="fw-bold mb-0">
                            <span class="text-primary">Create</span> New Password
                        </h3>
                        <p class="text-muted mt-2">Choose a secure password for your account</p>
                    </div>

                    <div class="card-body p-4 pt-0">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                        name="email" value="{{ $email ?? old('email') }}" readonly required autocomplete="email">
                                </div>
                                @error('email')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">{{ __('New Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                                        name="password" placeholder="Create a strong password" required autocomplete="new-password">
                                </div>
                                @error('password')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Password must be at least 8 characters
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label fw-bold">{{ __('Confirm New Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password-confirm" type="password" class="form-control border-start-0"
                                        name="password_confirmation" placeholder="Confirm your new password" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    <i class="fas fa-save me-2"></i> {{ __('Save New Password') }}
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted">Remember your password? <a href="{{ route('login') }}" class="text-primary fw-bold">Sign in</a></p>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center text-white mt-4" data-aos="fade-up" data-aos-delay="200">
                    <p>Keep your new password secure. Never share it with anyone.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
