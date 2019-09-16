@extends('layouts.app')

@section('content')
    <article class="page--tournament">

        @include('partials.tournament.header')

        @yield('tournament-content')

    </article>
@endsection