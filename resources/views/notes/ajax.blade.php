<article class="note">
    <header class="bg--dark bg--grid">
        <h1>{{$note->title}}</h1>
        <time><a href="/notes/{{$note->id}}">@stamp($note->updated_at)</a></time>
    </header>

    <div class="body">
        {!! $note->content !!}
    </div>
</article>