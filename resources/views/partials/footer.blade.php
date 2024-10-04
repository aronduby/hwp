<footer>
    <section class="footer-section follow-us bg--accent bg--inner-shadow">
        <div class="bg-elements bg--grid blend--overlay"></div>

        <div class="container text-align--center">
            @if(app('App\Models\ActiveSite')->settings->get('instagram.screenName'))
                <h1>Follow us on Instagram <a href="https://www.instagram.com/{{app('App\Models\ActiveSite')->settings->get('instagram.screenName')}}" target="_blank"><i class="fa-brands fa-instagram"></i> {{app('App\Models\ActiveSite')->settings->get('instagram.screenName')}}</a></h1>
            @endif
        </div>
    </section>

    <section class="footer-section main bg--primary">
        <div class="container">
            {{--<div class="row">
                <div class="col-xs-12 col-md-4">
                    <section class="twitter-embed">
                        <a class="twitter-timeline"
                           href="https://twitter.com/{{app('App\Models\ActiveSite')->settings->get('twitter.screenName')}}?ref_src=twsrc%5Etfw"
                           data-dnt="true"
                           data-link-color="#6ba3d0"
                           data-chrome="noheader nofooter noborders transparent"
                           data-tweet-limit="1"
                           data-theme="dark"
                       >Tweets by {{app('App\Models\ActiveSite')->settings->get('twitter.screenName')}}</a>
                       <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                    </section>
                </div>
                <div class="col-xs-12 col-md-8">
                    @include('partials.playerlist')
                </div>
            </div>--}}
            @include('partials.playerlist')
        </div>
    </section>

    <section class="footer-section sub text-align--center">
        <p>{!! app('App\Models\ActiveSite')->settings->get('thanks') !!}</p>
    </section>

</footer>