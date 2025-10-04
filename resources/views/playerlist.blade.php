@inject('playerList', 'App\Services\PlayerListService')

@extends('layouts.app')

@section('title')
    Player List -
@endsection

@section('content')
    <article class="page--player-list">

        <header class="page-header header--small">
            <div class="bg-elements">
                <div class="bg--gradient"></div>
                <div class="bg--img" style="background-image: url(images/ezra-ball.png);"></div>
            </div>
            <div class="container">
                <h1>@lang('menu.playerlist')</h1>
            </div>
        </header>

        @if ($playerList->team('V'))
            <section class="page-section bg--light">
                <div class="bg-elements">
                    <div class="bg--light"></div>
                    <div class="bg--inner-shadow"></div>
                    <div class="bg--grid"></div>
                </div>
                <div class="container">
                    <header class="divider--bottom text-align--center">
                        <h1>@lang('misc.V')</h1>
                    </header>

                    <ul class="player-list team--varsity">
                        @foreach($playerList->team('V')->sortByNumber('V') as $player)
                            <li>
                                <a href="@route('players', ['nameKey' => $player->nameKey])">
                                    <span class="player--number">{{$player->getNumber('V')}}</span>
                                    <span class="player--name">{{$player->name}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        @if ($playerList->team('JV'))
            <section class="page-section bg--smoke">
                <div class="container">
                    <header class="divider--bottom text-align--center">
                        <h1>@lang('misc.JV')</h1>
                    </header>

                    <ul class="player-list team--jv">
                        @foreach($playerList->team('JV')->sortByNumber('JV') as $player)
                            <li>
                                <a href="@route('players', ['nameKey' => $player->nameKey])">
                                    <span class="player--number">{{$player->getNumber('JV')}}</span>
                                    <span class="player--name">{{$player->name}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        @if($playerList->team('STAFF'))
        <section class="page-section container">
            <header class="divider--bottom text-align--center">
                <h1>@lang('misc.STAFF')</h1>
            </header>

            <ul class="player-list team--staff">
                @foreach($playerList->team('STAFF') as $player)
                    <li>
                        <a href="@route('players', ['nameKey' => $player->nameKey])">
                            <span class="player--name">{{$player->name}}</span>
                            <span class="player--title">{{$player->title}}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
        @endif


    </article>
@endsection