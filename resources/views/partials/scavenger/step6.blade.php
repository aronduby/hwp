<!doctype html>
<html lang="en">
<head>

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Step 6</title>

    <?php if (getenv('APP_ENV') == 'local'): ?>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <?php else: ?>
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">
    <?php endif ?>

    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=PT+Sans+Narrow);

        body {
            font-family: 'PT Sans Narrow', 'Arial Narrow', sans-serif;
            font-size:medium;
            background: white radial-gradient(white, rgba(0, 0, 0, .1)) fixed;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        article > .container {
            max-width: 1000px;
        }

        .lines {
            position: absolute;
            width: 10em;
            height: 10em;
            margin-top: -10em;
            left: 50%;
            margin-left: -5em;
            transform: rotateZ(-30deg) scale(1.5);
            filter: drop-shadow(0 0 1.2em rgba(0,0,0,.6));
        }

        .lines .container {
            width: auto;
            padding: 0;
            margin: 0;
            position: absolute;
            top: 0; right: 0;
            bottom: 0; left: 0;
        }

        .lines:after,
        .lines .container::before {
            content: '';
            background: #f3f3f3;
            position: absolute;
            transform-origin: 50% 100%;
            width: 5%;
            left: 50%;
            margin-left: -2.5%;
        }

        .lines .container:nth-child(1)::before {
            height: 20%;
            bottom: 30%;
        }

        .lines .container:nth-child(2)::before {
            height: 38%;
            top: 12%;
            left: 49%;
            transform: rotateZ(60deg);
        }

        .lines:after {
            width: 5%;
            height: 5%;
            top: 50%;
            margin-top: -2.5%;
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 */
            padding-top: 25px;
            height: 0;
        }
        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>

    @include('partials.ga')

    <script src="{{ mix('js/components.js') }}"></script>
    <script src="{{ mix('js/scavenger/step6.js') }}"></script>

</head>
<body>
<article>

    <div class="container">
        <div class="lines">
            <div class="container"></div>
            <div class="container"></div>
        </div>

        <div class="video-wrapper">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/TqSxQrDYsIc?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>

</article>
</body>
</html>

