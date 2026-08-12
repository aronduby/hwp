@if($note)
<div class="recent recent--note note" data-note-id="{{$note->id}}" data-recent-id="{{$recent->id}}">
    <div class="bg-elements">
        <div class="bg--dark-gray"></div>
        <div class="bg--img" @if($note->photo) style="background-image:url({{$note->photo}})" @endif></div>
        <div class="bg--grid blend--overlay"></div>
        <div class="bg--bottom-third-primary"></div>
    </div>

    <a href="@route('notes', ['note' => $note->id])" title="@lang('recent.viewNote')" target="_blank">
        <div class="tag"><span><em>@lang('recent.note')</em></span></div>

        <h1>{{Str::limit($note->title, \App\Models\Recent::TITLE_LIMIT)}}</h1>

        <time datetime="@iso($note->created_at)">@stamp($note->created_at)</time>
    </a>

    <div class="recent--hover">
        <h1>@lang('recent.viewNote') <i class="fa fa-stick-note-o"></i></h1>
    </div>

    <div class="block-loading bg--dark">
        <div class="loader"></div>
    </div>
</div>
@endif
