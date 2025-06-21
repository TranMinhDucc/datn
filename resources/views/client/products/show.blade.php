@extends('layouts.client')

@section('title', 'sản phẩm')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Product</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product</li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-b-space pt-0 product-thumbnail-page">
        <div class="custom-container container">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <div class="row sticky">
                        <div class="col-sm-2 col-3">
                            <div class="swiper product-slider product-slider-img">
                                <div class="swiper-wrapper">
                                    @foreach ($product->images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                                alt="Ảnh phụ của sản phẩm">
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-10 col-9">
                            <div class="swiper product-slider-thumb product-slider-img-1">
                                <div class="swiper-wrapper ratio_square-2">
                                    @foreach ($product->images as $image)
                                        <div class="swiper-slide">
                                            <img class="bg-img" src="{{ asset('storage/' . $image->image_url) }}"
                                                alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-detail-box">
                        <div class="product-option">
                            <div class="move-fast-box d-flex align-items-center gap-1"><img
                                    src="{{ asset('assets/client/images/gif/fire.gif') }}" alt="">
                                <p>Move fast!</p>
                            </div>
                            <h3>{{ $product->name }}</h3>
                            <p>${{ number_format($product->sale_price ?? $product->base_price) }}
                                @if ($product->sale_price)
                                    <del>${{ number_format($product->base_price) }}</del>
                                    <span
                                        class="offer-btn">{{ round((1 - $product->sale_price / $product->base_price) * 100) }}%
                                        off</span>
                                @endif
                            </p>
                            <div class="rating">
                                <ul class="rating">
                                    @php
                                        $fullStars = floor($product->rating_avg); // sao đầy
                                        $halfStar = $product->rating_avg - $fullStars >= 0.5; // sao nửa
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0); // sao rỗng
                                    @endphp

                                    {{-- sao đầy --}}
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <li><i class="fa-solid fa-star"></i></li>
                                    @endfor

                                    {{-- sao nửa --}}
                                    @if ($halfStar)
                                        <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                    @endif

                                    {{-- sao rỗng --}}
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <li><i class="fa-regular fa-star"></i></li>
                                    @endfor

                                    <li>{{ number_format($product->rating_avg, 1) }}</li>
                                </ul>

                                <p>{{ $product->description }}</p>
                            </div>
                            <div class="buy-box border-buttom">
                                <ul>
                                    <li> <span data-bs-toggle="modal" data-bs-target="#size-chart" title="Quick View"
                                            tabindex="0"><i class="iconsax me-2" data-icon="ruler"></i>Size Chart</span>
                                    </li>
                                    <li> <span data-bs-toggle="modal" data-bs-target="#terms-conditions-modal"
                                            title="Quick View" tabindex="0"><i class="iconsax me-2"
                                                data-icon="truck"></i>Delivery & return</span></li>
                                    <li> <span data-bs-toggle="modal" data-bs-target="#question-box" title="Quick View"
                                            tabindex="0"><i class="iconsax me-2" data-icon="question-message"></i>Ask a
                                            Question</span></li>
                                </ul>
                            </div>
                            {{-- Size --}}
                            {{-- @dd($attributeGroups) --}}
                            @foreach ($attributeGroups as $groupName => $values)
                                <div class="d-flex mb-3">
                                    <div>
                                        <h5>{{ ucfirst($groupName) }}:</h5>

                                        @if (in_array(strtolower($groupName), ['màu sắc', 'color', 'màu']))
                                            {{-- Nếu là màu sắc thì hiển thị đặc biệt --}}
                                            <div class="color-box">
                                                <ul class="color-variant">
                                                    @foreach ($values as $color)
                                                        <li data-color="{{ $color }}">{{ $color }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            {{-- Các thuộc tính khác --}}
                                            <div class="size-box">
                                                <ul class="selected size-list">
                                                    @foreach ($values as $val)
                                                        <li><a href="#">{{ strtolower($val) }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach




                            <div class="quantity-box d-flex align-items-center gap-3">
                                <div class="quantity">
                                    <button class="minus" type="button"><i class="fa-solid fa-minus"></i></button>
                                    <input type="number" value="1" min="1" max="20">
                                    <button class="plus" type="button"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="d-flex align-items-center gap-3 w-100">
                                    <a href="#" class="btn btn_black sm add-to-cart-btn"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-price="{{ $product->sale_price }}"
                                        data-original-price="{{ $product->base_price }}"
                                        data-image="{{ asset('storage/' . $product->image) }}" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                        Add To Cart
                                    </a>



                                    <a class="btn btn_outline sm" href="#">Buy Now</a>
                                </div>
                            </div>
                            <div class="buy-box">
                                <ul>
                                    <li> <a href="wishlist.html"> <i class="fa-regular fa-heart me-2"></i>Add To
                                            Wishlist</a></li>
                                    <li> <a href="compare.html"> <i class="fa-solid fa-arrows-rotate me-2"></i>Add To
                                            Compare</a></li>
                                    <li> <a href="#" data-bs-toggle="modal" data-bs-target="#social-box"
                                            title="Quick View" tabindex="0"><i
                                                class="fa-solid fa-share-nodes me-2"></i>Share</a></li>
                                </ul>
                            </div>
                            <div class="sale-box">
                                <div class="d-flex align-items-center gap-2"><img
                                        src="{{ asset('assets/client/images/gif/timer.gif') }}" alt="">
                                    <p>Limited Time Left! Hurry, Sale Ending!</p>
                                </div>
                                <div class="countdown">
                                    <ul class="clockdiv1">
                                        <li>
                                            <div class="timer">
                                                <div class="days"></div>
                                            </div><span class="title">Days</span>
                                        </li>
                                        <li>:</li>
                                        <li>
                                            <div class="timer">
                                                <div class="hours"></div>
                                            </div><span class="title">Hours</span>
                                        </li>
                                        <li>:</li>
                                        <li>
                                            <div class="timer">
                                                <div class="minutes"></div>
                                            </div><span class="title">Min</span>
                                        </li>
                                        <li>:</li>
                                        <li>
                                            <div class="timer">
                                                <div class="seconds"></div>
                                            </div><span class="title">Sec</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="dz-info">
                                <ul>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Sku:</h6>
                                            <p>{{ $product->sku }}</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Available:</h6>
                                            <p>{{ $product->stock_quantity > 0 ? 'In Stock' : 'Pre-Order' }}</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Tags:</h6>
                                            <p>
                                                @if ($product->tags)
                                                    {{ $product->tags->pluck('name')->implode(', ') }}
                                                @endif
                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6>Vendor:</h6>
                                            <p>{{ $product->vendor_name }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="share-option">
                                <h5>Secure Checkout</h5><img class="img-fluid"
                                    src="{{ asset('assets/client/images/other-img/secure_payments.png') }}"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="product-section-box x-small-section pt-0">
            <div class="custom-container container">
                <div class="row">
                    <div class="col-12">
                        <ul class="product-tab theme-scrollbar nav nav-tabs nav-underline" id="Product" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="Description-tab" data-bs-toggle="tab"
                                    data-bs-target="#Description-tab-pane" role="tab"
                                    aria-controls="Description-tab-pane" aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="specification-tab" data-bs-toggle="tab"
                                    data-bs-target="#specification-tab-pane" role="tab"
                                    aria-controls="specification-tab-pane" aria-selected="false">Specification</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="question-tab" data-bs-toggle="tab"
                                    data-bs-target="#question-tab-pane" role="tab" aria-controls="question-tab-pane"
                                    aria-selected="false">Q & A</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="Reviews-tab" data-bs-toggle="tab"
                                    data-bs-target="#Reviews-tab-pane" role="tab" aria-controls="Reviews-tab-pane"
                                    aria-selected="false">Reviews</button>
                            </li>
                        </ul>
                        <div class="tab-content product-content" id="ProductContent">
                            <div class="tab-pane fade show active" id="Description-tab-pane" role="tabpanel"
                                aria-labelledby="Description-tab" tabindex="0">
                                <div class="row gy-4">
                                    <div class="col-12">
                                        <p class="paragraphs">Experience the perfect blend of comfort and style with our
                                            Summer Breeze Cotton Dress. Crafted from 100% premium cotton, this dress offers
                                            a soft and breathable feel, making it ideal for warm weather. The lightweight
                                            fabric ensures you stay cool and comfortable throughout the day.</p>
                                        <p class="paragraphs">Perfect for casual outings, beach trips, or summer parties.
                                            Pair it with sandals for a relaxed look or dress it up with heels and
                                            accessories for a more polished ensemble.</p>
                                    </div>
                                    <div class="col-12">
                                        <div class="row gy-4">
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="general-summery">
                                                    <h5>Product Specifications</h5>
                                                    <ul>
                                                        <li>100% Premium Cotton</li>
                                                        <li>A-line silhouette with a flattering fit</li>
                                                        <li>Knee-length for versatile styling</li>
                                                        <li>V-neck for a touch of elegance</li>
                                                        <li>Short sleeves for a casual look</li>
                                                        <li>Available in solid colors and floral prints</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="general-summery">
                                                    <h5>Washing Instructions</h5>
                                                    <ul>
                                                        <li>Use cold water for washing</li>
                                                        <li>Use a low heat setting for drying.</li>
                                                        <li>Avoid using bleach on this fabric.</li>
                                                        <li>Use a low heat setting when ironing.</li>
                                                        <li>Do not take this item to a dry cleaner.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="general-summery">
                                                    <h5>Size & Fit</h5>
                                                    <ul>
                                                        <li>The model (height 5'8) is wearing a size S</li>
                                                        <li>Measurements taken from size Small</li>
                                                        <li>Chest: 30"</li>
                                                        <li>Length: 20"</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="specification-tab-pane" role="tabpanel"
                                aria-labelledby="specification-tab" tabindex="0">
                                <p>I like to be real. I don't like things to be staged or fussy. Grunge is a hippied
                                    romantic version of punk. I have my favourite fashion decade, yes, yes, yes: '60s. It
                                    was a sort of little revolution; the clothes were amazing but not too exaggerated.
                                    Fashions fade, style is eternal. A girl should be two things: classy and fabulous.</p>
                                <div class="table-responsive theme-scrollbar">
                                    <table class="specification-table table striped">
                                        <tr>
                                            <th>Product Dimensions</th>
                                            <td>15 x 15 x 3 cm; 250 Grams</td>
                                        </tr>
                                        <tr>
                                            <th>Date First Available</th>
                                            <td>5 April 2021</td>
                                        </tr>
                                        <tr>
                                            <th>Manufacturer&rlm;</th>
                                            <td>Aditya Birla Fashion and Retail Limited</td>
                                        </tr>
                                        <tr>
                                            <th>ASIN</th>
                                            <td>B06Y28LCDN</td>
                                        </tr>
                                        <tr>
                                            <th>Item model number</th>
                                            <td>AMKP317G04244</td>
                                        </tr>
                                        <tr>
                                            <th>Department</th>
                                            <td>Men</td>
                                        </tr>
                                        <tr>
                                            <th>Item Weight</th>
                                            <td>250 G</td>
                                        </tr>
                                        <tr>
                                            <th>Item Dimensions LxWxH</th>
                                            <td>15 x 15 x 3 Centimeters</td>
                                        </tr>
                                        <tr>
                                            <th>Net Quantity</th>
                                            <td>1 U</td>
                                        </tr>
                                        <tr>
                                            <th>Included Components&rlm;</th>
                                            <td>1-T-shirt</td>
                                        </tr>
                                        <tr>
                                            <th>Generic Name</th>
                                            <td>T-shirt</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="question-tab-pane" role="tabpanel"
                                aria-labelledby="question-tab" tabindex="0">
                                <div class="question-main-box">
                                    <h5>Have Doubts Regarding This Product ?</h5>
                                    <h6 data-bs-toggle="modal" data-bs-target="#question-modal" title="Quick View"
                                        tabindex="0">Post Your Question</h6>
                                </div>
                                <div class="question-answer">
                                    <ul>
                                        <li>
                                            <div class="question-box">
                                                <p>Q1 </p>
                                                <h6>Which designer created the little black dress?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0</a>
                                                    </li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>The little black dress (LBD) is often
                                                    attributed to the iconic fashion designer Coco Chanel. She popularized
                                                    the concept of the LBD in the 1920s, offering a simple, versatile, and
                                                    elegant garment that became a staple in women's fashion.</span></div>
                                        </li>
                                        <li>
                                            <div class="question-box">
                                                <p>Q2 </p>
                                                <h6>Which First Lady influenced women's fashion in the 1960s?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0</a>
                                                    </li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>The First Lady who significantly
                                                    influenced women's fashion in the 1960s was Jacqueline Kennedy, the wife
                                                    of President John F. Kennedy. She was renowned for her elegant and
                                                    sophisticated style, often wearing simple yet chic outfits that set
                                                    trends during her time in the White House. </span></div>
                                        </li>
                                        <li>
                                            <div class="question-box">
                                                <p>Q3 </p>
                                                <h6>What was the first name of the fashion designer Chanel?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0
                                                        </a></li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>The first name of the fashion designer
                                                    Chanel was Gabrielle. Gabrielle "Coco" Chanel was a pioneering French
                                                    fashion designer known for her timeless designs, including the iconic
                                                    Chanel suit and the little black dress.</span></div>
                                        </li>
                                        <li>
                                            <div class="question-box">
                                                <p>Q4 </p>
                                                <h6>Carnaby Street, famous in the 60s as a fashion center, is in which
                                                    capital?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0</a>
                                                    </li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>Carnaby Street, famous for its
                                                    association with fashion and youth culture in the 1960s, is located in
                                                    London, the capital of the United Kingdom.🎉</span></div>
                                        </li>
                                        <li>
                                            <div class="question-box">
                                                <p>Q5 </p>
                                                <h6>Threadless is a company selling unique what?</h6>
                                                <ul class="link-dislike-box">
                                                    <li> <a href="#"><i class="iconsax" data-icon="like"> </i>0</a>
                                                    </li>
                                                    <li> <a href="#"><i class="iconsax" data-icon="dislike">
                                                            </i>0</a></li>
                                                </ul>
                                            </div>
                                            <div class="answer-box"><b>Ans.</b><span>Threadless is a company selling unique
                                                    T-shirts.</span></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Reviews-tab-pane" role="tabpanel"
                                aria-labelledby="Reviews-tab" tabindex="0">
                                <div class="row gy-4">
                                    <div class="col-lg-4">
                                        <div class="review-right">
                                            <div class="customer-rating">
                                                <div class="global-rating">
                                                    <div>
                                                        <h5>{{ number_format($rating_summary['avg_rating'], 2) }}</h5>
                                                    </div>
                                                    <div>
                                                        <h6>Average Ratings</h6>
                                                        <ul class="rating p-0 mb">
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-solid fa-star"></i></li>
                                                            <li><i class="fa-regular fa-star"></i></li>
                                                            <li><span>({{ $rating_summary['total_rating'] }})</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <ul class="rating-progess">
                                                    <li>
                                                        <p>5 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['5_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['5_star_percent'] }}%</p>
                                                    </li>
                                                    <li>
                                                        <p>4 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['4_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['4_star_percent'] }}%</p>
                                                    </li>
                                                    <li>
                                                        <p>3 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['3_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['3_star_percent'] }}%</p>
                                                    </li>
                                                    <li>
                                                        <p>2 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['2_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['2_star_percent'] }}%</p>
                                                    </li>
                                                    <li>
                                                        <p>1 Star</p>
                                                        <div class="progress" role="progressbar"
                                                            aria-label="Animated striped example" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                style="width: {{ $rating_summary['1_star_percent'] }}%">
                                                            </div>
                                                        </div>
                                                        <p>{{ $rating_summary['1_star_percent'] }}%</p>
                                                    </li>
                                                </ul>
                                                <button class="btn reviews-modal" data-bs-toggle="modal"
                                                    data-bs-target="#Reviews-modal" title="Quick View"
                                                    tabindex="0">Write a
                                                    review</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="comments-box">
                                            <h5>Comments </h5>
                                            <ul class="theme-scrollbar">
                                                @foreach ($reviews as $review)
                                                    <li style="width:100%">
                                                        <div class="comment-items">
                                                            <div class="user-img"> <img
                                                                    src="{{ $review->user_avatar ?? asset('assets/client/images/user/3.jpg') }}"
                                                                    alt="">
                                                            </div>
                                                            <div class="user-content">
                                                                <div class="user-info">
                                                                    <div class="d-flex justify-content-between gap-3">
                                                                        <h6><i class="iconsax" data-icon="user-1"></i>
                                                                            {{ $review->user->fullname ?? 'Ẩn danh' }}</h6>


                                                                        <span> <i class="iconsax"
                                                                                data-icon="clock"></i>{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                                                    </div>
                                                                    <!-- Hiển thị số sao đánh giá -->
                                                                    <ul class="rating p-0 mb">
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <li>
                                                                                <i
                                                                                    class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                                                            </li>
                                                                        @endfor
                                                                    </ul>
                                                                </div>
                                                                @if ($review->approved)
                                                                    <p>{{ $review->comment }}</p>
                                                                @endif

                                                                {{-- <a href="#"> <span> <i class="iconsax" data-icon="undo"></i>
                                                                        Replay</span></a> --}}
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-b-space pt-0">
        <div class="custom-container container product-contain">
            <div class="title text-start">
                <h3>Related Products</h3>
                <svg>
                    <use href="{{ asset('assets/svg/icon-sprite.svg#main-line') }}"></use>

                </svg>
            </div>
            <div class="swiper special-offer-slide-2">
                <div class="swiper-wrapper ratio1_3">
                    <div class="swiper-slide">
                        <div class="product-box-3">
                            <div class="img-wrapper">
                                <div class="label-block"><span class="lable-1">NEW</span><a class="label-2 wishlist-icon"
                                        href="javascript:void(0)" tabindex="0"><i class="iconsax" data-icon="heart"
                                            aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to Wishlist"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/11.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/9.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" tabindex="0"><i class="iconsax"
                                            data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to cart"> </i></a><a href="compare.html" tabindex="0"><i
                                            class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                            data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i
                                            class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Quick View"></i></a></div>
                                <div class="countdown">
                                    <ul class="clockdiv2">
                                        <li>
                                            <div class="timer">
                                                <div class="days"></div>
                                            </div><span class="title">Days</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="hours"></div>
                                            </div><span class="title">Hours</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="minutes"></div>
                                            </div><span class="title">Min</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="seconds"></div>
                                            </div><span class="title">Sec</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-detail">
                                <ul class="rating">
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                    <li><i class="fa-regular fa-star"></i></li>
                                    <li>4.3</li>
                                </ul><a href="product.html">
                                    <h6>Greciilooks Women's Stylish Top</h6>
                                </a>
                                <p>$100.00
                                    <del>$140.00</del><span>-20%</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="product-box-3">
                            <div class="img-wrapper">
                                <div class="label-block"><span class="lable-1">NEW</span><a class="label-2 wishlist-icon"
                                        href="javascript:void(0)" tabindex="0"><i class="iconsax" data-icon="heart"
                                            aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to Wishlist"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/18.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/22.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" tabindex="0"><i class="iconsax"
                                            data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to cart"> </i></a><a href="compare.html" tabindex="0"><i
                                            class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                            data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i
                                            class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Quick View"></i></a></div>
                            </div>
                            <div class="product-detail">
                                <ul class="rating">
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-regular fa-star"></i></li>
                                    <li>4.3</li>
                                </ul><a href="product.html">
                                    <h6>Wide Linen-Blend Trousers</h6>
                                </a>
                                <p>$100.00
                                    <del>$18.00 </del>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="product-box-3">
                            <div class="img-wrapper">
                                <div class="label-block"><span class="lable-1">NEW</span><a class="label-2 wishlist-icon"
                                        href="javascript:void(0)" tabindex="0"><i class="iconsax" data-icon="heart"
                                            aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to Wishlist"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/12.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/10.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" tabindex="0"><i class="iconsax"
                                            data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to cart"> </i></a><a href="compare.html" tabindex="0"><i
                                            class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                            data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i
                                            class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Quick View"></i></a></div>
                            </div>
                            <div class="product-detail">
                                <ul class="rating">
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li>4.3</li>
                                </ul><a href="product.html">
                                    <h6>Long Sleeve Rounded T-Shirt</h6>
                                </a>
                                <p>$120.30
                                    <del>$140.00</del><span>-20%</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="product-box-3">
                            <div class="img-wrapper">
                                <div class="label-block"><span class="lable-1">NEW</span><a class="label-2 wishlist-icon"
                                        href="javascript:void(0)" tabindex="0"><i class="iconsax" data-icon="heart"
                                            aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to Wishlist"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/16.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/20.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" tabindex="0"><i class="iconsax"
                                            data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to cart"> </i></a><a href="compare.html" tabindex="0"><i
                                            class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                            data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i
                                            class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Quick View"></i></a></div>
                                <div class="countdown">
                                    <ul class="clockdiv11">
                                        <li>
                                            <div class="timer">
                                                <div class="days"></div>
                                            </div><span class="title">Days</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="hours"></div>
                                            </div><span class="title">Hours</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="minutes"></div>
                                            </div><span class="title">Min</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="seconds"></div>
                                            </div><span class="title">Sec</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-detail">
                                <ul class="rating">
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                    <li>4.3</li>
                                </ul><a href="product.html">
                                    <h6>Blue lined White T-Shirt</h6>
                                </a>
                                <p>$190.00
                                    <del>$210.00</del>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="product-box-3">
                            <div class="img-wrapper">
                                <div class="label-block"><span class="lable-1">NEW</span><a class="label-2 wishlist-icon"
                                        href="javascript:void(0)" tabindex="0"><i class="iconsax" data-icon="heart"
                                            aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to Wishlist"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/22.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/12.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" tabindex="0"><i class="iconsax"
                                            data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Add to cart"> </i></a><a href="compare.html" tabindex="0"><i
                                            class="iconsax" data-icon="arrow-up-down" aria-hidden="true"
                                            data-bs-toggle="tooltip" data-bs-title="Compare"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" tabindex="0"><i
                                            class="iconsax" data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
                                            data-bs-title="Quick View"></i></a></div>
                                <div class="countdown">
                                    <ul class="clockdiv10">
                                        <li>
                                            <div class="timer">
                                                <div class="days"></div>
                                            </div><span class="title">Days</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="hours"></div>
                                            </div><span class="title">Hours</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="minutes"></div>
                                            </div><span class="title">Min</span>
                                        </li>
                                        <li class="dot"> <span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="seconds"></div>
                                            </div><span class="title">Sec</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-detail">
                                <ul class="rating">
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star"></i></li>
                                    <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                    <li><i class="fa-regular fa-star"></i></li>
                                    <li>4.3</li>
                                </ul><a href="product.html">
                                    <h6>Greciilooks Women's Stylish Top</h6>
                                </a>
                                <p>$100.00
                                    <del>$140.00</del><span>-20%</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="customer-reviews-modal modal theme-modal fade" id="Reviews-modal" tabindex="-1" role="dialog"
        aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Write A Review</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    @auth
                        <form action="{{ route('client.review') }}" method="POST" class="row g-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $test_id }}">
                            <input type="hidden" name="rating" id="rating-value" value="0">

                            <div class="col-12">
                                <div class="reviews-product d-flex gap-3">
                                    <img src="{{ asset('assets/images/modal/1.jpg') }}" alt="" width="80">
                                    <div>
                                        <h5>Denim Skirts Corset Blazer</h5>
                                        <p>$20.00 <del>$35.00</del></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="customer-rating">
                                    <label class="form-label">Rating</label>
                                    <ul class="rating p-0 mb-0 d-flex" style="list-style: none; cursor: pointer;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li class="star" data-value="{{ $i }}">
                                                <i class="fa-regular fa-star fs-4 me-1"></i>
                                            </li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Review Content :</label>
                                    <textarea name="comment" class="form-control" id="comment" cols="30" rows="4"
                                        placeholder="Write your comments here..." required></textarea>
                                </div>
                            </div>

                            <div class="modal-button-group d-flex gap-2">
                                <button class="btn btn-cancel" type="button" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-submit" type="submit">Submit</button>
                            </div>
                        </form>

                        <script>
                            const stars = document.querySelectorAll('.star');
                            const ratingInput = document.getElementById('rating-value');

                            stars.forEach((star, index) => {
                                star.addEventListener('click', () => {
                                    const rating = star.getAttribute('data-value');
                                    ratingInput.value = rating;

                                    stars.forEach(s => s.querySelector('i').classList.replace('fa-solid', 'fa-regular'));

                                    for (let i = 0; i < rating; i++) {
                                        stars[i].querySelector('i').classList.replace('fa-regular', 'fa-solid');
                                    }
                                });
                            });
                        </script>
                    @endauth

                    @guest
                        <div class="alert alert-warning mt-3 d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <strong>Bạn cần đăng nhập</strong> để đánh giá và bình luận sản phẩm.
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </div>

                    @endguest


                </div>
            </div>
        </div>
    </div>Your browser
@endsection
@section('js')
    <script src="{{ asset('assets/client/js/grid-option.js') }}"></script>

    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartList = document.querySelector('.offcanvas-cart');

            // Load lại giỏ hàng từ localStorage
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            cartItems.forEach(item => {
                renderCartItem(item);
            });

            // Gắn sự kiện cho nút Add to Cart
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const item = {
                        id: this.dataset.id,
                        name: this.dataset.name,
                        price: parseFloat(this.dataset.price),
                        originalPrice: parseFloat(this.dataset.originalPrice),
                        image: this.dataset.image,
                        quantity: 1
                    };

                    // Nếu sản phẩm đã tồn tại, tăng quantity
                    const index = cartItems.findIndex(p => p.id === item.id);
                    if (index !== -1) {
                        cartItems[index].quantity += 1;
                    } else {
                        cartItems.push(item);
                    }

                    localStorage.setItem('cartItems', JSON.stringify(cartItems));
                    renderCartItems();
                });
            });

            // === Hiển thị lại toàn bộ giỏ hàng ===
            function renderCartItems() {
                cartList.innerHTML = '';
                cartItems.forEach(item => renderCartItem(item));
                updateTotal();
            }

            // === Render từng item ===
            function renderCartItem(item) {
                const li = document.createElement('li');

                li.innerHTML = `
        <a href="#"><img src="${item.image}" alt=""></a>
        <div>
            <h6 class="mb-0">${item.name}</h6>
            <p>$${item.price.toLocaleString()}
                <del>$${item.originalPrice.toLocaleString()}</del>
                <span class="btn-cart">$<span class="btn-cart__total">${item.price.toLocaleString()}</span></span>
            </p>
            <div class="btn-containter">
                <div class="btn-control">
                    <button class="btn-control__remove">&minus;</button>
                    <div class="btn-control__quantity">
                        <div id="quantity-previous">${item.quantity - 1}</div>
                        <div id="quantity-current">${item.quantity}</div>
                        <div id="quantity-next">${item.quantity + 1}</div>
                    </div>
                    <button class="btn-control__add">+</button>
                </div>
            </div>
        </div>
        <i class="fa fa-trash delete-icon" style="font-size: 18px; color: #888; cursor: pointer;"></i>
    `;

                // === Tăng / giảm số lượng ===
                li.querySelector('.btn-control__add').addEventListener('click', () => {
                    item.quantity += 1;
                    saveAndRender();
                });

                li.querySelector('.btn-control__remove').addEventListener('click', () => {
                    if (item.quantity > 1) {
                        item.quantity -= 1;
                        saveAndRender();
                    }
                });

                // === Xoá sản phẩm ===
                li.querySelector('.delete-icon').addEventListener('click', () => {
                    cartItems = cartItems.filter(p => p.id !== item.id);
                    saveAndRender();
                });

                cartList.appendChild(li);
            }


            // Cập nhật tổng tiền
            function updateTotal() {
                let total = 0;
                cartItems.forEach(item => {
                    total += item.price * item.quantity;
                });
                document.querySelector('.price-box p').textContent = `$ ${total.toFixed(2)} USD`;
            }

            // Lưu và hiển thị lại giỏ
            function saveAndRender() {
                localStorage.setItem('cartItems', JSON.stringify(cartItems));
                renderCartItems();
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý chọn Size
            const sizeItems = document.querySelectorAll('.size-box ul li');
            sizeItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    sizeItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    this.parentNode.classList.add('selected');
                });
            });

            // Xử lý chọn Màu
            const colorItems = document.querySelectorAll('.color-variant li');
            colorItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    colorItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script> -->

    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartList = document.querySelector('.offcanvas-cart');
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

            renderCartItems();

            // Sự kiện Add to Cart
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseFloat(this.dataset.price);
                    const originalPrice = parseFloat(this.dataset.originalPrice);
                    const image = this.dataset.image;

                    // ✅ Lấy size đang được chọn
                    const selectedSize = document.querySelector('.size-box ul li.active');
                    const size = selectedSize ? selectedSize.textContent.trim() : 'Default';

                    // ✅ Lấy màu đang được chọn
                    const selectedColor = document.querySelector('.color-variant li.active');
                    const color = selectedColor ? selectedColor.dataset.color || selectedColor
                        .title || 'Default' : 'Default';

                    // ✅ Lấy số lượng
                    const quantityInput = document.querySelector('.quantity input');
                    const quantity = parseInt(quantityInput?.value || 1);

                    // ✅ Kiểm tra xem đã có sản phẩm với cùng ID, size, color chưa
                    const index = cartItems.findIndex(p => p.id === id && p.size === size && p
                        .color === color);
                    if (index !== -1) {
                        cartItems[index].quantity += quantity;
                    } else {
                        cartItems.push({
                            id,
                            name,
                            price,
                            originalPrice,
                            image,
                            quantity,
                            size,
                            color
                        });
                    }

                    saveAndRender();
                });

            });

            function renderCartItems() {
                cartList.innerHTML = '';
                cartItems.forEach(item => renderCartItem(item));
                updateTotal();
            }

            function renderCartItem(item) {
                const li = document.createElement('li');
                li.innerHTML = `
            <a href="#"><img src="${item.image}" alt=""></a>
            <div>
                <h6 class="mb-0">${item.name}</h6>
                <p>$${item.price.toLocaleString()}
                    <del>$${item.originalPrice.toLocaleString()}</del>
                    <span class="btn-cart">$<span class="btn-cart__total">${(item.price * item.quantity).toLocaleString()}</span></span>
                </p>
                <p>Size: <span>${item.size || 'Default'}</span></p>
<p>Color: <span>${item.color || 'Default'}</span></p>

                <div class="btn-containter">
                    <div class="btn-control">
                        <button class="btn-control__remove">&minus;</button>
                        <div class="btn-control__quantity">
                            <div id="quantity-previous">${item.quantity - 1}</div>
                            <div id="quantity-current">${item.quantity}</div>
                            <div id="quantity-next">${item.quantity + 1}</div>
                        </div>
                        <button class="btn-control__add">+</button>
                    </div>
                </div>
            </div>
            <i class="fa fa-trash delete-icon" style="font-size: 18px; color: #888; cursor: pointer;"></i>
        `;

                li.querySelector('.btn-control__add').addEventListener('click', () => {
                    item.quantity += 1;
                    saveAndRender();
                });

                li.querySelector('.btn-control__remove').addEventListener('click', () => {
                    if (item.quantity > 1) {
                        item.quantity -= 1;
                        saveAndRender();
                    }
                });

                li.querySelector('.delete-icon').addEventListener('click', () => {
                    cartItems = cartItems.filter(p => p.id !== item.id);
                    saveAndRender();
                });

                cartList.appendChild(li);
            }

            function updateTotal() {
                let total = 0;
                cartItems.forEach(item => {
                    total += item.price * item.quantity;
                });
                const totalElement = document.querySelector('.price-box p');
                if (totalElement) {
                    totalElement.textContent = `$ ${total.toFixed(2)} USD`;
                }
            }

            function saveAndRender() {
                localStorage.setItem('cartItems', JSON.stringify(cartItems));
                renderCartItems();
            }

            // Xử lý chọn size
            const sizeItems = document.querySelectorAll('.size-box ul li');
            sizeItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    sizeItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    this.parentNode.classList.add('selected');
                });
            });

            // Xử lý chọn màu
            const colorItems = document.querySelectorAll('.color-variant li');
            colorItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    colorItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script> -->



@endsection
