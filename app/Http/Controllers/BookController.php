<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class BookController extends Controller
{

    /**
     * Display a listing of the books where title matches the search word
     * This was adapted from a post from Kingcosult on Oct 12 2020 on Dev Community here:
     * https://dev.to/kingsconsult/how-to-implement-search-functionality-in-laravel-8-and-laravel-7-downwards-3g76
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $sort = $request->sort ?? 'title';
        $direction = $request->direction ?? 'asc';

        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['title', 'credit_price', 'created_at', 'genre'];
        $sort = in_array($sort, $allowedSortFields) ? $sort : 'title';

        // Validate direction
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        $books = Book::where(
            [
                ['title', '!=', Null],
                [
                    function ($query) use ($request) {
                        if ($term = $request->term) {
                            $query->orWhere('title', 'LIKE', '%' . $term . '%')->get();
                        }
                    }
                ]
            ]
        )
            ->orderBy(
                $sort,
                $direction
            )
            ->paginate(
                50
            );
        return view(
            'books/index',
            [
                'books' => $books
            ]
        );
    }

    /**
     * Show the form for creating a new book.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create($id): View
    {
        if ($id == 0) {
            return view('books.create');
        }
        $author = Author::findorfail($id);
        return view('books.create', compact('author'));
    }

    /**
     * Store a newly created book in storage.
     * This was adapted from a youtube tutorial by  Victor Gondalez on freeCodeCamp
     * youtube channel here:
     * https://www.youtube.com/watch?v=ImtZ5yENzgE&amp;t=2635s
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $id = $request->author_id;

        // Refresh the CSRF token to prevent Token Mismatch Exception
        $request->session()->regenerateToken();

        try {
            $request->validate(
                [
                    'title' => 'required',
                    'genre' => 'required',
                    'description' => 'required',
                    'credit_price' => 'required|numeric|min:0',
                    'book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'pdf_file' => 'nullable|file|mimes:pdf|max:40960' // 40MB max for PDF files
                ]
            );

            $data = $request->all();
            $data['user_id'] = $user->id;

            // Set default status if not provided
            if (!isset($data['status'])) {
                $data['status'] = 'availiable';
            }

            // Handle image upload if provided
            if ($request->hasFile('book_image')) {
                try {
                    Log::info('Processing book cover image upload');

                    // Get the uploaded file
                    $imageFile = $request->file('book_image');

                    // Log uploaded file details
                    Log::info('Upload details - Name: ' . $imageFile->getClientOriginalName() .
                             ', Size: ' . $imageFile->getSize() .
                             ', MIME: ' . $imageFile->getMimeType());

                    // Upload using helper
                    $imagePath = \App\Helpers\ImageHelper::uploadImage($imageFile);

                    if ($imagePath) {
                        Log::info('Image uploaded successfully: ' . $imagePath);
                        $data['image_path'] = $imagePath;
                    } else {
                        Log::error('Image upload failed - Helper returned null');
                        return back()
                            ->withInput()
                            ->with('error', 'Failed to upload image. Please try again with a different image.');
                    }
                } catch (\Exception $e) {
                    Log::error('Image upload exception: ' . $e->getMessage());
                    return back()
                        ->withInput()
                        ->with('error', 'Error processing image upload: ' . $e->getMessage());
                }
            } else {
                Log::info('No book cover image provided');
            }

            // Handle PDF file upload - either from file input or base64 encoded
            $pdfProcessed = false;
            $filename = null; // Initialize filename variable

            // First try base64 method (for small files)
            if ($request->filled('pdf_file_base64') && $request->filled('pdf_file_name')) {
                try {
                    Log::info('Processing PDF from base64 data');

                    // Get the base64 data and filename
                    $base64Data = $request->input('pdf_file_base64');
                    $fileName = $request->input('pdf_file_name');

                    // Strip the data URI prefix if present
                    if (strpos($base64Data, ';base64,') !== false) {
                        $base64Data = explode(';base64,', $base64Data)[1];
                    }

                    // Convert to binary
                    $pdfBinary = base64_decode($base64Data);

                    // Calculate size for logging
                    $fileSize = strlen($pdfBinary);
                    $fileSizeMB = round($fileSize / 1048576, 2);

                    Log::info('PDF Upload details (base64) - Name: ' . $fileName .
                             ', Size: ' . $fileSize . ' bytes (' . $fileSizeMB . ' MB)');

                    // Generate a unique filename
                    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $fileName);

                    // Make sure the assets directory exists
                    if (!file_exists(public_path('assets'))) {
                        mkdir(public_path('assets'), 0755, true);
                    }

                    // Write the file
                    file_put_contents(public_path('assets/' . $filename), $pdfBinary);

                    $pdfProcessed = true;
                    Log::info('PDF saved successfully (base64): ' . $filename . ' (' . $fileSizeMB . ' MB)');
                } catch (\Exception $e) {
                    Log::error('Base64 PDF processing error: ' . $e->getMessage());
                }
            }

            // Fall back to standard file upload if base64 wasn't processed
            if (!$pdfProcessed && $request->hasFile('pdf_file')) {
                try {
                    Log::info('Processing PDF file upload (standard method)');

                    // Get the uploaded file
                    $pdfFile = $request->file('pdf_file');

                    // Log uploaded file details
                    Log::info('PDF Upload details - Name: ' . $pdfFile->getClientOriginalName() .
                             ', Size: ' . $pdfFile->getSize() .
                             ', MIME: ' . $pdfFile->getMimeType());

                    // Get file size in MB for display
                    $fileSizeMB = round($pdfFile->getSize() / 1048576, 2);

                    // Generate a unique filename with original extension
                    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $pdfFile->getClientOriginalName());

                    // Make sure the assets directory exists
                    if (!file_exists(public_path('assets'))) {
                        mkdir(public_path('assets'), 0755, true);
                    }

                    // Move the file
                    $pdfFile->move('assets', $filename);

                    // Store the file path in data array
                    $data['file'] = $filename;

                    Log::info('PDF uploaded successfully: ' . $filename . ' (' . $fileSizeMB . ' MB)');
                    $pdfProcessed = true;
                } catch (\Exception $e) {
                    Log::error('PDF upload exception: ' . $e->getMessage());
                    return back()
                        ->withInput()
                        ->with('error', 'Error processing PDF upload: ' . $e->getMessage());
                }
            } else {
                Log::info('No PDF file provided');
            }

            // Store the file path in data array if PDF was processed successfully
            if ($pdfProcessed && $filename) {
                $data['file'] = $filename;
                Log::info('PDF file path added to book data: ' . $filename);
            }

            // Create the book
            Log::info('Creating book with data: ' . json_encode($data));
            $book = Book::create($data);

            // Associate with author if provided
            if ($id != 0) {
                $author = Author::findOrFail($id);
                $author->booksWritten()->save($book);
                Log::info('Associated book with author ID: ' . $id);
            }

            // Set success message
            $successMessage = 'Book created successfully!';

            // Add PDF info to success message if a PDF was uploaded
            if (isset($data['file'])) {
                $successMessage .= ' PDF file was also uploaded.';
            }

            // Check if the request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect' => route('admin.show', ['user' => $user->id])
                ]);
            }

            return redirect()->route('admin.show', ['user' => $user->id])
                ->with('message', $successMessage);
        } catch (\Exception $e) {
            Log::error('Book creation error: ' . $e->getMessage());

            // Check if the request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the book: ' . $e->getMessage()
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', 'An error occurred while creating the book: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Book $book): View
    {
        return view(
            'books.show',
            compact('book')
        );
    }

    /**
     * Show the form for editing the specified book.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Book $book): View
    {
        return view(
            'books.edit',
            compact('book')
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $request->validate(
            [
                'title' => 'required',
                'genre' => 'required',
                'description' => 'required',
                'credit_price' => 'required',
                'book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]
        );

        $data = $request->all();

        // Handle image upload if provided
        if ($request->hasFile('book_image')) {
            try {
                // Delete old image if exists
                if ($book->image_path) {
                    \App\Helpers\ImageHelper::deleteImage($book->image_path);
                }

                // Upload new image
                $imagePath = \App\Helpers\ImageHelper::uploadImage($request->file('book_image'));
                if ($imagePath) {
                    $data['image_path'] = $imagePath;
                } else {
                    // Log error and notify user
                    Log::error('Failed to upload image for book: ' . $book->id);
                    return back()->with('error', 'Failed to upload image. Please try again with a different image.');
                }
            } catch (\Exception $e) {
                Log::error('Image upload error: ' . $e->getMessage());
                return back()->with('error', 'Error processing image upload. Please try again.');
            }
        }

        $book->update($data);
        $user = Auth::user();
        return redirect()->route('books.show', compact('book'));
    }

    /**
     * Check if the book is not borrowed by any user
     * Remove the specified book from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Book $book): RedirectResponse
    {
        $user = Auth::user();

        // Check if the user is an admin
        $isAdmin = ($user->role === 'admin');

        // Initialize message variable
        $message = 'Book deleted successfully.';

        if ($book->borrowedBy()->count() == 0 || $isAdmin) {
            // If book is borrowed and the user is admin, first detach the book from all users
            if ($book->borrowedBy()->count() > 0 && $isAdmin) {
                // Delete all book_user relationships for this book
                DB::table('book_user')->where('book_id', $book->id)->delete();
                $message = 'Book removed from all borrowers and deleted successfully.';
            }

            $book->delete();
            return redirect()->route('admin.show', compact('user'))->with('message', $message);
        } else {
            return redirect()->route('admin.show', compact('user'))->with('message', 'Cannot delete until book is returned. You can make the book unavailable first.');
        }
    }

    /**
     * Borrow the specified book
     * Check if the User has already borrowed the book
     * Attach the book to the bookCreated realationship
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function borrow(Book $book): RedirectResponse
    {
        $user = Auth::user();

        // Check if user has borrowed the book using DB query instead of relationship
        $hasBorrowed = DB::table('book_user')
            ->where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->exists();

        if ($hasBorrowed) {
            return redirect()->back()->with('message', 'Already borrowed');
        }

        if ($book->credit_price > $user->credits) {
            return redirect()->back()->with('message', 'Not enough credits');
        }

        // Attach book to user using DB query
        DB::table('book_user')->insert([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Deduct the user credits by the value of book credit price
        User::find($user->id)->decrement('credits', $book->credit_price);

        return redirect()->route('user.books', compact('user'))->with('message', 'Book Borrowed');
    }


    /**
     * Borrow the specified book
     * Check if the User has already borrowed the book
     * Check if the book is overdue
     * Attach the book to the bookCreated realationship
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function return(Book $book): RedirectResponse
    {
        $user = Auth::user();

        // Get the borrow record
        $borrowRecord = DB::table('book_user')
            ->where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if (!$borrowRecord) {
            return redirect()->back()->with('message', 'Book not borrowed by you');
        }

        // Calculate expiry date (7 days from created_at)
        $borrowDate = Carbon::parse($borrowRecord->created_at);
        $expiryDate = $borrowDate->copy()->addDays(7);

        // Check if book is overdue, deduct charges
        if ($expiryDate < Carbon::now()) {
            User::find($user->id)->decrement('credits', $book->credit_price / 3);

            // Remove the book_user relationship
            DB::table('book_user')
                ->where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->delete();

            return redirect()->route('user.books', compact('user'))->with('message', 'Book Returned. Late fee: ' . number_format($book->credit_price / 3, 2) . ' credits deducted');
        } else {
            // Remove the book_user relationship
            DB::table('book_user')
                ->where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->delete();

            return redirect()->route('user.books', compact('user'))->with('message', 'Book Returned');
        }
    }

    /**
     * Update the status of the specified book
     * Check the current status of specified book and toggle the status
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status(Book $book): RedirectResponse
    {

        $user = Auth::user();
        if ($book->status == 'availiable') {
            $book->update(['status' => 'unavailiable']);
        } else {
            $book->update(['status' => 'availiable']);
        }
        return redirect()->route('books.show', compact('book'))->with('message', 'Status Changed');
    }

    /**
     * Upload the book content file with improved error handling and file size validation
     * Based on the youtube channel Web TecH Knowledge tutorial:
     * https://www.youtube.com/watch?v=IYswY0Jgup4
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request, Book $book): RedirectResponse
    {
        try {
            // Validate the file - increasing max size to 20MB
            $validatedData = $request->validate([
                'file' => 'required|file|max:20480|mimes:pdf,epub,doc,docx,txt'
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Get file size in MB for display
                $fileSizeMB = round($file->getSize() / 1048576, 2);

                // Generate a unique filename with original extension
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());

                // Move the file
                $file->move('assets', $filename);

                // Remove old file if it exists
                if ($book->file && file_exists(public_path('assets/' . $book->file))) {
                    unlink(public_path('assets/' . $book->file));
                }

                // Update book record
                $book->file = $filename;
                $book->save();

                return redirect()->route('books.show', ['book' => $book->id])
                    ->with('message', "File added successfully ($fileSizeMB MB)");
            }

            return redirect()->back()->with('error', 'No file was provided');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            $error = $e->validator->errors()->first();

            // Check if it's a file size error
            if (strpos($error, 'file may not be greater than') !== false) {
                return redirect()->back()->with('error',
                    'The file is too large (max 20MB). Current PHP upload limit is ' .
                    ini_get('upload_max_filesize') . '. Contact your administrator to increase this limit.');
            }

            return redirect()->back()->with('error', $error);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during file upload: ' . $e->getMessage());
        }
    }


    /**
     * View the book content file
     *
     * @param  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function view($id): View
    {
        $book = Book::findorfail($id);
        return view('books.view', compact('book'));
    }

    /**
     * Extend the specified book
     * Check if the User has already borrowed the book
     * Re-attach the book to generate a new borrow date
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function extend(Book $book): RedirectResponse
    {
        $user = Auth::user();
        $today = Carbon::now();
        $today = Carbon::parse($today)->toDateString();

        // Get the borrow record
        $borrowRecord = DB::table('book_user')
            ->where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if (!$borrowRecord) {
            return redirect()->back()->with('message', 'Book not borrowed by you');
        }

        // Calculate expiry date (7 days from created_at)
        $borrowDate = Carbon::parse($borrowRecord->created_at);
        $expiryDate = $borrowDate->copy()->addDays(7);
        $expiryDateString = $expiryDate->toDateString();

        // Credits charged for extending a book
        $extend_credits = ($book->credit_price / 3);
        // Late fee charged for overdue books
        $late_fee = 0;

        // If the expiry/return date is less than current date, add late fee charges to extend credits
        if ($expiryDateString < $today) {
            $late_fee = ($book->credit_price / 3);
        }

        $total_credits = $late_fee + $extend_credits;

        if ($total_credits < $user->credits) {
            // Add 7 to created_at field of book_user table of current user
            $newDate = $borrowDate->copy()->addDays(7);

            // Update the new return date
            DB::table('book_user')
                ->where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->update(['created_at' => $newDate]);

            // Format for display
            $total_credits_formatted = number_format($total_credits, 2);

            // Deduct credits
            User::find($user->id)->decrement('credits', $total_credits);

            return redirect()->route('user.books', compact('user'))->with('message', 'Borrowed period extended by 7 days. Extended for: ' . number_format($extend_credits, 2) . ' credits. Extra Charges: ' . number_format($late_fee, 2) . ' credits');
        } else {
            return redirect()->route('user.books', compact('user'))->with('message', 'Not enough credits');
        }
    }

    /**
     * Download the PDF file for a book (admin only)
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($id)
    {
        // This route is already protected by admin middleware, but double-check
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('books.show', $id)->with('error', 'Only administrators can download PDF files.');
        }

        $book = Book::findOrFail($id);

        if (!$book->file) {
            return redirect()->route('books.show', $id)->with('error', 'This book does not have a PDF file.');
        }

        $filePath = public_path('assets/' . $book->file);

        if (!file_exists($filePath)) {
            return redirect()->route('books.show', $id)->with('error', 'PDF file not found.');
        }

        $fileName = preg_replace('/[^a-z0-9]+/', '-', strtolower($book->title)) . '.pdf';

        return response()->download($filePath, $fileName);
    }
}
