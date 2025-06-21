@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card mt-2 mx-auto p-4 bg-light">
                    <div class="card-body bg-light">
                        <div class="container">
                            <form action="{{ route('books.update', $book) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="controls">
                                    <div class="row">
                                        <p class="mb-2 h3"><span style="color:#11BBAB"
                                                class="font-italic h3 me-1">Edit</span> Book</p>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">Title:</label>
                                                <input class="form-control" type="text" name="title" id="title"
                                                    value="{{ $book->title }}"
                                                    class="p-2 bg-gray-200 @error('title') is-invalid @enderror" />
                                                @error('title')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="genre">Genre:</label>
                                                <input class="form-control" value="{{ $book->genre }}" name="genre"
                                                    id="genre" class="p-2 bg-gray-200 @error('genre') is-invalid @enderror">
                                                @error('genre')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="author_id">Author:</label>
                                                <select name="author_id" id="author_id" class="form-control @error('author_id') is-invalid @enderror">
                                                    <option value="0">-- Select Author (Optional) --</option>
                                                    @foreach($authors as $author)
                                                        <option value="{{ $author->id }}" {{ $book->author_id == $author->id ? 'selected' : '' }}>
                                                            {{ $author->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('author_id')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="credit_price">Credit Price:</label>
                                                <input type="number" class="form-control" value="{{ $book->credit_price }}"
                                                    name="credit_price" id="credit_price"
                                                    class="p-2 bg-gray-200 @error('credit_price') is-invalid @enderror">
                                                @error('credit_price')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description:</label>
                                                <textarea class="form-control" name="description" id="description" rows="5"
                                                    class="p-2 bg-gray-200 @error('description') is-invalid @enderror">{{ $book->description }}</textarea>
                                                @error('description')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="book_image">Book Cover Image:</label>

                                                @if($book->image_path)
                                                    <div class="mb-2">
                                                        <p>Current image:</p>
                                                        <img src="{{ asset('storage/' . $book->image_path) }}"
                                                            alt="{{ $book->title }}" class="img-thumbnail"
                                                            style="max-height: 200px;">
                                                    </div>
                                                @endif

                                                <input type="file" class="form-control mt-2" name="book_image"
                                                    id="book_image" accept="image/*">
                                                <small class="text-muted">Upload a new cover image or leave blank to keep
                                                    the current one (JPEG, PNG or GIF, max 2MB)</small>

                                                @error('book_image')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="book_file">Book PDF File:</label>

                                                @if($book->file)
                                                    <div class="mb-2">
                                                        <p>Current file: <span class="text-primary">{{ $book->file }}</span></p>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-file-pdf text-danger me-2" style="font-size: 2rem;"></i>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        No PDF file is currently attached to this book.
                                                    </div>
                                                @endif

                                                <input type="file" class="form-control mt-2" name="book_file"
                                                    id="book_file" accept="application/pdf">
                                                <small class="text-muted">Upload a new PDF file or leave blank to keep
                                                    the current one (PDF, max 40MB)</small>

                                                @error('book_file')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button style="background: #11BBAB" type="submit"
                                                class="btn text-white btn-send mt-2 pt-2 btn-block"
                                                value="Update">Update</button>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File size validation for PDF
        const pdfInput = document.getElementById('book_file');
        if (pdfInput) {
            pdfInput.addEventListener('change', function() {
                const maxSize = 40 * 1024 * 1024; // 40MB in bytes

                if (this.files.length > 0) {
                    const fileSize = this.files[0].size;
                    if (fileSize > maxSize) {
                        alert('Error: The selected PDF file exceeds the maximum size limit of 40MB.');
                        this.value = ''; // Clear the input
                    }
                }
            });
        }

        // File size validation for image
        const imageInput = document.getElementById('book_image');
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes

                if (this.files.length > 0) {
                    const fileSize = this.files[0].size;
                    if (fileSize > maxSize) {
                        alert('Error: The selected image file exceeds the maximum size limit of 2MB.');
                        this.value = ''; // Clear the input
                    }
                }
            });
        }
    });
</script>
@endsection