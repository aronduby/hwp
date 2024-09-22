<section class="notifications bg--bright bg--inner-shadow text--white">
    <div class="bg-elements bg--grid blend--overlay"></div>

    <div class="container notifications-container">
        <div class="notifications-text">
            <h1>We Have Push Notifications!</h1>
            <p>Exciting News! We're thrilled to announce we've added push notifications, ensuring you stay in the loop with all the latest scores, rankings, and updates. Don't miss a thing – subscribe today and be the first to know!</p>
            <p class="notifications--fine-print">This is a beta release, so expect bugs and please let me know if you have issues.</p>
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

            <div class="state state--not-installed">
                <header>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill-rule="nonzero"><path d="m6 6c.27614237 0 .5.22385763.5.5s-.22385763.5-.5.5c-.55228475 0-1 .44771525-1 1 0 .27614237-.22385763.5-.5.5s-.5-.22385763-.5-.5c0-1.1045695.8954305-2 2-2z"/><path d="m6 7c-.27614237 0-.5-.22385763-.5-.5s.22385763-.5.5-.5h2.5c.27614237 0 .5.22385763.5.5s-.22385763.5-.5.5z"/><path d="m6 22c-.27614237 0-.5-.2238576-.5-.5s.22385763-.5.5-.5h12c.2761424 0 .5.2238576.5.5s-.2238576.5-.5.5z"/><path d="m15.5 7c-.2761424 0-.5-.22385763-.5-.5s.2238576-.5.5-.5h2.5c.2761424 0 .5.22385763.5.5s-.2238576.5-.5.5z"/><path d="m4 8c0-.27614237.22385763-.5.5-.5s.5.22385763.5.5v12c0 .2761424-.22385763.5-.5.5s-.5-.2238576-.5-.5z"/><path d="m19 8c0-.27614237.2238576-.5.5-.5s.5.22385763.5.5v12c0 .2761424-.2238576.5-.5.5s-.5-.2238576-.5-.5z"/><path d="m20 8c0 .27614237-.2238576.5-.5.5s-.5-.22385763-.5-.5c0-.55228475-.4477153-1-1-1-.2761424 0-.5-.22385763-.5-.5s.2238576-.5.5-.5c1.1045695 0 2 .8954305 2 2z"/><path d="m4 20c0-.2761424.22385763-.5.5-.5s.5.2238576.5.5c0 .5522847.44771525 1 1 1 .27614237 0 .5.2238576.5.5s-.22385763.5-.5.5c-1.1045695 0-2-.8954305-2-2z"/><path d="m18 22c-.2761424 0-.5-.2238576-.5-.5s.2238576-.5.5-.5c.5522847 0 1-.4477153 1-1 0-.2761424.2238576-.5.5-.5s.5.2238576.5.5c0 1.1045695-.8954305 2-2 2z"/><path d="m11.5.5c0-.27614237.2238576-.5.5-.5s.5.22385763.5.5v14c0 .2761424-.2238576.5-.5.5s-.5-.2238576-.5-.5z"/><path d="m12 1.20710678-2.64644661 2.64644661c-.19526215.19526215-.51184463.19526215-.70710678 0s-.19526215-.51184463 0-.70710678l2.99999999-3c.1952622-.19526215.5118446-.19526215.7071068 0l3 3c.1952621.19526215.1952621.51184463 0 .70710678-.1952622.19526215-.5118446.19526215-.7071068 0z"/></g></svg>
                    <div class="iconPosition"><i class="fa-regular fa-square-plus fa-bounce animate-once text--bright-accent-alt"></i></div>
                </header>
                <h2>Add to Home Screen</h2>
                <p>Use the browser's share menu to add the site to your home screen to enable push notification support. Not
                    sure how? Follow along with <a href="https://www.youtube.com/watch?v=I4e1aoi0P-o" target="_blank">this
                        TechTips video</a>.</p>
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