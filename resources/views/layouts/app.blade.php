<!doctype html>
<html>
<head>
    <title>@yield('title')Hudsonville Water Polo</title>

    <base href="@route('home')" />

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png?v=69k3Ao4nqq">
    <link rel="icon" type="image/png" href="/icons/favicon-32x32.png?v=69k3Ao4nqq" sizes="32x32">
    <link rel="icon" type="image/png" href="/icons/favicon-16x16.png?v=69k3Ao4nqq" sizes="16x16">
    <link rel="manifest" href="/manifest.json?v=69k3Ao4nqq">
    <link rel="mask-icon" href="/icons/safari-pinned-tab.svg?v=69k3Ao4nqq" color="#32345d">
    <link rel="shortcut icon" href="/icons/favicon.ico?v=69k3Ao4nqq">
    <meta name="msapplication-config" content="/icons/browserconfig.xml?v=69k3Ao4nqq">
    <meta name="theme-color" content="#f5d100">

    <?php if (getenv('APP_ENV') == 'local'): ?>
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <?php else: ?>
        <link rel="stylesheet" href="{{ mix('css/main.css') }}">
    <?php endif ?>

    <link rel="stylesheet" href="/css/scratch.css" />

    <script src="js/modernizr.custom.js"></script>
    <script src="https://kit.fontawesome.com/07e53d67dd.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="mp-container">
        <div id="mp-pusher" class="mp-pusher">

            @include('partials.menu.mobile')

            <div class="scroller"> <!-- emulating position: fixed for the mobile nav -->
                <div class="scroller-inner">
                    @include('partials.menu')

                    @yield('content')

                    @include('partials.footer')
                </div>
            </div>

        </div>
    </div>


    @include('partials.photoswipe')

    <script type="text/html" id="gallery-thumb-tmpl">@include('partials.photos.thumb-js')</script>
    <script type="text/html" id="gallery-no-photos-found-tmpl">@include('partials.photos.no-photos-found')</script>
    <script type="text/html" id="load-more-btn">@include('partials.photos.load-more-btn')</script>

    <script src="js/playerlist/{{ app('App\Models\ActiveSite')->domain }}.js"></script>
    <script src="{{ mix('js/main.js') }}"></script>

    @stack('scripts')

    @if(App::environment('live', 'production'))
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-26653906-1', 'auto');
            ga('send', 'pageview');
        </script>
    @else
        <script>
            ga = (...args) => console.log('GA', ...args);
        </script>
    @endif

</body>
</html>