<div class="col-lg-3 col-md-4 col-6">
    <div class="card-item">
        <div class="card-item-img lazy r43 img"
            data-original="{{$movie->getThumbUrl()}}">
            <a class="card-item__img-href" title="{{$movie->name}}"
                href="{{$movie->getUrl()}}">
                <i class="fa fa-play"></i>
            </a>
            <div class="card-item-overlay">
                <div class="card-item-badget rtl">
                    {{$movie->episode_current}}
                </div>
            </div>
        </div>
        <div class="card-item-content">
            <h3>
                <a title="{{$movie->name}}" href="{{$movie->getUrl()}}"> {{$movie->name}} </a>
            </h3>
            <p class="elipsis">
                {{$movie->origin_name}}
            </p>
        </div>
    </div>
</div>
