@extends('layouts.app')

@section('title', 'Reset Your Password - EBOOK')

@section('content')
<!-- Reset Password Section with Background -->
<section class="position-relative overflow-hidden py-5">
    <!-- Background with Overlay -->
    <div class="position-absolute w-100 h-100" style="
        background: linear-gradient(135deg, rgba(17,187,171,0.8) 0%, rgba(0,0,0,0.7) 100%),
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
                            <span class="text-primary">Reset</span> Your Password
                        </h3>
                        <p class="text-muted mt-2">Enter your email to receive a password reset link</p>
                    </div>

                    <div class="card-body p-4 pt-0">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="Enter your registered email" required autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i> {{ __('Send Reset Link') }}
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted">Remember your password? <a href="{{ route('login') }}" class="text-primary fw-bold">Back to login</a></p>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center text-white mt-4" data-aos="fade-up" data-aos-delay="200">
                    <p>Need assistance? <a href="#" class="text-white text-decoration-underline">Contact our support team</a>.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
