@extends('layouts.client')

@section('title', 'Chính sách')

@section('content')
        <section class="inner-section privacy-part">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <nav class="nav nav-pills flex-column" id="scrollspy">
                            <a class="nav-link" href="#item-1">How to contact with Customer Service?</a>
                            <a class="nav-link" href="#item-2">App installation failed, how to update system information?</a>
                            <a class="nav-link" href="#item-3">Website reponse taking time, how to improve?</a>
                            <a class="nav-link" href="#item-4">How do I create a account?</a>
                            <a class="nav-link" href="#item-5">I cannot find an answer to my question!</a>
                        </nav>
                    </div>
                    <div class="col-lg-9">
                        <div data-bs-spy="scroll" data-bs-target="#scrollspy" data-bs-offset="0" tabindex="0">
                            <div class="scrollspy-content" id="item-1">
                                <h3>How to contact with Customer Service?</h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam officia expedita beatae tempore facilis ex maiores, assumenda nostrum minus. Autem nemo corrupti consequuntur incidunt quibusdam dicta. Quasi atque deserunt totam hic voluptatibus veritatis. Ducimus dicta esse praesentium tenetur obcaecati reprehenderit, recusandae ab explicabo maxime deserunt mollitia. Aliquid distinctio tenetur dolore!</p>
                            </div>
                            <div class="scrollspy-content" id="item-2">
                                <h3>App installation failed, how to update system information?</h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam officia expedita beatae tempore facilis ex maiores, assumenda nostrum minus. Autem nemo corrupti consequuntur incidunt quibusdam dicta. Quasi atque deserunt totam hic voluptatibus veritatis. Ducimus dicta esse praesentium tenetur obcaecati reprehenderit, recusandae ab explicabo maxime deserunt mollitia. Aliquid distinctio tenetur dolore!</p>
                            </div>
                            <div class="scrollspy-content" id="item-3">
                                <h3>Website reponse taking time, how to improve?</h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam officia expedita beatae tempore facilis ex maiores, assumenda nostrum minus. Autem nemo corrupti consequuntur incidunt quibusdam dicta. Quasi atque deserunt totam hic voluptatibus veritatis. Ducimus dicta esse praesentium tenetur obcaecati reprehenderit, recusandae ab explicabo maxime deserunt mollitia. Aliquid distinctio tenetur dolore!</p>
                            </div>
                            <div class="scrollspy-content" id="item-4">
                                <h3>How do I create a account?</h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam officia expedita beatae tempore facilis ex maiores, assumenda nostrum minus. Autem nemo corrupti consequuntur incidunt quibusdam dicta. Quasi atque deserunt totam hic voluptatibus veritatis. Ducimus dicta esse praesentium tenetur obcaecati reprehenderit, recusandae ab explicabo maxime deserunt mollitia. Aliquid distinctio tenetur dolore!</p>
                            </div>
                            <div class="scrollspy-content" id="item-5">
                                <h3>I cannot find an answer to my question!</h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam officia expedita beatae tempore facilis ex maiores, assumenda nostrum minus. Autem nemo corrupti consequuntur incidunt quibusdam dicta. Quasi atque deserunt totam hic voluptatibus veritatis. Ducimus dicta esse praesentium tenetur obcaecati reprehenderit, recusandae ab explicabo maxime deserunt mollitia. Aliquid distinctio tenetur dolore!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--=====================================
                    PRIVACY PART END
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
        <!--=====================================
                    INTRO PART END
        =======================================-->
@endsection