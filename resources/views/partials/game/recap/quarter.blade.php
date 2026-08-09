{{-- used as a JS template --}}
<div class="quarter-holder">
    <section class="recap-quarter page-section bg--inner-shadow">
        <header class="divider divider--bottom">
            <h1><span class="text--muted">${quarterNameFirst}</span> <span>${quarterNameRemaining}</span></h1>
        </header>

        <div class="body container">
            <!-- updates will go here -->
        </div>
    </section>

    <footer class="recap-quarter-score">
        <div class="bg-elements">
            <div class="bg--dark"></div>
            <div class="bg--grid"></div>
        </div>

        <div class="container">
            <div class="row center-xs middle-xs">

                <div class="recap-quarter-status"></div>

                <div class="col-sm-3 col-md-2">
                    <div class="card result score score--us">
                        <header class="bg--grid">
                            <h1>Hudsonville</h1>
                        </header>
                        <div class="body">
                            <div class="score">
                                <h2>${scoreUs}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3 col-md-2">
                    <div class="card result score score--them">
                        <header class="bg--grid">
                            <h1>${opponent}</h1>
                        </header>
                        <div class="body">
                            <div class="score">
                                <h2>${scoreThem}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="recap-quarter-status"></div>

            </div>
        </div>

    </footer>
</div>
