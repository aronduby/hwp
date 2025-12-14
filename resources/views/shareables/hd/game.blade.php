@extends('shareables.base')

@section('content')

    @if($photo)
        @include('shareables.parts.bg-image', [
            'photo' => $photo,
            'canvas' => $dimensions
        ])
    @else
        @include('shareables.parts.bg-pattern', [
            'pattern' => $pattern,
            'dimensions' => $dimensions
        ])
    @endif

    @include('shareables.parts.logo', [
        'x' => '57.23',
        'y' => '964.653'
    ])

    @include('shareables.parts.url', [
        'x' => '1539',
        'y' => '1001.226'
    ])

    @include('shareables.parts.stripe', [
        'y' => 338,
        'width' => $dimensions['width'],
        'height' => 240,
    ])

    @include('shareables.parts.scores', [
        'x' => 627,
        'y' => 267,
        'game' => $game
    ])

    @if($game->badge)
        @include('shareables.parts.badge', [
            'x' => 120,
            'y' => 598,
            'showTitle' => true,
            'badge' => $game->badge
        ])
    @endif

@endsection