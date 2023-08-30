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

    <script id="quarter-tmpl" type="text/html">
        @include('partials.game.recap.quarter')
    </script>

    <script id="update-tmpl" type="text/html">
        @include('partials.game.recap.update')
    </script>
@endsection

@push('scripts')
<script src="{{mix('js/recap.js')}}"></script>
@endpush