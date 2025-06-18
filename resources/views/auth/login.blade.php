@extends('layouts.app')

@section('title', 'Sign In to EBOOK - Access Your Library')

@section('content')
<!-- Login Section with Background -->
<section class="position-relative overflow-hidden py-5">
    <!-- Background with Overlay -->
    <div class="position-absolute w-100 h-100" style="
        background: linear-gradient(135deg, rgba(17,187,171,0.7) 0%, rgba(0,0,0,0.8) 100%),
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
                            Welcome <span class="text-primary">Back</span>
                        </h3>
                        <p class="text-muted mt-2">Sign in to access your library</p>
                    </div>

                    <div class="card-body p-4 pt-0">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="password" class="form-label fw-bold">{{ __('Password') }}</label>
                                    @if (Route::has('password.request'))
                                        <a class="text-primary small" href="{{ route('password.request') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                                        name="password" placeholder="Enter your password" required autocomplete="current-password">
                                </div>
                                @error('password')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    <i class="fas fa-sign-in-alt me-2"></i> {{ __('Sign In') }}
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold">Create one</a></p>
                            </div>


                        </form>
                    </div>
                </div>

                <div class="text-center text-white mt-4" data-aos="fade-up" data-aos-delay="200">
                    <p>New to EBOOK? <a href="{{ route('register') }}" class="text-white fw-bold">Join for free</a> and start your reading journey today.</p>
                </div>
            </div>
        </div>
    </div>
</section>
    </div>
</div>
@endsection
