<div class="container">
    <div class="bn-carousel tani-carousel">
        @foreach ($recommendations as $movie)
            <div class="bn-carousel-cell">
                <a href="{{$movie->getUrl()}}" title="{{$movie->name}}">
                    <div class="bn-carousel-img lazy carousel-img" data-original="{{$movie->getPosterUrl()}}">
                    </div>
                    <div class="bn-carousel-content">
                        <p class="bn-carousel__subtext">{{$movie->origin_name}}</p>
                        <h3>
                            {{$movie->name}} </h3>
                        <p class="bn-carousel__engtitle">
                        </p>
                        <div class="bn-carousel__latest">
                            {{$movie->episode_current}} - {{$movie->language}} {{$movie->quality}}
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
