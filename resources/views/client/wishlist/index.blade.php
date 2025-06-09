@extends('layouts.client')

@section('title', 'sản phẩm')

@section('content')
    <section class="section-b-space pt-0">
        <div class="heading-banner">
            <div class="custom-container container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4>Wishlist</h4>
                    </div>
                    <div class="col-sm-6">
                        <ul class="breadcrumb float-end">
                            <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
                            <li class="breadcrumb-item active"> <a href="#">Wishlist</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-b-space pt-0">
        <div class="custom-container container wishlist-box">
            <div class="product-tab-content ratio1_3">
                <div class="row-cols-xl-4 row-cols-md-3 row-cols-2 grid-section view-option row gy-4 g-xl-4">
                    <div class="col">
                        <div class="product-box-3 product-wishlist">
                            <div class="img-wrapper">
                                <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                                        href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                                            data-icon="trash" aria-hidden="true"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="#"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/1.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="#"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/20.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i class="iconsax"
                                            data-icon="basket-2" aria-hidden="true"> </i></a><a href="compare.html"
                                        title="Compare" tabindex="0"><i class="iconsax" data-icon="arrow-up-down"
                                            aria-hidden="true"></i></a><a href="#" data-bs-toggle="modal"
                                        data-bs-target="#quick-view" title="Quick View" tabindex="0"><i class="iconsax"
                                            data-icon="eye" aria-hidden="true"></i></a></div>
                                <div class="countdown">
                                    <ul class="clockdiv1">
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
                                </ul><a href="#">
                                    <h6>Greciilooks Women's Stylish Top</h6>
                                </a>
                                <p class="list-per">Fashion is to please your eye. Shapes and proportions are for your
                                    intellect. It is important to be chic. Vanity is the healthiest thing in life. Elegance
                                    isn't solely defined by what you wear. It's how you carry yourself, how you speak, what
                                    you read. Fashion is made to become unfashionable.</p>
                                <p>$100.00
                                    <del>$140.00</del><span>-20%</span>
                                </p>
                                <div class="listing-button"> <a class="btn" href="cart.html">Quick Shop </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="product-box-3 product-wishlist">
                            <div class="img-wrapper">
                                <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                                        href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i class="iconsax"
                                            data-icon="trash" aria-hidden="true"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/2.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/19.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i
                                            class="iconsax" data-icon="basket-2" aria-hidden="true"> </i></a><a
                                        href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                                            data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"
                                        tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true"></i></a>
                                </div>
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
                                <p class="list-per">I was the first person to have a punk rock hairstyle. It is not easy to
                                    dress well. I have my permanent muses and my muses of the moment. We live in an era of
                                    globalization and the era of the woman. Never in the history of the world have women
                                    been more in control of their destiny. You have a more interesting life if you wear
                                    impressive clothes.</p>
                                <p>$100.00
                                    <del>$18.00 </del>
                                </p>
                                <div class="listing-button"> <a class="btn" href="cart.html">Quick Shop </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="product-box-3 product-wishlist">
                            <div class="img-wrapper">
                                <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                                        href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i
                                            class="iconsax" data-icon="trash" aria-hidden="true"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/3.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/18.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i
                                            class="iconsax" data-icon="basket-2" aria-hidden="true"> </i></a><a
                                        href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                                            data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"
                                        tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true"></i></a>
                                </div>
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
                                <p class="list-per">I don't like trends. They tend to make everybody look the same. Clothes
                                    can transform your mood and confidence. I like the body. I like to design everything to
                                    do with the body. Fashion is made to become unfashionable. I adore the challenge of
                                    creating truly modern clothes, where a woman's personality and sense of self are
                                    revealed. I want people to see the dress, but focus on the woman.</p>
                                <p>$120.30
                                    <del>$140.00</del><span>-20%</span>
                                </p>
                                <div class="listing-button"> <a class="btn" href="cart.html">Quick Shop </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="product-box-3 product-wishlist">
                            <div class="img-wrapper">
                                <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                                        href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i
                                            class="iconsax" data-icon="trash" aria-hidden="true"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/4.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/17.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i
                                            class="iconsax" data-icon="basket-2" aria-hidden="true"> </i></a><a
                                        href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                                            data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"
                                        tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true"></i></a>
                                </div>
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
                                    <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                    <li>4.3</li>
                                </ul><a href="product.html">
                                    <h6>Blue lined White T-Shirt</h6>
                                </a>
                                <p class="list-per">If you wear clothes that don't suit you, you're a fashion victim. You
                                    have to wear clothes that make you look better. I think I'd go mad if I didn't have a
                                    place to escape to. In order to be irreplaceable one must always be different. Clothes
                                    mean nothing until someone lives in them. It's really easy to get colors right. It's
                                    really hard to get black - and neutrals - right. Black is certainly a color but it's
                                    also an illusion.</p>
                                <p>$190.00
                                    <del>$210.00</del>
                                </p>
                                <div class="listing-button"> <a class="btn" href="cart.html">Quick Shop </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="product-box-3 product-wishlist">
                            <div class="img-wrapper">
                                <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                                        href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i
                                            class="iconsax" data-icon="trash" aria-hidden="true"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/9.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/16.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i
                                            class="iconsax" data-icon="basket-2" aria-hidden="true"> </i></a><a
                                        href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                                            data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"
                                        tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true"></i></a>
                                </div>
                                <div class="countdown">
                                    <ul class="clockdiv3">
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
                                <p class="list-per">I try as much as possible to give you a great basic product and what
                                    comes out, I feel, is really amazing. I believe that my clothes can give people a better
                                    image of themselves - that it can increase their feelings of confidence and happiness.
                                    Every day I'm thinking about change. Luxury will be always around, no matter what
                                    happens in the world. I am like a freight train. Working on the details, twisting them
                                    and playing with them over the years, but always staying on the same track.</p>
                                <p>$100.00
                                    <del>$140.00</del><span>-20%</span>
                                </p>
                                <div class="listing-button"> <a class="btn" href="cart.html">Quick Shop </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="product-box-3 product-wishlist">
                            <div class="img-wrapper">
                                <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                                        href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i
                                            class="iconsax" data-icon="trash" aria-hidden="true"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/10.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/15.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i
                                            class="iconsax" data-icon="basket-2" aria-hidden="true"> </i></a><a
                                        href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                                            data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"
                                        tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true"></i></a>
                                </div>
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
                                <p class="list-per">Fashion fades, only style remains the same. I like the body. I like to
                                    design everything to do with the body. Men don't want another man to look at their woman
                                    because they don't know how to handle it. The key to my collections is sensuality. I
                                    wanted to dress the woman who lives and works, not the woman in a painting.</p>
                                <p>$100.00
                                    <del>$18.00 </del>
                                </p>
                                <div class="listing-button"> <a class="btn" href="cart.html">Quick Shop </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="product-box-3 product-wishlist">
                            <div class="img-wrapper">
                                <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                                        href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i
                                            class="iconsax" data-icon="trash" aria-hidden="true"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/11.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/14.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i
                                            class="iconsax" data-icon="basket-2" aria-hidden="true"> </i></a><a
                                        href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                                            data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"
                                        tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true"></i></a>
                                </div>
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
                                <p class="list-per">Clothes mean nothing until someone lives in them. I was the first
                                    person to have a punk rock hairstyle. You have to stay true to your heritage; that's
                                    what your brand is about. What I hate is nasty, ugly people. I never like to think that
                                    I design for a particular person. I design for the woman I wanted to be, the woman I
                                    used to be, and - to some degree - the woman I'm still a little piece of.</p>
                                <p>$120.30
                                    <del>$140.00</del><span>-20%</span>
                                </p>
                                <div class="listing-button"> <a class="btn" href="cart.html">Quick Shop </a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="product-box-3 product-wishlist">
                            <div class="img-wrapper">
                                <div class="label-block"><a class="label-2 wishlist-icon delete-button"
                                        href="javascript:void(0)" title="Add to Wishlist" tabindex="0"><i
                                            class="iconsax" data-icon="trash" aria-hidden="true"></i></a></div>
                                <div class="product-image"><a class="pro-first" href="product.html"> <img class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/12.jpg') }}"
                                            alt="product"></a><a class="pro-sec" href="product.html"> <img
                                            class="bg-img"
                                            src="{{ asset('assets/client/images/product/product-3/13.jpg') }}"
                                            alt="product"></a></div>
                                <div class="cart-info-icon"> <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addtocart" title="Add to cart" tabindex="0"><i
                                            class="iconsax" data-icon="basket-2" aria-hidden="true"> </i></a><a
                                        href="compare.html" title="Compare" tabindex="0"><i class="iconsax"
                                            data-icon="arrow-up-down" aria-hidden="true"></i></a><a href="#"
                                        data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"
                                        tabindex="0"><i class="iconsax" data-icon="eye" aria-hidden="true"></i></a>
                                </div>
                                <div class="countdown">
                                    <ul class="clockdiv4">
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
                                <p class="list-per">Every day I'm thinking about change. Fashion to me has become very
                                    disposable; I wanted to get back to craft, to clothes that could last. I want people to
                                    be afraid of the women I dress. I didn't want to be a fashion designer, and for a good
                                    half of my career I didn't like it. I always wanted to do other things. When I was
                                    young, I lived like an old woman, and when I got old, I had to live like a young person.
                                </p>
                                <p>$190.00
                                    <del>$210.00</del>
                                </p>
                                <div class="listing-button"> <a class="btn" href="cart.html">Quick Shop </a></div>
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
