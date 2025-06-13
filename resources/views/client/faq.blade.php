@extends('layouts.client')

@section('title', 'Câu hỏi thường gặp')

@section('content')
<!--=====================================
                      FAQ PART START
        =======================================-->
<section class="section-b-space pt-0">
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Faq</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
                        <li class="breadcrumb-item active"> <a href="#">Faq</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space pt-0">
    <div class="custom-container container faq-section">
        <div class="row gy-4">
            <div class="col-xl-10 mx-auto">
                <div class="faq-title-2 sticky">
                    <h3>How Can We Help You?</h3>
                    <div>
                        <div class="faq-search">
                            <input type="search" name="text" placeholder="Search here...."><i class="iconsax" data-icon="search-normal-2"></i>
                        </div>
                        <button class="btn btn_black">Search</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-10 mx-auto">
                <div class="custom-accordion">
                    <div class="accordion" id="accordionExample">
                        @if(count($faqs) > 0)
                        @php
                        $collapseIds = [1=>'One',2=>'Two',3=>'Three',4=>'Four',5=>'Five',6=>'Six',7=>'Seven',8=>'Eight',9=>'Nine',10=>'Ten',11=>'Eleven',12=>'Twelve',13=>'Thirteen',14=>'Fourteen',15=>'Fifteen',16=>'Sixteen',17=>'Seventeen',18=>'Eighteen',19=>'Nineteen',20=>'Twenty',21=>'TwentyOne',22=>'TwentyTwo',23=>'TwentyThree',24=>'TwentyFour',25=>'TwentyFive',26=>'TwentySix',27=>'TwentySeven',28=>'TwentyEight',29=>'TwentyNine',30=>'Thirty'];
                        @endphp
                        @foreach($faqs as $faq)
                        @php
                        $stt = $loop->iteration;
                        $collapseId = $collapseIds[$stt] ?? 'One';
                        @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $collapseId }}"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $collapseId }}">
                                    <span>{{ $stt }}. {{ $faq->question }}</span>
                                </button>
                            </h2>
                            <div id="collapse{{ $collapseId }}"
                                class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>{{ $faq->answer }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"><span>No FAQs available at the moment.</span></button>
                            </h2>
                            <div class="accordion-collapse collapse show" id="collapseOne">
                                <div class="accordion-body">
                                    <p>Please check back later or contact support for assistance.</p>
                                </div>
                            </div>
                        </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=====================================
                      FAQ PART END
        =======================================-->


<!--=====================================
                    NEWSLETTER PART START
        =======================================-->
<section class="news-part" style="background: url(images/newsletter.jpg) no-repeat center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5 col-lg-6 col-xl-7">
                <div class="news-text">
                    <h2>Get 20% Discount for Subscriber</h2>
                    <p>Lorem ipsum dolor consectetur adipisicing accusantium</p>
                </div>
            </div>
            <div class="col-md-7 col-lg-6 col-xl-5">
                <form class="news-form">
                    <input type="text" placeholder="Enter Your Email Address">
                    <button><span><i class="icofont-ui-email"></i>Subscribe</span></button>
                </form>
            </div>
        </div>
    </div>
</section>
<!--=====================================
                    NEWSLETTER PART END
        =======================================-->


<!--=====================================
                    INTRO PART START
        =======================================-->
<section class="intro-part">
    <div class="container">
        <div class="row intro-content">
            <div class="col-sm-6 col-lg-3">
                <div class="intro-wrap">
                    <div class="intro-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="intro-content">
                        <h5>free home delivery</h5>
                        <p>Lorem ipsum dolor sit amet adipisicing elit nobis.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="intro-wrap">
                    <div class="intro-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="intro-content">
                        <h5>instant return policy</h5>
                        <p>Lorem ipsum dolor sit amet adipisicing elit nobis.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="intro-wrap">
                    <div class="intro-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="intro-content">
                        <h5>quick support system</h5>
                        <p>Lorem ipsum dolor sit amet adipisicing elit nobis.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="intro-wrap">
                    <div class="intro-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="intro-content">
                        <h5>secure payment way</h5>
                        <p>Lorem ipsum dolor sit amet adipisicing elit nobis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection