@php
    $logo = setting('site_logo', '');
    $brand = setting('site_brand', '');
    $title = isset($title) ? $title : setting('site_homepage_title', '');
@endphp

@push('header')
    <style>
        .dropdown-menu {
            background-color: #2c2c2c;
        }
        @media only screen and (min-width: 576px) {
            .dropdown-menu {
                width: 600px;
            }
         }
        .dropdown-item {
            color: #fff;
        }
        ul.navbar-nav {
            font-size: 16px;
        }
    </style>
@endpush

<header class="border-bottom">
    <nav class="navbar navbar-expand-md container navbar-dark">
        <a href="/" class="navbar-brand">
            @if ($logo)
                {!! $logo !!}
            @else
                {!! $brand !!}
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                @foreach ($menu as $item)
                    @if (count($item['children']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{ $item['name'] }} </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <div class="row">
                                    @foreach ($item['children'] as $children)
                                        <li class="col-6 col-lg-4"><a class="dropdown-item" href="{{ $children['link'] }}">{{ $children['name'] }}</a></li>
                                    @endforeach
                                </div>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $item['link'] }}" title="{{ $item['name'] }}">{{ $item['name'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="search-box">
            <form action="/" method="GET" class="top-search">
                <input type="text" id="search-nav" class="form-control" name="search" placeholder="Tìm kiếm phim..."
                    value="{{ request('search') }}">
                <button type="submit" class="top-search__btn-search"><i class="fa fa-fw fa-search"></i></button>
            </form>
        </div>
    </nav>
</header>
