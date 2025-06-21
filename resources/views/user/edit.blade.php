@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mt-2 mx-auto p-4 bg-light">
                <div class="card-body bg-light">
                    <div class="container">
                        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="controls">
                                <div class="row">
                                    <p class="mb-2 h3"><span style="color:#11BBAB" class="font-italic h3 me-1">Edit</span> Profile</p>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Full Name:</label>
                                            <input class="form-control" type="text" name="name" id="name"
                                                value="{{ $user->name }}"
                                                class="p-2 bg-gray-200 @error('name') is-invalid @enderror" />
                                            @error('name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label for="username">Username:</label>
                                            <input class="form-control" type="text" name="username" id="username"
                                                value="{{ $user->username }}"
                                                class="p-2 bg-gray-200 @error('username') is-invalid @enderror" />
                                            @error('username')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input class="form-control" type="email" name="email" id="email"
                                                value="{{ $user->email }}"
                                                class="p-2 bg-gray-200 @error('email') is-invalid @enderror" readonly />
                                            <small class="text-muted">Email address cannot be changed for security reasons.</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button style="background: #11BBAB" type="submit"
                                            class="btn text-white btn-send mt-3 pt-2 btn-block">
                                            Update Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
