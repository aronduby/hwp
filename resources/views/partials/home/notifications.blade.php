<section class="notifications bg--bright bg--inner-shadow text--white">
    <div class="bg-elements bg--grid blend--overlay"></div>

    <div class="container notifications-container">
        <div class="notifications-text">
            <h1>We Have Push Notifications!</h1>
            <p>Here's some rand text stuff. I don't know what to put here, but it'll be some good hype text. Here's some rand text stuff. I don't know what to put here, but it'll be some good hype text.</p>
        </div>

        <hr />

        <div id="notificationActions" class="notifications-actions" data-state="loading">
            <div class="state state--loading">
                <div class="loader loader--sm"></div>
            </div>

            <div class="state state--with-icon state--blocked">
                <header>
                    <i class="fa-solid fa-ban fa-beat animate-once text--loss"></i>
                    <h2>Blocked!</h2>
                </header>
                <p>You currently have notifications blocked. Reset the site permissions and then refresh the page to start again.</p>
            </div>

            <div class="state state--with-icon state--subscribed">
                <header>
                    <i class="fa-solid fa-bell fa-shake animate-once text--success"></i>
                    <h2>Subscribed!</h2>
                </header>
                <p>You can always <a id="fcm-unsubscribe" href="#">unsubscribe</a> if you would like.</p>
            </div>

            <div class="state state--not-subscribed">
                <button id="fcm-subscribe" class="btn btn--lg btn--bright-accent-alt btn--loader fa-bounce animate-once">
                    <i class="fa-solid fa-bell"></i>
                    Subscribe!
                    <div class="loader-wrapper"><div class="loader loader--sm"></div></div>
                </button>
                <p class="unsubscribed-message">you have been unsubscribed</p>
            </div>

            <div class="state state--with-icon state--not-supported">
                <header>
                    <i class="fa-solid fa-skull-crossbones fa-shake animate-once text--smoke"></i>
                </header>
                <p>Sorry, but it looks like this device doesn't support push notifications.</p>
            </div>

            <div class="state state--with-icon state--error">
                <header>
                    <i class="fa-solid fa-triangle-exclamation fa-bounce animate-once text--warn"></i>
                </header>
                <p>Sorry, but some sort of error happened. Please try again later.</p>
            </div>
        </div>
    </div>
</section>