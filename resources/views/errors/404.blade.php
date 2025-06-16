@extends('layouts.client')

@section('title', 'my profile')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>404</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"> <a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">404</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-b-space pt-0">
        <div class="custom-container container error-img">
            <div class="row g-4">
                <div class="col-12 px-0">
                    <a href="{{ route('client.home') }}">
                        <img class="img-fluid" src="{{ asset('assets/client/images/other-img/404.png') }}"
                            alt="404 - Page Not Found">
                    </a>
                </div>
                <div class="col-12 text-center">
                    <h2>Page Not Found</h2>
                    <p>The page you are looking for doesn't exist or another error occurred.<br>Go back or head over to the
                        home page.</p>
                    <a class="btn btn_black rounded" href="{{ route('client.home') }}">
                        Back Home Page
                        <svg width="16" height="16" fill="currentColor" class="ms-2" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.498.498 0 0 1 .146.354v.004a.498.498 0 0 1-.146.354l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection