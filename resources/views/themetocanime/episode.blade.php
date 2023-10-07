@extends('themes::themetocanime.layout')

@push('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('/themes/tocanime/plugins/flickity/flickity.min.css') }}" />
@endpush

@section('player')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="video-detail">
                        <style>
                            .video-wrap {
                                position: relative
                            }

                            .video-preload {
                                position: absolute;
                                top: 0;
                                left: 0;
                                right: 0;
                                bottom: 0;
                                background-color: #202125;
                                z-index: 2;
                                padding: 20px;
                                border: 1px dotted rgba(210, 118, 99, .5)
                            }

                            .video-preload p {
                                font-size: 20px
                            }

                            .vid-servers {
                                background: #111;
                                border: 1px solid #d27663;
                                position: relative;
                                padding-left: 100px
                            }

                            .vid-servers>span {
                                vertical-align: middle;
                                display: flex;
                                align-items: center;
                                background-color: #d27663;
                                border-radius: 3px;
                                width: 100px;
                                position: absolute;
                                left: 0;
                                top: 0;
                                height: 100%
                            }

                            .vid-servers .btn {
                                font-size: 13px;
                                text-transform: uppercase;
                                background-color: #444;
                                color: #fff
                            }

                            .vid-servers .btn.active {
                                background-color: #d27663
                            }

                            .vid-servers>span b {
                                margin: 0 auto
                            }
                            #wrapper-video {
                                height: 625px !important;
                            }
                            @media only screen and (max-width: 500px) {
                                #wrapper-video {
                                    height: 210px !important;
                                }
                            }
                            @media only screen and (min-width: 501px) and (max-width: 767px) {
                                #wrapper-video {
                                    height: 286px !important;
                                }
                            }
                            @media only screen and (min-width: 768px) and (max-width: 991px) {
                                #wrapper-video {
                                    height: 388px !important;
                                }
                            }
                            @media only screen and (min-width: 992px) and (max-width: 1199px) {
                                #wrapper-video {
                                    height: 525px !important;
                                }
                            }
                        </style>
                        <div class="video-wrap">
                            <div id="wrapper-video">
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="report_error" class="mx-1 my-1 btn bg-danger text-white"><i class="fa fa-bug" aria-hidden="true"></i> Báo Lỗi</button>
                        </div>
                        <div class="vid-servers">
                            <span><b>Link dự phòng</b></span>
                            @foreach ($currentMovie->episodes->where('slug', $episode->slug)->where('server', $episode->server) as $server)
                                <button onclick="chooseStreamingServer(this)" data-type="{{ $server->type }}"
                                    data-id="{{ $server->id }}" data-link="{{ $server->link }}"
                                    class="streaming-server mx-1 my-1 btn btn-effect">Link #{{ $loop->index + 1 }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div>
        <div class="box">
            <div class="pl-carousel tani-carousel" id="pl-contain">
                <div class="pl-carousel__item active" data-type="eps">
                    Danh sách tập phim
                </div>
            </div>
            <div class="ss-container">
                <div class="ss-item" data-type="eps">
                    @foreach ($currentMovie->episodes->sortBy([['server', 'asc']])->groupBy('server') as $server => $data)
                        <div id="me-newest" class="">
                            <p class="box-header">
                                <span>Danh sách tập <b>{{ $server }}</b></span>
                            </p>
                            <div class="me-list scroller" style="max-height: 200px;">
                                @foreach ($data->sortByDesc('name', SORT_NATURAL)->groupBy('name') as $name => $item)
                                    <a class="me-item @if ($item->contains($episode)) active @endif" href="{{ $item->sortByDesc('type')->first()->getUrl() }}">{{$name}}</a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="ss-item scroller"></div>
            </div>
        </div>
    </div>
    <div class="movie-title title">
        <div class="row">
            <h1 class="col-sm-12">
                <a href="{{$currentMovie->getUrl()}}">{{$currentMovie->name}}</a>
                <p class="movie-title__eps">Tập {{$episode->name}}</p>
            </h1>
            <div class="col-sm-12">
                <div id="movies-rating-star"></div>
                ({{$currentMovie->getRatingStar()}}
                sao
                /
                {{$currentMovie->getRatingCount()}} đánh giá)
            </div>
        </div>
    </div>
    <div class="box mt30">
        <p class="box-header">
            Thông tin phim
        </p>
        <div class="box-content">
            <p>{!! strip_tags($currentMovie->content) !!}</p>
        </div>
    </div>
    <div class="box mt30">
        <h3 class="title box-header"> Bình luận </h3>
        @include('themes::themetocanime.inc.comment')
    </div>
    @include('themes::themetocanime.inc.related_movie')
