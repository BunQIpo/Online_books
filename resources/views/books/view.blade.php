@extends('layouts.app')

@inject('carbon', 'Carbon\Carbon')
@section('title', 'Reading: ' . $book->title)

@section('styles')
<style>
  /* General styles */
  body {
    background-color: #f8f9fa;
  }

  /* PDF viewer container */
  .pdf-container {
    position: relative;
    overflow: hidden;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    width: 100%;
    margin-bottom: 2rem;
  }

  /* Responsive toolbar */
  .pdf-toolbar {
    padding: 14px 16px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    border-radius: 10px 10px 0 0;
  }

  .pdf-title {
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
  }

  .pdf-controls {
    display: flex;
    gap: 8px;
  }

  .pdf-button {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: white;
    width: 38px;
    height: 38px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
  }

  .pdf-button:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: white;
  }

  /* Responsive PDF viewer */
  .pdf-viewer-wrapper {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 140%; /* Default aspect ratio for mobile */
  }

  .pdf-viewer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
  }

  /* Overdue notification styling */
  .overdue-card {
    background: linear-gradient(to right, #ff4b2b, #ff416c);
    color: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(255, 75, 43, 0.3);
    padding: 25px 20px;
    text-align: center;
  }

  .overdue-icon {
    font-size: 3rem;
    margin-bottom: 15px;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
  }

  /* PDF header with title and limited permissions banner */
  .pdf-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background-color: #10b3a1;
    border-radius: 10px 10px 0 0;
    color: white;
  }

  .pdf-header-icon {
    font-size: 1.5rem;
  }

  .pdf-header-title {
    font-size: 1.25rem;
    font-weight: 600;
    flex-grow: 1;
  }

  .pdf-header-actions {
    display: flex;
    gap: 0.5rem;
  }

  .pdf-header-btn {
    background-color: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    width: 34px;
    height: 34px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
  }

  .pdf-header-btn:hover {
    background-color: rgba(255, 255, 255, 0.3);
    color: white;
  }

  /* Permission warning bar */
  .permission-warning {
    background-color: #333;
    color: white;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    font-size: 0.875rem;
    justify-content: space-between;
  }

  .permission-warning-text {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .permission-warning-link {
    color: #11BBAB;
    text-decoration: none;
  }

  .permission-warning-link:hover {
    text-decoration: underline;
    color: #0ea395;
  }

  .permission-warning-close {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
  }

  .permission-warning-close:hover {
    color: white;
  }

  /* Responsive media queries */
  @media (min-width: 576px) {
    .pdf-title {
      max-width: 300px;
    }

    .pdf-viewer-wrapper {
      padding-bottom: 120%; /* Tablet aspect ratio */
    }
  }

  @media (min-width: 768px) {
    .pdf-viewer-wrapper {
      padding-bottom: 100%; /* Medium screens */
    }
  }

  @media (min-width: 992px) {
    .pdf-title {
      max-width: 400px;
    }

    .pdf-viewer-wrapper {
      padding-bottom: 75%; /* Desktop aspect ratio */
      height: 75vh; /* Allow taller on larger screens */
    }
  }

  @media (min-width: 1200px) {
    .pdf-viewer-wrapper {
      height: 80vh; /* Allow taller on larger screens */
    }
  }

  /* Watermark for borrowed books */
  .pdf-watermark {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: rgba(17, 187, 171, 0.15);
    color: var(--primary-color);
    padding: 8px 15px;
    border-radius: 30px;
    font-size: 0.8rem;
    z-index: 1000;
    backdrop-filter: blur(5px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: opacity 0.3s ease;
    opacity: 0.7;
  }

  .pdf-watermark:hover {
    opacity: 1;
  }

  /* Special styling for admin watermark */
  .pdf-watermark.admin-watermark {
    background-color: rgba(0, 123, 255, 0.15);
    color: #0d6efd;
    font-weight: 600;
  }

  /* Admin Banner Styling */
  .admin-banner {
    background-color: #e3f6fc;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-left: 4px solid #11BBAB;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .admin-banner-icon {
    background-color: rgba(17, 187, 171, 0.2);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #11BBAB;
    flex-shrink: 0;
    font-size: 1.2rem;
  }

  .admin-banner-text {
    flex-grow: 1;
    color: #333;
    font-size: 0.95rem;
  }

  .admin-banner-text span {
    font-weight: 600;
    color: #11BBAB;
  }

  .admin-banner-action {
    flex-shrink: 0;
  }

  @media (max-width: 768px) {
    .admin-banner {
      flex-direction: column;
      align-items: flex-start;
    }

    .admin-banner-action {
      align-self: flex-start;
      margin-top: 0.5rem;
      width: 100%;
    }

    .admin-banner-action .btn {
      width: 100%;
    }
  }

  /* Security protection - disable print functionality */
  @media print {
    .pdf-viewer-wrapper {
      display: none !important;
    }

    body:after {
      content: "Printing PDF content is not allowed";
      display: block;
      text-align: center;
      font-size: 24px;
      margin-top: 40px;
      color: red;
    }
  }
</style>
@endsection

@section('content')
<?php
use Carbon\Carbon;
$date = Carbon::now();
$date = Carbon::parse($date)->toDateString();
$isAdmin = auth()->user()->role === 'admin';
$hasBorrowed = auth()->user()->booksBorrowed->contains($book->id);

// Only check for overdue if the user has actually borrowed the book
$isOverdue = false;
if ($hasBorrowed) {
    try {
        $isOverdue = auth()->user()->getExpirydate($book->id) <= $date;
    } catch (\Exception $e) {
        // Handle case where getExpirydate fails
        $isOverdue = false;
    }
}
?>

<div class="container py-4">
  @if($isAdmin && !$hasBorrowed)
    <!-- Admin PDF View with permissions warning -->
    <div class="row mb-3">
      <div class="col-12">
        <div class="pdf-container">
          <!-- PDF Header with title and controls -->
          <div class="pdf-header">
            <div class="pdf-header-icon">
              <i class="fas fa-book-reader"></i>
            </div>
            <div class="pdf-header-title">{{ $book->title }}</div>
            <div class="pdf-header-actions">
              <a href="{{ route('books.show', $book->id) }}" class="pdf-header-btn" title="Back">
                <i class="fas fa-arrow-left"></i>
              </a>
              <a href="{{ url('/books/download/' . $book->id) }}" class="pdf-header-btn" title="Download" id="download-btn">
                <i class="fas fa-download"></i>
              </a>
              <button class="pdf-header-btn" title="Fullscreen" id="fullscreen-btn">
                <i class="fas fa-expand"></i>
              </button>
            </div>
          </div>

          <!-- Permissions Warning Banner -->
          <div class="permission-warning" id="permission-warning">
            <div class="permission-warning-text">
              <i class="fas fa-lock"></i>
              This file has limited permissions. You may not have access to some features.
              <a href="#" class="permission-warning-link ms-1">View permissions</a>
            </div>
            <button class="permission-warning-close" id="close-permission-warning">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- PDF Viewer -->
          <div class="pdf-viewer-wrapper" id="viewer-container">
            <embed
              src="/assets/{{$book->file}}#toolbar=0&navpanes=0"
              class="pdf-viewer"
              id="pdf-viewer"
              type="application/pdf"
              disablesave="true"
              disablecopy="true"
              disableprint="true"
              disablemodify="true"
            />
          </div>
        </div>

        <div class="pdf-watermark admin-watermark">
          <i class="fas fa-shield-alt me-1"></i>
          Admin View: {{ auth()->user()->name }} | Protected Content
        </div>
      </div>
    </div>
  @elseif($isOverdue)
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="overdue-card">
          <div class="overdue-icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <h2 class="mb-3">Your Book is Overdue!</h2>
          <p class="lead mb-1">Extend your loan period or return this book.</p>
          <p class="mb-4">You will be charged {{number_format($book->credit_price/3,2)}} credits.</p>

          <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
            <form action="{{ route('books.extend', $book->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-warning">
                <i class="fas fa-calendar-plus me-2"></i> Extend Loan
              </button>
            </form>

            <form action="{{ route('books.return', $book->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-danger">
                <i class="fas fa-undo-alt me-2"></i> Return Book
              </button>
            </form>

            <a href="{{ route('books.show', $book->id) }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left me-2"></i> Back
            </a>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="row">
      <div class="col-12">
        <div class="pdf-container">
          <div class="pdf-toolbar">
            <div class="pdf-title">
              <i class="fas fa-book-reader me-2"></i> {{ $book->title }}
            </div>
            <div class="pdf-controls">
              <a href="{{ route('books.show', $book->id) }}" class="pdf-button" title="Back to Details">
                <i class="fas fa-arrow-left"></i>
              </a>
              <a href="{{ url('/books/download/' . $book->id) }}" class="pdf-button" title="Download PDF" id="download-btn">
                <i class="fas fa-download"></i>
              </a>
              <button id="fullscreen-btn" class="pdf-button" title="Toggle Fullscreen">
                <i class="fas fa-expand"></i>
              </button>
            </div>
          </div>

          <div class="pdf-viewer-wrapper" id="viewer-container">
            <embed
              src="/assets/{{$book->file}}#toolbar=0&navpanes=0"
              class="pdf-viewer"
              id="pdf-viewer"
              type="application/pdf"
              disablesave="true"
              disablecopy="true"
              disableprint="true"
              disablemodify="true"
            />
          </div>
        </div>

        <div class="pdf-watermark {{ $isAdmin ? 'admin-watermark' : '' }}">
          @if($isAdmin && !$hasBorrowed)
            <i class="fas fa-shield-alt me-1"></i>
            Admin View: {{ auth()->user()->name }} | Protected Content
          @else
            <i class="fas fa-user-shield me-1"></i>
            Borrowed by: {{ auth()->user()->name }} | Protected Content
          @endif
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const downloadBtn = document.getElementById('download-btn');
        const viewerContainer = document.getElementById('viewer-container');
        const pdfViewer = document.getElementById('pdf-viewer');
        const watermark = document.querySelector('.pdf-watermark');
        const isAdmin = {{ $isAdmin ? 'true' : 'false' }};

        // Handle permission warning banner
        const permissionWarning = document.getElementById('permission-warning');
        const closeWarningBtn = document.getElementById('close-permission-warning');

        if (closeWarningBtn && permissionWarning) {
          closeWarningBtn.addEventListener('click', function() {
            permissionWarning.style.display = 'none';
          });
        }

        // Handle fullscreen functionality
        fullscreenBtn.addEventListener('click', function() {
          if (!document.fullscreenElement) {
            // Try different fullscreen methods for cross-browser support
            if (viewerContainer.requestFullscreen) {
              viewerContainer.requestFullscreen();
            } else if (viewerContainer.webkitRequestFullscreen) {
              viewerContainer.webkitRequestFullscreen();
            } else if (viewerContainer.msRequestFullscreen) {
              viewerContainer.msRequestFullscreen();
            }
            fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
          } else {
            if (document.exitFullscreen) {
              document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
              document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
              document.msExitFullscreen();
            }
            fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
          }
        });

        // Update button icon when fullscreen state changes
        document.addEventListener('fullscreenchange', updateFullscreenButton);
        document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
        document.addEventListener('mozfullscreenchange', updateFullscreenButton);
        document.addEventListener('MSFullscreenChange', updateFullscreenButton);

        function updateFullscreenButton() {
          if (document.fullscreenElement) {
            fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
          } else {
            fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
          }
        }

        // Add loading indicator for download button
        if (downloadBtn) {
          downloadBtn.addEventListener('click', function(e) {
            // Show a loading state while downloading
            const originalIcon = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            // Restore after 3 seconds or when window regains focus
            setTimeout(() => {
              downloadBtn.innerHTML = originalIcon;
            }, 3000);

            // For best UX, don't prevent default to allow download to start
          });
        }

        // Detect mobile devices and adjust the viewer
        function isMobileDevice() {
          return (window.innerWidth <= 768 ||
                 navigator.userAgent.match(/Android/i) ||
                 navigator.userAgent.match(/iPhone|iPad|iPod/i));
        }

        // Adjust height for mobile devices
        function adjustViewerHeight() {
          if (isMobileDevice()) {
            // On mobile, make it fill most of the screen height
            viewerContainer.style.height = (window.innerHeight - 150) + 'px';
            viewerContainer.style.paddingBottom = '0';
          } else {
            // On desktop, make it taller for better reading
            viewerContainer.style.height = (window.innerHeight - 120) + 'px';
            viewerContainer.style.paddingBottom = '0';
          }

          // Make watermark fade out after a delay on desktop
          if (!isMobileDevice() && watermark) {
            setTimeout(() => {
              watermark.style.opacity = '0.3';
            }, 3000);

            // Show on hover
            watermark.addEventListener('mouseenter', () => {
              watermark.style.opacity = '1';
            });

            watermark.addEventListener('mouseleave', () => {
              watermark.style.opacity = '0.3';
            });
          }
        }

        // Prevent right click on the PDF viewer to disable "Save as" option
        pdfViewer.addEventListener('contextmenu', function(e) {
          e.preventDefault();
          return false;
        });

        // Track reading session
        const startTime = new Date();

        // Record reading session data when user leaves page
        window.addEventListener('beforeunload', function() {
          const duration = Math.floor((new Date() - startTime) / 1000); // Duration in seconds
          console.log(`Reading session ended: ${duration} seconds`);

          // For admin users, we could record statistics differently
          if (isAdmin) {
            console.log('Admin review session recorded');
          }
        });

        // Run initially and on resize
        adjustViewerHeight();
        window.addEventListener('resize', adjustViewerHeight);
      });
    </script>
  @endif
</div>
@endsection