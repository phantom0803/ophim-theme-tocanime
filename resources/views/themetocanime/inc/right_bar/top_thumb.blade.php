<div class="sidebar-widget">
    <div class="title border-bottom-dash">
        <h2>{{$top['label']}}</h2>
    </div>
    <div class="sidebar-widget-content">
        <div class="tab-content">
            <div id="sbb-weekly">
                @foreach ($top['data'] as $movie)
                    <div class="sbb-item">
                        <div class="sbb-item-number">{{$loop->index + 1}}</div>
                        <div class="sbb-item-thumb">
                            <div class="img lazy r43"
                                data-original="{{$movie->getThumbUrl()}}">
                            </div>
                        </div>
                        <div class="sbb-item-content">
                            <a href="{{$movie->getUrl()}}">
                                <h3>{{$movie->name}}</h3>
                                <p>{{$movie->episode_current}}</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