@endsection

@push('scripts')
    <script src="/themes/tocanime/player/js/p2p-media-loader-core.min.js"></script>
    <script src="/themes/tocanime/player/js/p2p-media-loader-hlsjs.min.js"></script>

    <script src="/js/jwplayer-8.9.3.js"></script>
    <script src="/js/hls.min.js"></script>
    <script src="/js/jwplayer.hlsjs.min.js"></script>

    <script>
        var episode_id = {{ $episode->id }};
        const wrapper = document.getElementById('wrapper-video');
        const vastAds = "{{ Setting::get('jwplayer_advertising_file') }}";

        function chooseStreamingServer(el) {
            const type = el.dataset.type;
            const link = el.dataset.link.replace(/^http:\/\//i, 'https://');
            const id = el.dataset.id;

            const newUrl =
                location.protocol +
                "//" +
                location.host +
                location.pathname.replace(`-${episode_id}`, `-${id}`);

            history.pushState({
                path: newUrl
            }, "", newUrl);
            episode_id = id;

            Array.from(document.getElementsByClassName('streaming-server')).forEach(server => {
                server.classList.remove('active');
            })
            el.classList.add('active');
            renderPlayer(type, link, id);
        }

        function renderPlayer(type, link, id) {
            if (type == 'embed') {
                if (vastAds) {
                    wrapper.innerHTML = `<div id="fake_jwplayer"></div>`;
                    const fake_player = jwplayer("fake_jwplayer");
                    const objSetupFake = {
                        key: "{{ Setting::get('jwplayer_license') }}",
                        aspectratio: "16:9",
                        width: "100%",
                        file: "/themes/tocanime/player/1s_blank.mp4",
                        volume: 100,
                        mute: false,
                        autostart: true,
                        advertising: {
                            tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                            client: "vast",
                            vpaidmode: "insecure",
                            skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                            skipmessage: "Bỏ qua sau xx giây",
                            skiptext: "Bỏ qua"
                        }
                    };
                    fake_player.setup(objSetupFake);
                    fake_player.on('complete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });

                    fake_player.on('adSkipped', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });

                    fake_player.on('adComplete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                } else {
                    if (wrapper) {
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                    }
                }
                return;
            }

            if (type == 'm3u8' || type == 'mp4') {
                wrapper.innerHTML = `<div id="jwplayer"></div>`;
                const player = jwplayer("jwplayer");
                const objSetup = {
                    key: "{{ Setting::get('jwplayer_license') }}",
                    aspectratio: "16:9",
                    width: "100%",
                    file: link,
                    playbackRateControls: true,
                    playbackRates: [0.25, 0.75, 1, 1.25],
                    sharing: {
                        sites: [
                            "reddit",
                            "facebook",
                            "twitter",
                            "googleplus",
                            "email",
                            "linkedin",
                        ],
                    },
                    volume: 100,
                    mute: false,
                    autostart: true,
                    logo: {
                        file: "{{ Setting::get('jwplayer_logo_file') }}",
                        link: "{{ Setting::get('jwplayer_logo_link') }}",
                        position: "{{ Setting::get('jwplayer_logo_position') }}",
                    },
                    advertising: {
                        tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                        client: "vast",
                        vpaidmode: "insecure",
                        skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                        skipmessage: "Bỏ qua sau xx giây",
                        skiptext: "Bỏ qua"
                    }
                };

                if (type == 'm3u8') {
                    const segments_in_queue = 50;

                    var engine_config = {
                        debug: !1,
                        segments: {
                            forwardSegmentCount: 50,
                        },
                        loader: {
                            cachedSegmentExpiration: 864e5,
                            cachedSegmentsCount: 1e3,
                            requiredSegmentsPriority: segments_in_queue,
                            httpDownloadMaxPriority: 9,
                            httpDownloadProbability: 0.06,
                            httpDownloadProbabilityInterval: 1e3,
                            httpDownloadProbabilitySkipIfNoPeers: !0,
                            p2pDownloadMaxPriority: 50,
                            httpFailedSegmentTimeout: 500,
                            simultaneousP2PDownloads: 20,
                            simultaneousHttpDownloads: 2,
                            // httpDownloadInitialTimeout: 12e4,
                            // httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpDownloadInitialTimeout: 0,
                            httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpUseRanges: !0,
                            maxBufferLength: 300,
                            // useP2P: false,
                        },
                    };
                    if (Hls.isSupported() && p2pml.hlsjs.Engine.isSupported()) {
                        var engine = new p2pml.hlsjs.Engine(engine_config);
                        player.setup(objSetup);
                        jwplayer_hls_provider.attach();
                        p2pml.hlsjs.initJwPlayer(player, {
                            liveSyncDurationCount: segments_in_queue, // To have at least 7 segments in queue
                            maxBufferLength: 300,
                            loader: engine.createLoaderClass(),
                        });
                    } else {
                        player.setup(objSetup);
                    }
                } else {
                    player.setup(objSetup);
                }

                const resumeData = 'OPCMS-PlayerPosition-' + id;

                player.on('ready', function() {
                    if (typeof(Storage) !== 'undefined') {
                        if (localStorage[resumeData] == '' || localStorage[resumeData] == 'undefined') {
                            console.log("No cookie for position found");
                            var currentPosition = 0;
                        } else {
                            if (localStorage[resumeData] == "null") {
                                localStorage[resumeData] = 0;
                            } else {
                                var currentPosition = localStorage[resumeData];
                            }
                            console.log("Position cookie found: " + localStorage[resumeData]);
                        }
                        player.once('play', function() {
                            console.log('Checking position cookie!');
                            console.log(Math.abs(player.getDuration() - currentPosition));
                            if (currentPosition > 180 && Math.abs(player.getDuration() - currentPosition) >
                                5) {
                                player.seek(currentPosition);
                            }
                        });
                        window.onunload = function() {
                            localStorage[resumeData] = player.getPosition();
                        }
                    } else {
                        console.log('Your browser is too old!');
                    }
                });

                player.on('complete', function() {
                    if (typeof(Storage) !== 'undefined') {
                        localStorage.removeItem(resumeData);
                    } else {
                        console.log('Your browser is too old!');
                    }
                })

                function formatSeconds(seconds) {
                    var date = new Date(1970, 0, 1);
                    date.setSeconds(seconds);
                    return date.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
                }
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const episode = '{{ $episode->id }}';
            let playing = document.querySelector(`[data-id="${episode}"]`);
            if (playing) {
                playing.click();
                return;
            }

            const servers = document.getElementsByClassName('streaming-server');
            if (servers[0]) {
                servers[0].click();
            }
        });
    </script>

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

    <script>
        $("#report_error").click(() => {
            fetch("{{ route('episodes.report', ['movie' => $currentMovie->slug, 'episode' => $episode->slug, 'id' => $episode->id]) }}", {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    message: ''
                })
            });
            $("#report_error").remove();
            alert("Báo lỗi thành công!")
        })
    </script>

    <script type="text/javascript" src="{{ asset('/themes/tocanime/plugins/flickity/flickity.smart.min.js') }}"></script>
    {!! setting('site_scripts_facebook_sdk') !!}
@endpush
