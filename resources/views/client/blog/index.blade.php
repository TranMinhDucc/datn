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
                    @foreach($blogs as $blog)
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
                                    <a href="{{ route('client.blog.show', $blog) }}">Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('assets/client/images/blog/blog-page/2.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="blog-content"><span class="blog-date">May 9, 2018 Stylish</span><a
                                    href="blog-details.html">
                                    <h4>How Black Trans Women Are Redefining Beauty Standards</h4>
                                </a>
                                <p>Sed non mauris vitae erat consequat. Proin gravida nibh vel velit auctor aliquet.
                                    Aenean sollicitudin, lom quis bibenm auctor</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2"><img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/2.jpg') }}" alt="">
                                        <h6>by John wiki on</h6>
                                    </div><a href="blog-details.html"> Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('assets/client/images/blog/blog-page/3.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="blog-content"> <span class="blog-date">May 9, 2018 Stylish</span><a
                                    href="blog-details.html">
                                    <h4>How Black Trans Women Are Redefining Beauty Standards</h4>
                                </a>
                                <p>Sed non mauris vitae erat consequat. Proin gravida nibh vel velit auctor aliquet.
                                    Aenean sollicitudin, lom quis bibenm auctor</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2"><img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/3.jpg') }}" alt="">
                                        <h6>by John wiki on</h6>
                                    </div><a href="blog-details.html"> Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('assets/client/images/blog/blog-page/4.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="blog-content"> <span class="blog-date">May 9, 2018 Stylish</span><a
                                    href="blog-details.html">
                                    <h4>How Black Trans Women Are Redefining Beauty Standards</h4>
                                </a>
                                <p>Sed non mauris vitae erat consequat. Proin gravida nibh vel velit auctor aliquet.
                                    Aenean sollicitudin, lom quis bibenm auctor</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2"><img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/8.jpg') }}" alt="">
                                        <h6>by John wiki on</h6>
                                    </div><a href="blog-details.html"> Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('assets/client/images/blog/blog-page/5.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="blog-content"><span class="blog-date">May 9, 2018 Stylish</span><a
                                    href="blog-details.html">
                                    <h4>How Black Trans Women Are Redefining Beauty Standards</h4>
                                </a>
                                <p>Sed non mauris vitae erat consequat. Proin gravida nibh vel velit auctor aliquet.
                                    Aenean sollicitudin, lom quis bibenm auctor</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2"><img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/9.jpg') }}" alt="">
                                        <h6>by John wiki on</h6>
                                    </div><a href="blog-details.html"> Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('assets/client/images/blog/blog-page/6.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="blog-content"> <span class="blog-date">May 9, 2018 Stylish</span><a
                                    href="blog-details.html">
                                    <h4>How Black Trans Women Are Redefining Beauty Standards</h4>
                                </a>
                                <p>Sed non mauris vitae erat consequat. Proin gravida nibh vel velit auctor aliquet.
                                    Aenean sollicitudin, lom quis bibenm auctor</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2"><img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/10.jpg') }}" alt="">
                                        <h6>by John wiki on</h6>
                                    </div><a href="blog-details.html"> Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('assets/client/images/blog/blog-page/7.jpg') }}"
                                        alt=""></div>
                            </div>
                            <div class="blog-content"> <span class="blog-date">May 9, 2018 Stylish</span><a
                                    href="blog-details.html">
                                    <h4>How Black Trans Women Are Redefining Beauty Standards</h4>
                                </a>
                                <p>Sed non mauris vitae erat consequat. Proin gravida nibh vel velit auctor aliquet.
                                    Aenean sollicitudin, lom quis bibenm auctor</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2"><img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/8.jpg') }}" alt="">
                                        <h6>by John wiki on</h6>
                                    </div><a href="blog-details.html"> Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('assets/client/images/blog/blog-page/8.jpg') }}"
                                        alt=""></div>
                            </div>
                            <div class="blog-content"><span class="blog-date">May 9, 2018 Stylish</span><a
                                    href="blog-details.html">
                                    <h4>How Black Trans Women Are Redefining Beauty Standards</h4>
                                </a>
                                <p>Sed non mauris vitae erat consequat. Proin gravida nibh vel velit auctor aliquet.
                                    Aenean sollicitudin, lom quis bibenm auctor</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2"><img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/9.jpg') }}" alt="">
                                        <h6>by John wiki on</h6>
                                    </div><a href="blog-details.html"> Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <div class="blog-main-box">
                            <div>
                                <div class="blog-img"> <img class="img-fluid bg-img"
                                        src="{{ asset('assets/client/images/blog/blog-page/9.jpg') }}"
                                        alt=""></div>
                            </div>
                            <div class="blog-content"> <span class="blog-date">May 9, 2018 Stylish</span><a
                                    href="blog-details.html">
                                    <h4>How Black Trans Women Are Redefining Beauty Standards</h4>
                                </a>
                                <p>Sed non mauris vitae erat consequat. Proin gravida nibh vel velit auctor aliquet.
                                    Aenean sollicitudin, lom quis bibenm auctor</p>
                                <div class="share-box">
                                    <div class="d-flex align-items-center gap-2"><img class="img-fluid"
                                            src="{{ asset('assets/client/images/user/10.jpg') }}" alt="">
                                        <h6>by John wiki on</h6>
                                    </div><a href="blog-details.html"> Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination-wrap mt-0">
                        <ul class="pagination">
                            <li> <a class="prev" href="#"><i class="iconsax"
                                        data-icon="chevron-left"></i></a></li>
                            <li> <a href="#">1</a></li>
                            <li> <a class="active" href="#">2</a></li>
                            <li> <a href="#">3 </a></li>
                            <li> <a class="next" href="#"> <i class="iconsax"
                                        data-icon="chevron-right"></i></a></li>
                        </ul>
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
                                    <h5> Categories</h5>
                                </div>
                                <ul class="categories">
                                    <li>
                                        <p>Fashion<span>30</span></p>
                                    </li>
                                    <li>
                                        <p>Trends<span>20</span></p>
                                    </li>
                                    <li>
                                        <p>Designer<span>3</span></p>
                                    </li>
                                    <li>
                                        <p>Swimwear<span>15</span></p>
                                    </li>
                                    <li>
                                        <p>Handbags<span>11</span></p>
                                    </li>
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
                                    <li> <img class="img-fluid"
                                            src="{{ asset('assets/client/images/other-img/blog-1.jpg') }}"
                                            alt="">
                                        <div> <a href="blog-details.html">
                                                <h6>Study 2020: Fake Engagement is Only Half the Problem</h6>
                                            </a>
                                            <p>September 28, 2021</p>
                                        </div>
                                    </li>
                                    <li> <img class="img-fluid"
                                            src="{{ asset('assets/client/images/other-img/blog-2.jpg') }}"
                                            alt="">
                                        <div> <a href="blog-details.html">
                                                <h6>Top 10 Interior Design in 2020 New York Business</h6>
                                            </a>
                                            <p>September 28, 2021</p>
                                        </div>
                                    </li>
                                    <li> <img class="img-fluid"
                                            src="{{ asset('assets/client/images/other-img/blog-3.jpg') }}"
                                            alt="">
                                        <div> <a href="blog-details.html">
                                                <h6>Ecommerce Brands Tend to Create Strong Communities</h6>
                                            </a>
                                            <p>September 28, 2021</p>
                                        </div>
                                    </li>
                                    <li> <img class="img-fluid"
                                            src="{{ asset('assets/client/images/other-img/blog-4.jpg') }}"
                                            alt="">
                                        <div> <a href="blog-details.html">
                                                <h6>What Do I Need to Make It in the World of Business?</h6>
                                            </a>
                                            <p>September 28, 2021</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
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
                        </div>
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