<div id="badge-{{$badge->uid}}" class="badge @if($badge->shiny) badge--shiny @endif">
    @if($badge->shiny)
        <div class="shine"></div>
    @endif
    <img src="badges/{{$badge->image}}" alt="{{$badge->title}}" aria-describedby="badge-{{$badge->uid}}-tooltip" />
    <div id="badge-{{$badge->uid}}-tooltip" class="hover" role="tooltip">
        <h2 class="title">{{$badge->title}}</h2>
        <p class="description">{{$badge->description}}</p>
    </div>

    @if($badge->shiny)
        <!--suppress CssUnknownTarget -->
        <style>
            #badge-{{$badge->uid}} .shine {
                -webkit-mask-image: url(/badges/{{$badge->image}});
                mask-image: url(/badges/{{$badge->image}});
            }
        </style>
    @endif
</div>