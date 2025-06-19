@extends('layouts.client')

@section('title', 'sản phẩm')

@section('content')
<section class="section-b-space pt-0">
    <div class="heading-banner">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4>Contact</h4>
                </div>
                <div class="col-sm-6">
                    <ul class="breadcrumb float-end">
                        <li class="breadcrumb-item"> <a href="index.html">Home </a></li>
                        <li class="breadcrumb-item active"> <a href="#">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-b-space pt-0">
    <div class="custom-container container">
        <div class="contact-main">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="title-1 address-content">
                        <p class="pb-0">Let's Get In Touch<span></span></p>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="address-items">
                        <div class="icon-box"> <i class="iconsax" data-icon="location"></i></div>
                        <div class="contact-box">
                            <h6>Contact Number</h6>
                            <p>+91 123 - 456 - 7890</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="address-items">
                        <div class="icon-box"> <i class="iconsax" data-icon="phone-calling"></i></div>
                        <div class="contact-box">
                            <h6>Email Address</h6>
                            <p>katie098@gmail.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="address-items">
                        <div class="icon-box"> <i class="iconsax" data-icon="mail"></i></div>
                        <div class="contact-box">
                            <h6>Other Address</h6>
                            <p>ABC Complex, New York USA 123456</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="address-items">
                        <div class="icon-box"> <i class="iconsax" data-icon="map-1"></i></div>
                        <div class="contact-box">
                            <h6>Bournemouth Office</h6>
                            <p>Visitación de la Encina 22</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-b-space pt-0">
    <div class="custom-container container">
        <div class="contact-main">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div class="contact-box">
                        <h4>Contact Us </h4>
                        <p>If you've got fantastic products or want to collaborate, reach out to us. </p>

                        {{-- Thông báo --}}
                        @if(session('success'))
                            <div class="alert alert-success mt-2">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('client.contact.store') }}" class="contact-form">
                            @csrf
                            
                            <div class="row gy-4">
                                <div class="col-12">
                                    <label class="form-label" for="inputName">Full Name</label>
                                    <input class="form-control" id="inputName" type="text" name="name" placeholder="Enter Full Name" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="inputEmail">Email Address</label>
                                    <input class="form-control" id="inputEmail" type="email" name="email" placeholder="Enter Email Address" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="inputPhone">Phone Number</label>
                                    <input class="form-control" id="inputPhone" type="text" name="phone" placeholder="Enter Phone Number">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="inputSubject">Subject</label>
                                    <input class="form-control" id="inputSubject" type="text" name="subject" placeholder="Enter Subject">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-control" name="message" rows="6" placeholder="Enter Your Message" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn_black rounded sm" type="submit"> Send Message </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6 order-lg-2 order-1 offset-xl-1">
                    <div class="contact-img"> 
                        <img class="img-fluid" src="https://themes.pixelstrap.net/katie/assets/images/contact/1.svg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
@endsection
