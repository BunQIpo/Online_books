<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ExpiryReminderMail;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Carbon;

class WelcomeController extends Controller
{

  /**
   * Show the Welcome page when a user visits
   * website url
   *
   */
  /**
   * Show the Welcome page with featured books
   *
   * @return \Illuminate\Contracts\View\View
   */
  public function index()
  {
    // Get books for the featured section
    $totalBooks = Book::count();

    if ($totalBooks > 0) {
      // Get newest and most popular books
      $newBooks = Book::latest()->take(2)->get();

      $popularBooks = Book::withCount('borrowedBy')
                        ->orderBy('borrowed_by_count', 'desc')
                        ->take(2)
                        ->get();

      // Combine and ensure we have unique books
      $featuredBooks = $newBooks->concat($popularBooks)->unique('id')->take(4);

      // If we don't have enough books after combining, just get whatever books are available
      if ($featuredBooks->count() < 4 && $totalBooks >= 4) {
        $featuredBooks = Book::inRandomOrder()->take(4)->get();
      }
    } else {
      // No books in the database
      $featuredBooks = collect();
    }

    return view('welcome', [
      'featuredBooks' => $featuredBooks
    ]);
  }
}
