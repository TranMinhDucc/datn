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
                            @php
                                $now = \Carbon\Carbon::now();
                                $start = \Carbon\Carbon::parse($product->starts_at);
                                $end = \Carbon\Carbon::parse($product->ends_at);

                                $isInDiscountTime = $now->between($start, $end);
                                $finalPrice = $isInDiscountTime
                                    ? $product->base_price * (1 - $product->sale_times / 100)
                                    : $product->sale_price;
                            @endphp
                            <script>
                                const isInDiscountTime = @json($isInDiscountTime);
                                const saleTimes = @json($product->sale_times);
                            </script>
                            <h3>{{ $product->name }}</h3>
                            <p id="main-price">{{ number_format($finalPrice) }} đ
                                <del>{{ number_format($product->base_price) }} đ</del>
                                @if ($isInDiscountTime)
                                    <span>-{{ $product->sale_times }}%</span>
                                @endif
                            </p>
                            <p></p>
                            <div class="rating">
                                <ul class="rating">
                                    <li>
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($rating_summary['avg_rating'] >= $i)
                                                <i class="fa-solid fa-star"></i>
                                            @elseif ($rating_summary['avg_rating'] >= $i - 0.5)
                                                <i class="fa-solid fa-star-half-stroke"></i>
                                            @else
                                                <i class="fa-regular fa-star"></i>
                                            @endif
                                        @endfor
                                    </li>
                                    <li>({{ number_format($rating_summary['avg_rating'], 1) }}) Rating</li>
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
                            @foreach ($attributes as $attrId => $attr)
                                <div class="mb-2">
                                    <label><strong>{{ $attr['name'] }}:</strong></label>
                                    <select class="form-select variant-select" data-attr="{{ $attrId }}">
                                        <option value="">-- Chọn {{ strtolower($attr['name']) }} --</option>
                                        @foreach ($attr['values'] as $valueId => $value)
                                            <option value="{{ $valueId }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div> <!-- Đóng thẻ div.mb-2 -->
                            @endforeach
                            @foreach ($attributeGroups as $groupName => $values)
                                <div class="variant-group mb-3" data-attribute="{{ strtolower($groupName) }}">
                                    <h6>{{ ucfirst($groupName) }}</h6>
                                    <ul class="variant-list d-flex gap-2">
                                        @foreach ($values as $val)
                                            <li class="variant-item px-3 py-1 border rounded"
                                                data-value="{{ $val }}" style="cursor: pointer;">
                                                {{ $val }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="variant-error text-danger small mt-1" style="display: none;"></div>
                                    <!-- thêm dòng này -->
                                </div>
                            @endforeach

                            <div id="variant-info" class="mt-3" style="display: none;">
                                {{-- <p><strong>Giá:</strong> <span id="variant-price"></span> đ</p> --}}
                                <p><strong>Số lượng còn lại:</strong> <span id="variant-quantity"></span></p>
                            </div>


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
                                        data-image="{{ asset('storage/' . $product->image) }}"
                                        data-brand="{{ $product->brand->name ?? 'Unknown' }}"
                                        aria-controls="offcanvasRight">
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

                                <div class="countdown"
                                    data-starttime="{{ optional($product->starts_at ? \Carbon\Carbon::parse($product->starts_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}"
                                    data-endtime="{{ optional($product->ends_at ? \Carbon\Carbon::parse($product->ends_at)->timezone('Asia/Ho_Chi_Minh') : null)->toIso8601String() }}">

                                    <div class="d-flex align-items-center gap-2"><img
                                            src="{{ asset('assets/client/images/gif/timer.gif') }}" alt="">
                                        <p>Limited Time Left! Hurry, Sale Ending!</p>
                                    </div>
                                    <ul>
                                        <li>
                                            <div class="timer">
                                                <div class="days"></div>
                                            </div><span class="title">Days</span>
                                        </li>
                                        <li class="dot"><span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="hours"></div>
                                            </div><span class="title">Hours</span>
                                        </li>
                                        <li class="dot"><span>:</span></li>
                                        <li>
                                            <div class="timer">
                                                <div class="minutes"></div>
                                            </div><span class="title">Min</span>
                                        </li>
                                        <li class="dot"><span>:</span></li>
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
                                                                            {{ $review->user->fullname ?? 'Ẩn danh' }}
                                                                        </h6>


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
                    @foreach ($product->related_products as $value)
                        <div class="swiper-slide">
                            <div class="product-box-3">
                                <div class="img-wrapper">
                                    <div class="label-block"><span class="lable-1">NEW</span><a
                                            class="label-2 wishlist-icon" href="javascript:void(0)" tabindex="0"><i
                                                class="iconsax" data-icon="heart" aria-hidden="true"
                                                data-bs-toggle="tooltip" data-bs-title="Add to Wishlist"></i></a></div>
                                    <div class="product-image"><a class="pro-first"
                                            href="{{ route('client.products.show', $value->id) }}"> <img class="bg-img"
                                                src="{{ asset('storage/' . $value->image) }}"
                                                alt="Áo phông cucci LV collab"></a><a class="pro-sec"
                                            href="product.html"> <img class="bg-img"
                                                src="{{ asset('storage/' . $value->image) }}"
                                                alt="Áo phông cucci LV collab"></a></div>
                                    <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#addtocart" tabindex="0"><i class="iconsax"
                                                data-icon="basket-2" aria-hidden="true" data-bs-toggle="tooltip"
                                                data-bs-title="Add to cart"> </i></a><a href="compare.html"
                                            tabindex="0"><i class="iconsax" data-icon="arrow-up-down"
                                                aria-hidden="true" data-bs-toggle="tooltip"
                                                data-bs-title="Compare"></i></a><a href="#" data-bs-toggle="modal"
                                            data-bs-target="#quick-view" tabindex="0"><i class="iconsax"
                                                data-icon="eye" aria-hidden="true" data-bs-toggle="tooltip"
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
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li>{{ $value->rating_avg ?? 0 }}</li>
                                    </ul><a href="{{ route('client.products.show', $value->id) }}">
                                        <h6>{{ $value->name }}</h6>
                                    </a>
                                    <p>${{ number_format($value->sale_price, 2) }}
                                        <del>${{ number_format($value->base_price, 2) }}</del><span>-{{ round((($value->base_price - $value->sale_price) / $value->base_price) * 100) }}%</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('rating-form');
                                const stars = document.querySelectorAll('.star');
                                const ratingInput = document.getElementById('rating-value');
                                const commentInput = document.getElementById('comment');

                                stars.forEach((star, index) => {
                                    star.addEventListener('click', () => {
                                        const rating = star.getAttribute('data-value');
                                        ratingInput.value = rating;

                                        stars.forEach(s => s.querySelector('i').classList.replace('fa-solid',
                                            'fa-regular'));
                                        stars.forEach(s => s.querySelector('i').classList.replace('fa-solid',
                                            'fa-regular'));

                                        for (let i = 0; i < rating; i++) {
                                            stars[i].querySelector('i').classList.replace('fa-regular', 'fa-solid');
                                        }
                                    });
                                });

                                document.querySelectorAll('.submit-rating').forEach(button => {
                                    button.addEventListener('click', function() {
                                        const rate = ratingInput.value;
                                        const comment = commentInput.value;
                                        if (isNaN(rate) || (rate <= 0 || rate > 5)) {
                                            Swal.fire('Thông báo', 'Vui lòng lựa chọn đánh giá của bạn', 'warning');
                                            return;
                                        }
                                        if (comment == '') {
                                            Swal.fire('Thông báo', 'Vui lòng nhập nội dung đánh giá', 'warning');
                                            return;
                                        }
                                        form.submit();
                                    })
                                });
                            })
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
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/client/js/grid-option.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const allVariants = @json($variants);
        const product = @json($product);

        $('.variant-select').on('change', function() {
            let selected = {};
            $('.variant-select').each(function() {
                let attr = $(this).data('attr');
                let val = $(this).val();
                if (val) selected[attr] = val;
            });

            if (Object.keys(selected).length === {{ count($attributes) }}) {
                $.ajax({
                    url: "{{ route('api.get-variant-info') }}",
                    type: "POST",
                    data: {
                        product_id: product.id,
                        attributes: selected,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.status === 'ok') {
                            let price = res.price;
                            if (isInDiscountTime) {
                                price = price * (1 - saleTimes / 100);
                            }
                            $('#variant-info').show();
                            $('#variant-price').text(Math.round(price));
                            $('#variant-quantity').text(res.quantity);
                            $('#main-price').html(
                                new Intl.NumberFormat().format(Math.round(price)) + ' đ' +
                                ' <del>' + new Intl.NumberFormat().format(res.price) + ' đ</del>' +
                                (isInDiscountTime ? ' <span>-' + saleTimes + '%</span>' : '')
                            );
                        } else {
                            $('#variant-info').hide();
                            $('#main-price').html(
                                new Intl.NumberFormat().format({{ round($finalPrice) }}) + ' đ' +
                                ' <del>' + new Intl.NumberFormat().format(
                                    {{ round($product->base_price) }}) + ' đ</del>' +
                                (@json($isInDiscountTime) ?
                                    ' <span>-{{ $product->sale_times }}%</span>' : '')
                            );
                        }
                    }
                });
            } else {
                $('#variant-info').hide();
                $('#main-price').html(
                    new Intl.NumberFormat().format({{ round($finalPrice) }}) + ' đ' +
                    ' <del>' + new Intl.NumberFormat().format({{ round($product->base_price) }}) + ' đ</del>' +
                    (@json($isInDiscountTime) ? ' <span>-{{ $product->sale_times }}%</span>' : '')
                );
            }
        });
    </script>
    <script>
        function getTimeRemaining(endtime) {
            const t = Date.parse(endtime) - Date.now();
            const seconds = Math.floor((t / 1000) % 60);
            const minutes = Math.floor((t / 1000 / 60) % 60);
            const hours = Math.floor((t / (1000 * 60 * 60)) % 24);
            const days = Math.floor(t / (1000 * 60 * 60 * 24));
            return {
                total: t,
                days,
                hours,
                minutes,
                seconds
            };
        }

        function initializeClock($clock, starttimeStr, endtimeStr) {
            const $days = $clock.find('.days');
            const $hours = $clock.find('.hours');
            const $minutes = $clock.find('.minutes');
            const $seconds = $clock.find('.seconds');

            function updateClock() {
                const now = Date.now();
                const start = Date.parse(starttimeStr);
                const end = Date.parse(endtimeStr);

                if (isNaN(start) || isNaN(end)) {
                    $clock.hide();
                    return;
                }
                if (now < start) {
                    // Chưa đến thời gian bắt đầu
                    $clock.hide();
                    return;
                }
                if (now > end) {
                    // Đã hết hạn
                    $clock.hide();
                    return;
                }
                const t = getTimeRemaining(endtimeStr);
                $clock.show();
                $days.text(String(t.days).padStart(2, '0'));
                $hours.text(String(t.hours).padStart(2, '0'));
                $minutes.text(String(t.minutes).padStart(2, '0'));
                $seconds.text(String(t.seconds).padStart(2, '0'));
            }
            updateClock();
            const interval = setInterval(function() {
                const now = Date.now();
                const end = Date.parse(endtimeStr);
                if (now > end) {
                    $clock.hide();
                    clearInterval(interval);
                    return;
                }
                updateClock();
            }, 1000);
        }

        $(document).ready(function() {
            $('.countdown[data-starttime][data-endtime]').each(function() {
                const $clock = $(this);
                const start = $clock.attr('data-starttime');
                const end = $clock.attr('data-endtime');
                if (!start || !end) {
                    $clock.hide();
                    return;
                }
                initializeClock($clock, start, end);
            });
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Xử lý chọn thuộc tính
            document.querySelectorAll('.variant-item').forEach(item => {
                item.addEventListener('click', function() {
                    const group = this.closest('.variant-list');
                    if (!group) return;

                    group.querySelectorAll('.variant-item').forEach(i => {
                        i.classList.remove('active');
                        i.style.setProperty('border', '1px solid #ddd', 'important');
                        i.style.fontWeight = 'normal';
                        i.style.color = '';
                    });

                    this.classList.add('active');
                    this.style.setProperty('border', '2px solid #222', 'important');
                    this.style.fontWeight = 'bold';
                    this.style.color = '#222';
                });
            });

            function showToast(message, type = 'error') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');

                toast.className = 'toast-box';
                toast.style.background =
                    type === 'error' ? '#dc3545' :
                    type === 'warning' ? '#ffc107' :
                    type === 'info' ? '#17a2b8' :
                    '#28a745';

                toast.innerHTML = `
        <div class="icon">
            <span>${type === 'error' ? '❌' : type === 'success' ? '✅' : 'ℹ️'}</span>
            <span>${message}</span>
        </div>
        <button class="close-btn">&times;</button>
    `;

                container.appendChild(toast);

                // ✅ Đóng khi click nút ×
                toast.querySelector('.close-btn').addEventListener('click', () => {
                    toast.remove();
                });

                // ✅ Tự ẩn sau 3s (lần lượt từng toast)
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.5s ease';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                }, 3000 + container.children.length * 500); // lần lượt cách nhau 0.5s
            }



            // ✅ Thêm vào giỏ hàng
            // ✅ Sự kiện Add to Cart
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseFloat(this.dataset.price);
                    const originalPrice = parseFloat(this.dataset.originalPrice);
                    const image = this.dataset.image;
                    const quantity = parseInt(document.querySelector('.quantity input')?.value ||
                        1);
                    const brand = this.dataset.brand || 'Unknown';

                    const currentUser = localStorage.getItem('currentUser') || 'guest';
                    const cartKey = `cartItems_${currentUser}`;
                    const cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

                    const selectedAttributes = {};
                    let valid = true;
                    const missingAttrs = [];

                    document.querySelectorAll('.variant-group').forEach(group => {
                        const attrName = group.dataset.attribute;
                        const selected = group.querySelector('.variant-item.active');

                        if (!selected) {
                            valid = false;
                            missingAttrs.push(attrName);

                        } else {
                            selectedAttributes[attrName] = selected.dataset.value ||
                                selected.textContent.trim();
                        }
                    });

                    if (!valid) {
                        missingAttrs.forEach(attr => {
                            showToast(`Vui lòng chọn ${attr}`, 'error');
                        });

                        // ❌ Không mở giỏ hàng khi có lỗi
                        return;
                    }

                    // ✅ Thêm vào giỏ
                    const index = cartItems.findIndex(item =>
                        item.id === id &&
                        JSON.stringify(item.attributes || {}) === JSON.stringify(
                            selectedAttributes)
                    );

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
                            brand,
                            attributes: selectedAttributes
                        });
                    }

                    localStorage.setItem(cartKey, JSON.stringify(cartItems));

                    // ✅ Cập nhật giỏ hàng UI
                    if (typeof renderCartItems === 'function') {
                        renderCartItems();
                    }

                    // ✅ Mở giỏ hàng
                    const offcanvasEl = document.getElementById('offcanvasRight');
                    if (offcanvasEl) {
                        const bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
                        bsOffcanvas.show();
                    }
                });
            });
        });
    </script>
@endsection
