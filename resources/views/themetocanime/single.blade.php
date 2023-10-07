@extends('themes::themetocanime.layout')

@php
    $watch_url = '';
    if (!$currentMovie->is_copyright && count($currentMovie->episodes) && $currentMovie->episodes[0]['link'] != '') {
        $watch_url = $currentMovie->episodes
            ->sortBy([['server', 'asc']])
            ->groupBy('server')
            ->first()
            ->sortByDesc('name', SORT_NATURAL)
            ->groupBy('name')
            ->last()
            ->sortByDesc('type')
            ->first()
            ->getUrl();
    }
@endphp

@push('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/tocanime/plugins/flickity/flickity.min.css') }}" />
@endpush

@section('content')
    <div class="row mb20">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="movie-thumb">
                <img class="mb20 lazy img img-fluid" title="{{ $currentMovie->name }}"
                    alt="{{ $currentMovie->name }} - {{ $currentMovie->origin_name }}"
                    data-original="{{ $currentMovie->getThumbUrl() }}">
                @if ($watch_url !== '')
                    <a class="btn btn-watch btn-primary" href="{{ $watch_url }}">Xem phim</a>
                @endif
            </div>
        </div>
        <div class="col-sm-6 col-md-8">
            <div class="movie-title-box">
                <h1 class="movie-title title">{{ $currentMovie->name }}</h1>
                <span class="movie-info text-gray">
                    <label>{{ $currentMovie->origin_name }} ({{ $currentMovie->publish_year }})</label>
                    <div class="movie-rating">
                        <div id="movies-rating-star"></div>
                        ({{$currentMovie->getRatingStar()}}
                        sao
                        /
                        {{$currentMovie->getRatingCount()}} đánh giá)
                    </div>
                </span>
            </div>
            <div class="movie-des-box scroller">
                <dl class="movie-des">
                    <dt>Trạng thái:</dt>
                    <dd class="text-danger">{{ $currentMovie->episode_current }}</dd>
                    <br>
                    <dt>Số tập :</dt>
                    <dd>{{ $currentMovie->episode_total }}</dd>
                    <br>
                    <dt>Ngôn ngữ :</dt>
                    <dd>{{ $currentMovie->language }} {{ $currentMovie->quality }}</dd>
                    <br>
                    <dt>Độ dài :</dt>
                    <dd>{{ $currentMovie->episode_time }}</dd>
                    <br>
                    <dt>Thể loại :</dt>
                    <dd>
                        <ul class="color-list">
                            {!! $currentMovie->categories->map(function ($category) {
                                    return '<li><a href="' . $category->getUrl() . '">' . $category->name . '</a></li>';
                                })->implode(', ') !!}
                        </ul>
                    </dd>
                    <br>
                    <dt>Quốc gia :</dt>
                    <dd>
                        <ul class="color-list">
                            {!! $currentMovie->regions->map(function ($region) {
                                    return '<li><a href="' . $region->getUrl() . '">' . $region->name . '</a></li>';
                                })->implode(', ') !!}
                        </ul>
                    </dd>
                    <br>
                    <dt>Diễn viên :</dt>
                    <dd>
                        <ul class="color-list">
                            {!! $currentMovie->actors->map(function ($actor) {
                                    return '<li><a href="' . $actor->getUrl() . '">' . $actor->name . '</a></li>';
                                })->implode(', ') !!}
                        </ul>
                    </dd>
                    <br>
                    <dt>Đạo diễn :</dt>
                    <dd>
                        <ul class="color-list">
                            {!! $currentMovie->directors->map(function ($director) {
                                    return '<li><a href="' . $director->getUrl() . '">' . $director->name . '</a></li>';
                                })->implode(', ') !!}
                        </ul>
                    </dd>
                </dl>
            </div>
            @if ($currentMovie->showtimes && $currentMovie->showtimes != '')
                <div class="air-date mb-3">
                    <b>Lịch chiếu: </b>{!! $currentMovie->showtimes !!}
                </div>
            @endif

            @if ($currentMovie->notify && $currentMovie->notify != '')
                <div class="air-date mb-3">
                    <b>Ghi chú: </b>{!! $currentMovie->notify !!}
                </div>
            @endif
        </div>
    </div>
    <div class="box">
        <div class="pl-carousel tani-carousel" id="pl-contain">
            <div class="pl-carousel__item active" data-type="eps">
                Danh sách tập phim
            </div>
            <div class="pl-carousel__item">{{$currentMovie->name}}</div>
        </div>
        <div class="ss-container">
            <div class="ss-item" data-type="eps">
                <div id="me-newest" class="">
                    <p class="box-header">
                        <b>Tập mới nhất</b>
                    </p>
                    <div class="me-list">
                        @if (!$currentMovie->is_copyright && count($currentMovie->episodes) && $currentMovie->episodes[0]['link'] != '')
                            @php
                                $currentMovie->episodes
                                    ->sortBy([['name', 'desc'], ['type', 'desc']])
                                    ->sortByDesc('name', SORT_NATURAL)
                                    ->unique('name')
                                    ->take(3)
                                    ->map(function ($episode) {
                                        echo '<a class="me-item" href="' . $episode->getUrl() . '"> ' . $episode->name . '</a>';
                                    });
                            @endphp
                        @else
                            Phim đang được cập nhật...
                        @endif
                    </div>
                </div>
            </div>
            <div class="ss-item scroller"></div>
        </div>
    </div>
    <div class="box mt30">
        <p class="box-header">
            Nội dung phim
        </p>
        <div class="box-content">
            <p>{!! strip_tags($currentMovie->content) !!}</p>
        </div>
        <b>Tags: </b>
        {!! $currentMovie->tags->map(function ($tag) {
            return '<a href="' . $tag->getUrl() . '">' . $tag->name . '</a>';
        })->implode(', ') !!}
    </div>
    @if (strpos($currentMovie->trailer_url, 'youtube'))
        @php
            try {
                parse_str(parse_url($currentMovie->trailer_url, PHP_URL_QUERY), $parse_url);
                $trailer_id = $parse_url['v'];
            } catch (\Throwable $th) {
                $trailer_id = '';
            }
        @endphp
        <div class="box mt30">
            <p class="box-header">
                Trailer
            </p>
            <div id="trailer">
                <script>
                    $(window).load(function() {
                        $("#trailer").html('' + '<div style="max-width: 640px; margin: 0 auto;">' +
                            '	<div style="position: relative;padding-bottom: 56.25%;height: 0;">' +
                            '    	<iframe src="https://www.youtube.com/embed/{{$trailer_id}}" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;"' +
                            '    		frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' +
                            '    </div>' + '</div>');
                    });
                </script>
            </div>
        </div>
    @endif
    <div class="box mt30">
        <h3 class="title box-header"> Bình luận </h3>
        @include('themes::themetocanime.inc.comment')
    </div>

    @include('themes::themetocanime.inc.related_movie')
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('/themes/tocanime/plugins/flickity/flickity.smart.min.js') }}"></script>
    <script src="/themes/tocanime/plugins/jquery-raty/jquery.raty.js"></script>
    <link href="/themes/tocanime/plugins/jquery-raty/jquery.raty.css" rel="stylesheet" type="text/css" />
    <script>
        var rated = false;
        $('#movies-rating-star').raty({
            score: {{$currentMovie->getRatingStar()}},
            number: 10,
            numberMax: 10,
            hints: ['quá tệ', 'tệ', 'không hay', 'không hay lắm', 'bình thường', 'xem được', 'có vẻ hay', 'hay',
                'rất hay', 'siêu phẩm'
            ],
            starOff: '/themes/tocanime/plugins/jquery-raty/images/star-off.png',
            starOn: '/themes/tocanime/plugins/jquery-raty/images/star-on.png',
            starHalf: '/themes/tocanime/plugins/jquery-raty/images/star-half.png',
            click: function(score, evt) {
                if (rated) return
                fetch("{{ route('movie.rating', ['movie' => $currentMovie->slug]) }}", {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')
                            .getAttribute(
                                'content')
                    },
                    body: JSON.stringify({
                        rating: score
                    })
                });
                rated = true;
                $('#movies-rating-star').data('raty').readOnly(true);
                alert(`Bạn đã đánh giá ${score} sao cho phim này!`);
            }
        });
    </script>
    {!! setting('site_scripts_facebook_sdk') !!}
@endpush
