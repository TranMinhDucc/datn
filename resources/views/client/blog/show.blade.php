@extends('layouts.client')

@section('title', 'Giỏ hàng')

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
                        <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
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
                            <div class="blog-content"><span class="blog-date">{{$blog->created_at->format('l, d F Y')}}</span><a
                                    href="#">
                                    <h4>{{$blog->title}}</h4>
                                </a>
                                {!! $blog->content !!}
                                <h5 class="pt-3">You Might Be Interested In</h5>
                                <ul>
                                    <li>Mid seat coverage Non wired powermesh back liner low curve to the reverse fixed
                                        straps quick drying comfortable.</li>
                                    <li>Bodycon skirts bright primary colours punchy palette pleated cheerleader vibe
                                        stripe trims.</li>
                                    <li>Statement buttons cover-up tweaks patch pockets perennial lapel collar flap
                                        chest pockets topline stitching cropped jacket.</li>
                                </ul>
                                <div class="comments-box">
                                    <h5>Comments </h5>
                                    <ul class="theme-scrollbar">
                                        <li>
                                            <div class="comment-items">
                                                <div class="user-img"> <img
                                                        src="{{ asset('assets/client/images/user/1.jpg') }}"
                                                        alt=""></div>
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="d-flex justify-content-between gap-3">
                                                            <h6> <i class="iconsax" data-icon="user-1"></i>Michel Poe
                                                            </h6><span> <i class="iconsax" data-icon="clock"></i>Mar 29,
                                                                2022</span>
                                                        </div>
                                                        <ul class="rating p-0 mb">
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-regular fa-star"></i></li>
                                                        </ul>
                                                    </div>
                                                    <p>Khaki cotton blend military jacket flattering fit mock horn
                                                        buttons and patch pockets showerproof black lightgrey. Printed
                                                        lining patch pockets jersey blazer built in pocket square wool
                                                        casual quilted jacket without hood azure.</p><a href="#">
                                                        <span> <i class="iconsax" data-icon="undo"></i>
                                                            Replay</span></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reply">
                                            <div class="comment-items">
                                                <div class="user-img"> <img
                                                        src="{{ asset('assets/client/images/user/2.jpg') }}"
                                                        alt=""></div>
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="d-flex justify-content-between gap-3">
                                                            <h6> <i class="iconsax" data-icon="user-1"></i>Michel Poe
                                                            </h6><span> <i class="iconsax" data-icon="clock"></i>Mar 29,
                                                                2022</span>
                                                        </div>
                                                        <ul class="rating p-0 mb">
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-regular fa-star"></i></li>
                                                        </ul>
                                                    </div>
                                                    <p>Khaki cotton blend military jacket flattering fit mock horn
                                                        buttons and patch pockets showerproof black lightgrey. Printed
                                                        lining patch pockets jersey blazer built in pocket square wool
                                                        casual quilted jacket without hood azure.</p><a href="#">
                                                        <span> <i class="iconsax" data-icon="undo"></i>
                                                            Replay</span></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="comment-items">
                                                <div class="user-img"> <img
                                                        src="{{ asset('assets/client/images/user/3.jpg') }}"
                                                        alt=""></div>
                                                <div class="user-content">
                                                    <div class="user-info">
                                                        <div class="d-flex justify-content-between gap-3">
                                                            <h6> <i class="iconsax" data-icon="user-1"></i>Michel Poe
                                                            </h6><span> <i class="iconsax" data-icon="clock"></i>Mar 29,
                                                                2022</span>
                                                        </div>
                                                        <ul class="rating p-0 mb">
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-regular fa-star"></i></li>
                                                        </ul>
                                                    </div>
                                                    <p>Khaki cotton blend military jacket flattering fit mock horn
                                                        buttons and patch pockets showerproof black lightgrey. Printed
                                                        lining patch pockets jersey blazer built in pocket square wool
                                                        casual quilted jacket without hood azure.</p><a href="#">
                                                        <span> <i class="iconsax" data-icon="undo"></i>
                                                            Replay</span></a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <h5 class="pt-3">Leave a Comment</h5>
                                <div class="row gy-3 message-box">
                                    <div class="col-sm-6">
                                        <label class="form-label">Full Name</label>
                                        <input class="form-control" type="text" placeholder="Enter your Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Email address</label>
                                        <input class="form-control" type="email" placeholder="Enter your Email">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="6" placeholder="Enter Your Message"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn_black rounded sm" type="submit">Post Comment </button>
                                    </div>
                                </div>
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