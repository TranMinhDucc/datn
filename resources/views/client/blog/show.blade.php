@extends('layouts.client')

@section('title', 'Blog Details')

@section('content')
<section class="section-b-space pt-0">
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Blog Details</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="{{route('client.home')}}">Home </a></li>
                        <li class="breadcrumb-item active"> <a href="#">Blog Details</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space pt-0">
    <div class="custom-container container blog-page">
        <div class="row gy-4">
            <div class="col-xl-9 col-lg-8 col-12 ratio50_2">
                <div class="row">
                    <div class="col-12">
                        <div class="blog-main-box blog-details">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('storage/' . $blog->thumbnail) }}"
                                        alt=""></div>
                            </div>
                            <div class="blog-content"><span class="blog-date">{{$blog->published_at->format('l, d F Y')}}</span><a
                                    href="#">
                                    <h4>{{$blog->title}}</h4>
                                </a>
                                {!! $blog->content !!}
                                <h5 class="pt-3">Có thể bạn sẽ quan tâm</h5>
                                <ul>
                                    @foreach ($relatedBlogs as $related)
                                    <li>
                                        <a href="{{ route('client.blog.show', $related->slug) }}">
                                            {{ $related->title }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="comments-box">
                                    <h5>Comments </h5>
                                    <ul class="theme-scrollbar">
                                        @forelse($blog->comments->whereNull('parent_id') as $comment)
                                        @include('client.blog.comment', ['comment' => $comment])
                                        @empty
                                        <li>
                                            <div class="comment-items">
                                                <div class="user-content">
                                                    <p>No comments yet. Be the first to comment!</p>
                                                </div>
                                            </div>
                                        </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <h5 class="pt-3">Leave a Comment</h5>

                                <form action="{{ route('client.blog-comment.store', $blog) }}" method="POST">
                                    @csrf
                                    <div class="row gy-3 message-box">
                                        @guest
                                        <div class="col-sm-6">
                                            <label class="form-label">Full Name</label>
                                            <input class="form-control" name="guest_name" type="text" placeholder="Enter your Name" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">Email address</label>
                                            <input class="form-control" name="guest_email" type="email" placeholder="Enter your Email" required>
                                        </div>
                                        @endguest
                                        <div class="col-12">
                                            <label class="form-label">Message</label>
                                            <textarea class="form-control" name="content" rows="6" placeholder="Enter Your Message" required></textarea>
                                        </div>
                                        <input type="hidden" name="parent_id" id="parent_id" value="">
                                        <div class="col-12">
                                            <button class="btn btn_black rounded sm" type="submit">Post Comment</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 order-lg-first col-12">
                <div class="blog-sidebar sticky">
                    <div class="row gy-4">
                        <div class="col-12">
                            <div class="blog-search">
                                <input type="search" placeholder="Search Here..."><i class="iconsax"
                                    data-icon="search-normal-2"></i>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5> Categories</h5>
                                </div>
                                <ul class="categories">
                                    @foreach ($categories as $category)
                                    <li>
                                        <p>{{$category->name}}<span>{{$category->blogs_count}}</span></p>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5> Top Post</h5>
                                </div>
                                <ul class="top-post">
                                    @foreach($topViewedBlogs as $top)
                                    <li>
                                        <img class="img-fluid" src="{{ asset('storage/' . $top->thumbnail) }}" alt="{{ $top->title }}">
                                        <div>
                                            <a href="{{ route('client.blog.show', $top->slug) }}">
                                                <h6>{{ \Illuminate\Support\Str::limit($top->title, 60) }}</h6>
                                            </a>
                                            <p>{{ $top->published_at->format('F d, Y') }}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5> Popular Tags</h5>
                                </div>
                                <ul class="popular-tag">
                                    <li>
                                        <p>T-shirt</p>
                                    </li>
                                    <li>
                                        <p>Handbags </p>
                                    </li>
                                    <li>
                                        <p>Trends </p>
                                    </li>
                                    <li>
                                        <p>Fashion</p>
                                    </li>
                                    <li>
                                        <p>Designer</p>
                                    </li>
                                </ul>
                            </div>
                        </div> -->
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Follow Us</h5>
                                </div>
                                <ul class="social-icon">
                                    <li> <a href="https://www.facebook.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-facebook-f"></i></div>
                                            <h6>Facebook</h6>
                                        </a></li>
                                    <li> <a href="https://www.instagram.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-instagram"> </i></div>
                                            <h6>Instagram</h6>
                                        </a></li>
                                    <li> <a href="https://twitter.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-x-twitter"></i></div>
                                            <h6>Twitter</h6>
                                        </a></li>
                                    <li> <a href="https://www.youtube.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-youtube"></i></div>
                                            <h6>Youtube</h6>
                                        </a></li>
                                    <li> <a href="https://www.whatsapp.com/" target="_blank">
                                            <div class="icon"><i class="fa-brands fa-whatsapp"></i></div>
                                            <h6>Whatsapp</h6>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 d-none d-lg-block">
                            <div class="blog-offer-box"> <img class="img-fluid"
                                    src="{{ asset('assets/client/images/other-img/blog-offer.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')

@endsection