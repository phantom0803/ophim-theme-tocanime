@extends('themes::themetocanime.layout')


@php
    use Ophim\Core\Models\Movie;
    $recommendations = Cache::remember('site.movies.recommendations', setting('site_cache_ttl', 5 * 60), function () {
        return Movie::where('is_recommended', true)
            ->limit(get_theme_option('recommendations_limit', 10))
            ->orderBy('updated_at', 'desc')
            ->get();
    });
    $data = Cache::remember('site.movies.latest', setting('site_cache_ttl', 5 * 60), function () {
        $lists = preg_split('/[\n\r]+/', get_theme_option('latest'));
        $data = [];
        foreach ($lists as $list) {
            if (trim($list)) {
                $list = explode('|', $list);
                [$label, $relation, $field, $val, $sortKey, $alg, $limit, $link] = array_merge($list, ['Phim mới cập nhật', '', 'type', 'series', 'created_at', 'desc', 8, '/']);
                try {
                    $data[] = [
                        'label' => $label,
                        'data' => Movie::when($relation, function ($query) use ($relation, $field, $val) {
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
                        'link' => $link ?: '#',
                    ];
                } catch (\Exception $e) {
                }
            }
        }
        return $data;
    });

@endphp

@push('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/tocanime/plugins/flickity/flickity.min.css') }}" />
@endpush

@section('slider_recommended')
    @if (count($recommendations))
        @include('themes::themetocanime.inc.slider_recommended')
    @endif
@endsection

@section('content')
    @foreach ($data as $item)
        @include('themes::themetocanime.inc.section_movie')
    @endforeach
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('/themes/tocanime/plugins/flickity/flickity.smart.min.js') }}"></script>
    <script>
        $(function () {
            var banner = $(".bn-carousel").smartflickity({
                contain: true,
                prevNextButtons: true,
                groupCells: true,
                autoPlay: 3500,
                wrapAround: true,
            });
        });
    </script>
@endpush
