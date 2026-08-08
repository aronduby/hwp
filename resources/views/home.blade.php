@extends('layouts.app')

@section('content')
    <article class="page--home">

        {!! $header !!}

        {!! $results !!}

        {!! $notifications !!}

        {!! $badges !!}

        {!! $content !!}

    </article>
@endsection

@push('scripts')
    @vite(['resources/js/home.js'])
@endpush
