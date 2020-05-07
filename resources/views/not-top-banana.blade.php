@extends('layouts.app')

@section('title')
    That was very NOT top banana of you...
@endsection

@section('content')

    <article class="page--notTopBanana">

        <section class="page-section text-align--center">

            <div class="bg-elements">
                <div class="bg--light"></div>
                <div class="bg--inner-shadow"></div>
            </div>
            <div class="container">

                <header class="text-align--center">
                    <img class="notTopBanana" src="images/not-top-banana.png" alt="not top banana" />
                    <h1 class="text--muted">That was <span class="text--loss">not</span> very top banana of you <span class="text--black">{{$player->first_name}}</span></h1>
                </header>

                <p><a href="@route('players', ['nameKey' => $player->name_key])?sorry">Uhm&hellip; sorry?</a></p>
            </div>
        </section>

    </article>

@endsection