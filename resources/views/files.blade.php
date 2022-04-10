@extends('layouts.app')

@section('title')
    @lang('files.files') -
@endsection

@section('content')
    <article class="page--parents">

        <header class="page-header header--small">
            <div class="bg-elements">
                <div class="bg--gradient"></div>
                <div class="bg--img"></div>
            </div>
            <div class="container">
                <h1>@lang('files.useful') <span class="text--muted">@lang('files.files')</span></h1>
            </div>
        </header>

        <div class="page-section container">
            <iframe class="parent-docs google-drive" src="https://drive.google.com/embeddedfolderview?id={{$googleFolderID}}#list"></iframe>
        </div>

    </article>
@endsection