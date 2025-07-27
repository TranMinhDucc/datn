<footer class="footer-layout-img">
         <section class="section-b-space footer-1">
      <section class="section-b-space footer-1">
          <div class="custom-container container">
             <div class="row">
    {{-- Cột Logo + Hotline + Email --}}
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="footer-content">
            <div class="footer-logo">
                <a href="{{ url('/') }}">
                    <img class="img-fluid" src="{{ asset('assets/client/images/logo/logo-white-4.png') }}"
                         alt="Footer Logo">
                </a>
            </div>
            <ul>
                <li>
                    <i class="iconsax" data-icon="phone-calling"></i>
                    <h6>{{ $settings['hotline'] }}</h6>
                </li>
                <li>
                    <i class="iconsax" data-icon="mail"></i>
                    <h6>{{ $settings['email'] }}</h6>
                </li>
            </ul>
        </div>
    </div>

    {{-- Menu Footer động --}}
    @foreach($footerMenus as $menu)
        <div class="col">
            <div class="footer-content">
                <div class="footer-title d-md-block">
                    <h5>{{ $menu->title }}</h5>
                    @if($menu->children->count() > 0)
                        <ul class="footer-details accordion-hidden">
                            @foreach($menu->children as $child)
                                <li><a class="nav" href="{{ url($child->url) }}">{{ $child->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

          </div>
      </section>

    {{-- Sub Footer --}}
    <div class="sub-footer">
        <div class="custom-container container">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="footer-end">
                        <h6>© 2024 Bản quyền thuộc về bạn. Thiết kế bởi Pixelstrap.</h6>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="payment-card-bottom">
                        <ul>
                            <li><img src="{{ asset('assets/client/images/footer/discover.png') }}" alt=""></li>
                            <li><img src="{{ asset('assets/client/images/footer/american.png') }}" alt=""></li>
                            <li><img src="{{ asset('assets/client/images/footer/master.png') }}" alt=""></li>
                            <li><img src="{{ asset('assets/client/images/footer/giro.png') }}" alt=""></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

