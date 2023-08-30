<html>
<head>
    <title>@yield('title')Hudsonville Water Polo</title>

    <base href="/" />

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png?v=69k3Ao4nqq">
    <link rel="icon" type="image/png" href="/icons/favicon-32x32.png?v=69k3Ao4nqq" sizes="32x32">
    <link rel="icon" type="image/png" href="/icons/favicon-16x16.png?v=69k3Ao4nqq" sizes="16x16">
    <link rel="manifest" href="/icons/manifest.json?v=69k3Ao4nqq">
    <link rel="mask-icon" href="/icons/safari-pinned-tab.svg?v=69k3Ao4nqq" color="#32345d">
    <link rel="shortcut icon" href="/icons/favicon.ico?v=69k3Ao4nqq">
    <meta name="msapplication-config" content="/icons/browserconfig.xml?v=69k3Ao4nqq">
    <meta name="theme-color" content="#ffffff">

    <?php if (getenv('APP_ENV') == 'local'): ?>
    <link rel="stylesheet" href="{{ asset('css/picker.css') }}">
    <?php else: ?>
    <link rel="stylesheet" href="{{ mix('css/picker.css') }}">
    <?php endif ?>

    <link rel="stylesheet" href="/css/scratch.css" />
</head>
<body>

    <main class="picker picker--{{$sites->count()}}">
        <header>
            <h1 class="site-name text--shadow text--white">Hudsonville<wbr><span class="text--accent">Water</span><wbr>Polo</h1>
        </header>

        @foreach($sites as $site)
            <section class="picker-site">
                <a href="//{{$site->domain}}.{{$tld}}">
                    <div class="bg-elements">
                        <div class="bg--gradient"></div>
                        <div class="bg--img"
                             @if($site->featuredPhotos->isNotEmpty())
                                style="background-image: url({{ $site->featuredPhotos->random()->photo }})"
                             @endif
                        ></div>
                    </div>

                    <div class="picker-content">
                        <h2 class="text--shadow-small"><span>{{$site->subtitle}}</span></h2>
                    </div>
                </a>
            </section>
        @endforeach
    </main>


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
    @endif

</body>
</html>