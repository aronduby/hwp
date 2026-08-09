@extends('game')

@section('title')
    @lang('game.recap') - {{$game->title}} -
@endsection

@section('game-content')
    <div class="recap">
        <div class="recap-loader bg--inner-shadow">
            <div class="loading bg--dark bg--grid-small">
                <div class="loader"></div>
                <h1>@lang('misc.loading')</h1>
            </div>
        </div>
    </div>

    <pre class="json">{{json_encode($game->updates->json, JSON_PRETTY_PRINT)}}</pre>
    <script>
        var recap = {
            game_id: {{$game->id}},
            updates: {!! json_encode($game->updates->json, JSON_PRETTY_PRINT) !!}
        }
    </script>

    <template id="quarter-tmpl">@include('partials.game.recap.quarter')</template>
    <template id="update-tmpl">@include('partials.game.recap.update')</template>
@endsection

@push('scripts')
    @vite(['resources/js/recap.js'])
@endpush
