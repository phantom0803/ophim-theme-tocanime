@extends('themes::themetocanime.layout')

@php
    $years = Cache::remember('all_years', \Backpack\Settings\app\Models\Setting::get('site_cache_ttl', 5 * 60), function () {
        return \Ophim\Core\Models\Movie::select('publish_year')
            ->distinct()
            ->pluck('publish_year')
            ->sortDesc();
    });
@endphp

@push('header')
    <style>.idol-item{margin-right:1em}.idol-item .img{width:96px;height:96px;border-radius:3px;border:1px solid #111;margin-bottom:5px}.idol-item p{margin-bottom:10px}</style>
@endpush

@section('catalog_filter')
    @include('themes::themetocanime.inc.catalog_filter')
@endsection

@section('content')
    <h1 class="title border-bottom">
        {{$section_name}}
    </h1>
    <div>
        <div class="row">
            @if (!count($data))
            <div class="col-sm-12">
                <h4 class="alert alert-info" role="alert">
                    Không có dữ liệu cho mục này...
                </h4>
            </div>
            @endif
            @foreach ($data as $movie)
                @include('themes::themetocanime.inc.section_movie_item')
            @endforeach
        </div>
    </div>
    {{ $data->appends(request()->all())->links('themes::themetocanime.inc.pagination') }}
@endsection
