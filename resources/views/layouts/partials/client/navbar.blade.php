<nav id="main-nav">
    <ul class="nav-menu sm-horizontal theme-scrollbar" id="sm-horizontal">
        <li class="mobile-back" id="mobile-back">
            Back<i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i>
        </li>

        {{-- Menu Header động --}}
        @foreach($headerMenus as $menu)
            <li>
                <a class="nav-link" href="{{ url($menu->url) }}">
                    {{ $menu->title }}
                    @if($menu->children->count() > 0)
                        <span><i class="fa-solid fa-angle-down"></i></span>
                    @endif
                </a>

                @if($menu->children->count() > 0)
                    <ul class="nav-submenu">
                        @foreach($menu->children as $child)
                            <li><a href="{{ url($child->url) }}">{{ $child->title }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
