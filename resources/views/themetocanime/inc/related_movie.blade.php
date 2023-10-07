<div class="box" style="margin-bottom: 10px;">
    <h2 class="box-header">Có thể bạn quan tâm</h2>
    <div class="rcm-carousel tani-carousel">
        @foreach ($movie_related as $movie)
            <div class="rcm-carousel-cell" style="width: 20%;padding: 0 5px;min-width: 150px;">
                <div class="card-item">
                    <div class="card-item-img lazy r169 img"
                        data-original="{{$movie->getPosterUrl()}}">
                        <a class="card-item__img-href" title="{{$movie->name}}"
                            href="{{$movie->getUrl()}}">
                            <i class="fa fa-play"></i>
                        </a>
                        <div class="card-item-overlay">
                            <div class="card-item-badget rtl">{{$movie->episode_current}}</div>
                        </div>
                    </div>
                    <div class="card-item-content">
                        <h3>
                            <a title="{{$movie->name}}"
                                href="{{$movie->getUrl()}}">{{$movie->name}}</a>
                        </h3>
                        <p class="elipsis">
                            {{$movie->origin_name}}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        $(function() {
            var rcm = $(".rcm-carousel").smartflickity({
                contain: true,
                prevNextButtons: true,
                groupCells: true,
                autoPlay: 5000,
                wrapAround: true,
            });
        });
    </script>
</div>
