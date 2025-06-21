@extends('layouts.app')

@section('author', 'New signing')

@section('content')
    <div class="container mt-5">
        {{--
        * This template is adapted from Bootstrap
        * https://getbootstrap.com/
        * Displays the form which allows user to create a new Book
        --}}
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card mt-2 mx-auto p-4 bg-light">
                    <div class="card-body bg-light">

                        <div class="container">
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" id="bookForm">
                                @php
                                    // Try to set PHP limits directly
                                    @ini_set('upload_max_filesize', '40M');
                                    @ini_set('post_max_size', '42M');
                                    @ini_set('memory_limit', '512M');
                                    @ini_set('max_execution_time', 300);
                                    @ini_set('max_input_time', 300);
                                @endphp
                                @csrf
                                <!-- Store CSRF token in a variable for AJAX requests -->
                                <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}" />

                                <div class="controls">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="mb-2"><span style="color:#11BBAB" class="font-italic me-1">Create New</span> Book</h3>
                                            @if(!empty($author['name']))
                                                <h6>by: {{$author->name }}</h6>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mt-1">
                                                <label for="title">Title:</label>
                                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" />
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-1">
                                                <label for="genre">Genre:</label>
                                                <input type="text" name="genre" id="genre" class="form-control @error('genre') is-invalid @enderror" value="{{ old('genre') }}" />
                                                @error('genre')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="author_id">Author:</label>
                                                <select name="author_id" id="author_id" class="form-control @error('author_id') is-invalid @enderror">
                                                    <option value="0">-- Select Author (Optional) --</option>
                                                    @foreach($authors as $authorItem)
                                                        <option value="{{ $authorItem->id }}" {{ (isset($author) && $author->id == $authorItem->id) ? 'selected' : '' }}>
                                                            {{ $authorItem->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description:</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="5">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="credit_price">Credit price:</label>
                                                <input type="number" step="0.01" class="form-control @error('credit_price') is-invalid @enderror" name="credit_price" id="credit_price" value="{{ old('credit_price') }}">
                                                @error('credit_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">                                                <div class="form-group">
                                                    <label for="book_image">Book Cover Image:</label>
                                                    <input type="file" class="form-control @error('book_image') is-invalid @enderror" name="book_image"
                                                        id="book_image" accept="image/*">
                                                    <small class="text-muted">Upload a cover image for the book (optional,
                                                        JPEG, PNG or GIF, max 2MB)</small>

                                                    @error('book_image')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="pdf_file">PDF File:</label>
                                                    <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" name="pdf_file"
                                                        id="pdf_file" accept="application/pdf">
                                                    <small class="text-muted">Upload a PDF file for the book (optional, max 40MB)</small>

                                                    <!-- Hidden fields for base64 encoded PDF data -->
                                                    <input type="hidden" name="pdf_file_base64" id="pdf_file_base64">
                                                    <input type="hidden" name="pdf_file_name" id="pdf_file_name">

                                                    <div class="alert alert-info mt-2 pdf-info d-none" id="pdfInfo">
                                                        <span id="pdfSize"></span> - Using: <span id="uploadMethod"></span>
                                                        <div class="progress mt-2">
                                                            <div class="progress-bar" role="progressbar" id="pdfProgress"></div>
                                                        </div>
                                                    </div>

                                                    @error('pdf_file')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Default status value -->
                                        <input type="hidden" name="status" value="availiable">

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <button id="submitButton" style="background: #11BBAB" type="submit"
                                                    class="btn text-white">Create Book</button>
                                                <a href="{{ route('admin.show', Auth::user()->id) }}" class="btn btn-secondary ms-2">Cancel</a>
                                            </div>
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
    // Function to refresh CSRF token to prevent 419 Page Expired errors
    async function refreshCsrfToken() {
        try {
            const response = await fetch('{{ route('refresh.csrf') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.getElementById('csrf-token').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                document.getElementById('csrf-token').value = data.token;
                return true;
            }
            return false;
        } catch (error) {
            console.error('Error refreshing CSRF token:', error);
            return false;
        }
    }

    // Smart PDF upload handling
    document.getElementById('pdf_file').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) {
            document.getElementById('pdfInfo').classList.add('d-none');
            document.getElementById('pdf_file_base64').value = '';
            document.getElementById('pdf_file_name').value = '';
            return;
        }

        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        document.getElementById('pdfSize').textContent = `${file.name} (${fileSizeMB} MB)`;

        // Check if file is larger than allowed limit
        if (file.size > 40 * 1024 * 1024) {
            alert(`The selected PDF file is ${fileSizeMB} MB, which exceeds the maximum allowed size of 40MB. Please select a smaller file.`);
            this.value = ''; // Clear the input
            document.getElementById('pdfInfo').classList.add('d-none');
            return;
        }

        document.getElementById('pdfInfo').classList.remove('d-none');

        // For small files (under 8MB), use base64 encoding
        if (file.size < 8 * 1024 * 1024) {
            document.getElementById('uploadMethod').textContent = 'Base64 Encoding (For Small Files)';

            const reader = new FileReader();
            reader.onload = function(e) {
                const base64Data = e.target.result;
                document.getElementById('pdf_file_base64').value = base64Data;
                document.getElementById('pdf_file_name').value = file.name;
                document.getElementById('pdfProgress').style.width = '100%';
                document.getElementById('pdfProgress').textContent = 'Ready to upload';
            };

            reader.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentLoaded = Math.round((e.loaded / e.total) * 100);
                    document.getElementById('pdfProgress').style.width = percentLoaded + '%';
                    document.getElementById('pdfProgress').textContent = percentLoaded + '%';
                }
            };

            // Start reading the file as a data URL (base64)
            reader.readAsDataURL(file);
        } else {
            // For larger files, use regular file upload
            document.getElementById('uploadMethod').textContent = 'Regular File Upload';
            document.getElementById('pdf_file_base64').value = '';
            document.getElementById('pdfProgress').style.width = '100%';
            document.getElementById('pdfProgress').textContent = 'Ready to upload';
        }
    });    // Handle form submission with CSRF validation
    document.getElementById('bookForm').addEventListener('submit', function(event) {
        // Check if PDF file is being uploaded
        const pdfFile = document.getElementById('pdf_file').files[0];
        const base64Data = document.getElementById('pdf_file_base64').value;

        // CSRF token
        const csrfToken = document.getElementById('csrf-token').value;

        // Check file size again to be safe
        if (pdfFile && pdfFile.size > 40 * 1024 * 1024) {
            event.preventDefault();
            const fileSizeMB = (pdfFile.size / (1024 * 1024)).toFixed(2);
            alert(`Cannot upload: The PDF file is ${fileSizeMB} MB, which exceeds the maximum allowed size of 40MB.`);
            return false;
        }

        // Handle larger file uploads with special handling to avoid 419 errors
        if (pdfFile && pdfFile.size > 5 * 1024 * 1024) {
            event.preventDefault(); // Prevent default form submission

            // Disable the submit button
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            submitButton.innerHTML = 'Preparing submission...';

            // Get form data
            const formData = new FormData(this);

            // Display file size info
            const fileSizeMB = (pdfFile.size / (1024 * 1024)).toFixed(2);
            submitButton.innerHTML = `Creating... Uploading PDF (${fileSizeMB} MB)`;

            // Refresh CSRF token first, then submit the form
            refreshCsrfToken().then(() => {
                // Get the refreshed token
                const refreshedToken = document.getElementById('csrf-token').value;

                // Use fetch API to submit the form with proper CSRF token
                return fetch('{{ route('books.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': refreshedToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    credentials: 'same-origin'
                });
            })
            .then(response => {
                if (response.redirected) {
                    // If we got redirected, follow the redirect
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data) {
                    if (data.success) {
                        // Redirect to success page
                        window.location.href = data.redirect;
                    } else {
                        // Show error
                        alert('Error: ' + (data.message || 'An error occurred during submission.'));
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Create Book';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during submission. Please try again.');
                submitButton.disabled = false;
                submitButton.innerHTML = 'Create Book';
            });

            return false;
        }

        // For smaller files or no PDF, use regular form submission
        // Disable the submit button
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;

        if (pdfFile) {
            // If PDF is being uploaded, show more detailed status
            const fileSizeMB = (pdfFile.size / (1024 * 1024)).toFixed(2);
            submitButton.innerHTML = `Creating... Uploading PDF (${fileSizeMB} MB)`;

            // For small files, we're using base64, so remove the file input
            if (base64Data && pdfFile.size < 8 * 1024 * 1024) {
                // Remove the file input from the form to prevent regular upload
                this.querySelector('#pdf_file').disabled = true;
            }
        } else {
            submitButton.innerHTML = 'Creating...';
        }
    });
</script>
@endsection