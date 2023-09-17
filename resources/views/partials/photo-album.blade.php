<div class="recent recent--photos photo-album">
    <div class="bg-elements">
        <div class="bg--primary"></div>
        <div class="bg--img"
             @if($album->cover)
                style="background-image:url({{$album->cover->photo}})"
            @endif
        ></div>
        <div class="bg--grid blend--overlay"></div>
        <div class="bg--bottom-third-primary"></div>
    </div>

    <a href="@route('album', ['id' => $album->id])" title="@lang('photos.viewAlbum')">
        @if($album->photos_count)
            <div class="tag"><span><em>@number($album->photos_count) @lang('photos.photos')</em></span></div>
        @endif

        <h1>{{str_limit($album->title, \App\Models\Recent::TITLE_LIMIT)}}</h1>

        <time datetime="@iso($album->updated_at)">@stamp($album->updated_at)</time>

        <div class="recent--hover">
            <h1><i class="fa fa-search-plus"></i>@lang('photos.viewAlbum')</h1>
        </div>
    </a>
</div>