<div>
    <div class="title border-bottom">
        <h2>{{$item['label']}}</h2>
        @if ($item['link'] != '' && $item['link'] != "#")
            <span class="title-more">
                <a href="{{$item['link']}}" title="{{$item['label']}}">
                    Xem tất cả <i class="fa fa-fw fa-caret-right"></i>
                </a>
            </span>
        @endif
    </div>
    <div class="playlist-content">
        <div class="row">
            @foreach ($item['data'] as $movie)
                @include('themes::themetocanime.inc.section_movie_item')
            @endforeach
        </div>
    </div>
</div>
