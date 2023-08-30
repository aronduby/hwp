<!doctype html>
<html lang="en">
<head>

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Step 3</title>

    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=PT+Sans+Narrow);

        body {
            font-family: 'PT Sans Narrow', 'Arial Narrow', sans-serif;
            font-size:medium;
            background: white radial-gradient(white, rgba(0, 0, 0, .1)) fixed;
        }

        .badge-holder {
            display: flex;
            flex-wrap: wrap;
            margin: 10vw;
            justify-content: center;
        }

        .badge-holder div {
            position: relative;
        }

        .badge-holder img {
            filter: drop-shadow(0 0 1em rgba(0,0,0,.2));
        }

        .badge-holder span {
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 6em;
            mix-blend-mode: difference;
        }
    </style>

    @include('partials.ga')

    <script src="{{ mix('js/components.js') }}"></script>
    <script src="{{ mix('js/scavenger/step3.js') }}"></script>

</head>
<body>
<article>

    <div class="badge-holder">
        <?php
        $str = "Do you ever feel like youre trying to run into a brick wall";
        $str = str_replace(" ", "", $str);
        ?>
        @for ($i = 0; $i < 47; $i++)
            <div>
                <img src="badges/team-brick-wall.png" alt="brickwall" />
                <span>{{$str[$i]}}</span>
            </div>
        @endfor
    </div>

</article>
</body>
</html>

