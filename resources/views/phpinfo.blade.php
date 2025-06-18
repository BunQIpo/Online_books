@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">PHP Information</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> This page displays sensitive information about your PHP environment. It should only be accessible to administrators.
                    </div>

                    <div class="php-info-container">
                        {!! $phpinfo !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .php-info-container {
        overflow-x: auto;
    }
    .php-info-container table {
        margin: 1em 0;
        border-collapse: collapse;
        width: 100%;
    }
    .php-info-container table td,
    .php-info-container table th {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .php-info-container table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .php-info-container table tr:hover {
        background-color: #ddd;
    }
    .php-info-container h2 {
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }
    .php-info-container hr {
        display: none;
    }
    .php-info-container a {
        color: var(--primary-color);
    }
</style>
@endsection
