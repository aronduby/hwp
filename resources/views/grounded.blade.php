@extends('layouts.app')

@section('title')
    GROUNDED!
@endsection

@section('content')

    <article class="page--grounded">

        <section class="page-section text-align--center">
            <div class="bg-elements">
                <div class="bg--light"></div>
                <div class="bg--inner-shadow"></div>

            </div>
            <div class="container">
                <header class="divider--bottom text-align--center">
                    <h1><span class="text--muted">Sorry,</span> {{$player->first_name}}'s Grounded</h1>
                </header>

                <p>{{$player->first_name}} can't come out to play right now, he's currently grounded. He knows what
                    he did. Once he's learned how to behave properly he can come out again.</p>

                <p><a href="@route('players', ['nameKey' => $player->name_key])?please">awww, pretty please?</a></p>
            </div>
        </section>

    </article>

@endsection