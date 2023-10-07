@extends('themes::layout')

@php
    $menu = \Ophim\Core\Models\Menu::getTree();
    $tops = Cache::remember('site.movies.tops', setting('site_cache_ttl', 5 * 60), function () {
        $lists = preg_split('/[\n\r]+/', get_theme_option('hotest'));
        $data = [];
        foreach ($lists as $list) {
            if (trim($list)) {
                $list = explode('|', $list);
                [$label, $relation, $field, $val, $sortKey, $alg, $limit, $template] = array_merge($list, ['Phim hot', '', 'type', 'series', 'view_total', 'desc', 4, 'top_thumb']);
                try {
                    $data[] = [
                        'label' => $label,
                        'template' => $template,
                        'data' => \Ophim\Core\Models\Movie::when($relation, function ($query) use ($relation, $field, $val) {
                            $query->whereHas($relation, function ($rel) use ($field, $val) {
                                $rel->where($field, $val);
                            });
                        })
                            ->when(!$relation, function ($query) use ($field, $val) {
                                $query->where($field, $val);
                            })
                            ->orderBy($sortKey, $alg)
                            ->limit($limit)
                            ->get(),
                    ];
                } catch (\Exception $e) {
                    # code
                }
            }
        }

        return $data;
    });
@endphp

@push('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/tocanime/plugins/bootstrap4/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/tocanime/css/style.css') }}" />
    <script type="text/javascript" src="{{ asset('/themes/tocanime/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style>
        #playlists>div:not(:first-child) {
            margin-top: 30px
        }

        #nf-billboard-nav .active {
            font-size: 1.2em;
            color: #fff
        }

        #nf-billboard-nav span {
            cursor: pointer
        }
    </style>
@endpush

@section('body')
    @include('themes::themetocanime.inc.header')
    @if (get_theme_option('ads_header'))
        <div id="top-banner">
            {!! get_theme_option('ads_header') !!}
        </div>
    @endif

    @yield('slider_recommended')
    @yield('catalog_filter')
    @yield('player')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-9" id="playlists">
                    @yield('content')
                </div>
                @include('themes::themetocanime.inc.right_bar')
            </div>

        </div>
    </section>
@endsection

@push('scripts')
@endpush

@section('footer')
    {!! get_theme_option('footer') !!}

    @if (get_theme_option('ads_catfish'))
        <div id="catfish-banner">
            {!! get_theme_option('ads_catfish') !!}
        </div>
    @endif
    <script type="text/javascript" src="{{ asset('/themes/tocanime/js/main.js') }}"></script>
    <script>
        $(window).load(function() {
            WebFont.load({
                google: {
                    families: ['Montserrat&subset=vietnamese']
                },
                custom: {
                    families: ['FontAwesome'],
                    urls: ['https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css']
                }
            });
        });
    </script>
    <script type="text/javascript" src="{{ asset('/themes/tocanime/plugins/lazyload_v2/intersection-observer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/themes/tocanime/plugins/lazyload_v2/lazyload.js') }}"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js"></script>

    {!! setting('site_scripts_google_analytics') !!}
@endsection
