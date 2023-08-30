<!doctype html>
<html lang="en">
<head>

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Step 4</title>

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
        }

        img#body-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            filter: blur(5px) brightness(75%);
        }

        .page-header {
            padding: 0;
            padding-bottom: 2em;
            text-align: center;
        }

        article {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            pointer-events: none;
        }

        form {
            position: relative;
            pointer-events: all;
        }

        .particle-holder {
            display: inline-block;
            position: relative;
        }

        input, button {
            font-size: 2.5em;
            padding: .3em;
            width: 2em;
            margin: 0 .05em;
            text-align: center;
            border: 1px solid transparent;
            border-radius: 4px;
            outline: 0;
        }

        input {
            box-shadow: inset 0 0 4px rgba(0,0,0,.8);
            font-weight: bold;
            background-clip: padding-box;
        }

        button {
            width: auto;
            background: #1188FE;
            color: white;
            padding: .15em 1em;
            border-radius: 0.9em;
            font-size: 2.2em;
        }
        button:hover,
        button:focus {
            box-shadow: 0 0 5px #1188fe;
        }

        input.dirty:invalid {
            border: 1px solid red;
            filter: drop-shadow(0 0 5px red);
        }

        form:invalid button,
        button[disabled] {
            opacity: .2;
            cursor: not-allowed;
            background: grey;
        }

        /* Failure */
        .status--fail input {
            animation-name: shake, glow-red;
            animation-duration: 1s;
            animation-iteration-count: 1;
        }

        @keyframes shake {
            0%, 20%, 40%, 60%, 80% {
                transform: translateX(8px);
            }
            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-8px);
            }
        }

        @keyframes glow-red {
            50% {
                border-color: red;
            }
        }

        /* Success */
        .particle {
            opacity: 0;
            position: absolute;
            animation: confetti 1.5s ease-in-out /*infinite*/;
        }
        .particle.c1 {
            background-color: rgba(76, 175, 80, 0.5);
        }
        .particle.c2 {
            background-color: rgba(156, 39, 176, 0.5);
        }

        @keyframes confetti {
            0% {
                opacity:0;
                transform:translateY(50%) rotate(0deg);
            }
            10% {
                opacity:1;
            }
            35% {
                transform:translateY(-1000%) rotate(270deg);
            }
            80% {
                opacity:1;
            }
            100% {
                opacity:0;
                transform:translateY(1900%) rotate(1440deg);
            }
        }
    </style>

    @include('partials.ga')

    <script src="{{ mix('js/components.js') }}"></script>
    <script src="{{ mix('js/scavenger/step4.js') }}"></script>

</head>
<body>

    <img id="body-bg" src="images/step4.jpg" alt="step 4" />

    <article>
        <header class="page-header">
            <h1><span class="text--accent">Hold on,</span> I have to send streaks&hellip;</h1>
        </header>

        <form name="streaker" autocomplete="off" novalidate>
            <div class="particle-holder">
                <input name="first" tabindex="1" required>
                <input name="second" tabindex="2" required>
            </div>
            <button type="submit" tabindex="3">Send</button>
        </form>
    </article>

</body>
</html>

