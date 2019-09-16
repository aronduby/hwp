@extends('tournament')

@section('title')
    @lang('game.photos') - {{$tournament->title}} -
@endsection

@section('tournament-content')

    <div class="page-section container">
        <div class="album-photos full-gallery" data-gallery-path="@route('gallery.album', ['album' => $tournament->album->id])">
            <div class="recap-loader">
                <div class="loading bg--dark bg--grid-small">
                    <div class="loader"></div>
                    <h1>@lang('misc.loading')</h1>
                </div>
            </div>
        </div>
    </div>

@endsection