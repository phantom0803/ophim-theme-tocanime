<div class="sidebar-widget">
    <div class="title border-bottom-dash">
        <h2>{{$top['label']}} </h2>
    </div>
    <div class="sidebar-widget-content scroller" data-sheight="500">
        @foreach ($top['data'] as $movie)
            <div class="card-v-item clearfix">
                <a href="{{$movie->getUrl()}}" title="{{$movie->name}}">
                    <div class="card-item-left">
                        <div class="card-item-img lazy img r43"
                            data-original="{{$movie->getThumbUrl()}}">
                            <div class="card-item-overlay"></div>
                        </div>
                    </div>
                    <div class="card-item-right">
                        <div class="card-item-content">
                            <h3>{{$movie->name}}</h3>
                            <p>{{$movie->origin_name}}</p>
                            <p class="card-item-highlight">{{$movie->episode_current}}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
