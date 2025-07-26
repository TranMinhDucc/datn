@extends('layouts.client')

@section('title', 'Blog')

@section('content')
<section class="section-b-space pt-0">
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Blog Left Sidebar</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
                        <li class="breadcrumb-item active"> <a href="#">Blog Left Sidebar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space pt-0">
    <div class="custom-container container blog-page">
        <div class="row gy-4">
            <div class="col-xl-9 col-lg-8 ratio50_2">
                <div class="row gy-4 sticky">
                    @forelse($blogs as $blog)
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img">
                                    <img class="img-fluid bg-img"
                                        src="{{ asset('storage/' . ($blog->thumbnail)) }}"
                                        alt="{{ $blog->title }}">
                                </div>
                            </div>
                            <div class="blog-content">
                                <span class="blog-date">
                                    {{ $blog->created_at->format('M d, Y') }}
                                </span>
                                <a href="{{ route('client.blog.show', $blog) }}">
                                    <h4>{{ $blog->title }}</h4>
                                </a>
                                <p>{{ $blog->excerpt }}</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2">
                                        <img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/1.jpg') }}" alt="">
                                        <h6>by {{ $blog->author->username ?? 'Unknown' }}</h6>
                                    </div>
                                    <a href="{{ route('client.blog.show', $blog) }}">Đọc thêm..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="iconsax" data-icon="document-text" style="font-size: 48px; color: #b5b5c3;"></i>
                            <h4 class="mt-3 text-muted">Không có bài viết nào được tìm thấy</h4>
                            <p class="text-gray-600">Vui lòng thử lại sau hoặc thay đổi bộ lọc.</p>
                        </div>
                    </div>
                    @endforelse
                    <div class="pagination-wrap mt-0">
                        {{ $blogs->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 order-lg-first">
                <div class="blog-sidebar">
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
                                    <h5>Danh mục</h5>
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
                                    <h5>Bài viết nổi bật</h5>
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
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Tag phổ biến</h5>
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
                        </div>
                        <div class="col-12">
                            <div class="sidebar-box">
                                <div class="sidebar-title">
                                    <div class="loader-line"></div>
                                    <h5>Theo dõi chúng tôi</h5>
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
                                    src="{{ asset('assets/client/images/other-img/blog-offer.jpg') }}"
                                    alt=""></div>
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