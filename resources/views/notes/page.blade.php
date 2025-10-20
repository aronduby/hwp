@extends('layouts.app')

@section('title')
    {{$note->title}} -
@endsection

@section('content')
    <article class="page--note">

        <header class="page-header header--small">
            <div class="bg-elements">
                <div class="bg--gradient"></div>
                <div class="bg--img"
                    @if($note->photo)
                        style="background-image: url({{ $note->photo }})"
                    @endif
                ></div>
            </div>
            <div class="container">
                <h1>{{$note->title}}</h1>
                <h2>Posted <time class="inline">@stamp($note->updated_at)</time></h2>
            </div>
        </header>

        <section class="page-section">
            <div class="container">
                <div class="note note-content">
                    {!! $note->content !!}
                </div>
            </div>
        </section>
    </article>
@endsection