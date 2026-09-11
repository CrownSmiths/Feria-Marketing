<footer class="py-5 pt-10 bg-dark text-light">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-6 md-3">
                <h5 class="font-semibold text-[#ADEFD1]"><strong>CrownSmith</strong></h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-light" data-discover="true">Inicio</a></li>
                    <li class="nav-item mb-2"><a href="#producto" class="nav-link p-0 text-light" data-discover="true">Producto</a></li>
                    <li class="nav-item mb-2"><a href="#equipo" class="nav-link p-0 text-light" data-discover="true">Equipo</a></li>
                    <li class="nav-item mb-2"><a href="#contacto" class="nav-link p-0 text-light" data-discover="true">Contacto</a></li>
                </ul>
            </div>
            <div class="col-8 col-md-6 md-3">
                <h5 class="font-semibold text-[#ADEFD1]"><strong>Contacto</strong></h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="mailto:crownsmiths.team@gmail.com" class="nav-link p-0 text-light">crownsmiths.team@gmail.com</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="d-flex justify-content-center mb-4">
            <img src="img/logo_Crownsmith_oscuro.svg" alt="CrownSmith Logo" style="height: 50px;">
        </div>
        <div class="d-flex justify-content-between py-4 my-4 border-top">
            <p>&copy; 2026 CrownSmith. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.8s ease-out forwards;
        opacity: 0;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('#producto, #equipo, #contacto, .card, .col').forEach(el => {
            observer.observe(el);
        });
    });
</script>
<script>
    (function() {

        'use strict';

        const ANALYTICS_ENDPOINT = '/analytics/analytics.php';

        // -----------------------------------------------------
        // ENVÍO DE EVENTOS
        // -----------------------------------------------------

        function track(eventName, extra = {}) {

            const data = {
                event: eventName,

                page: window.location.pathname,

                device_type: getDeviceType(),
                browser: getBrowser(),
                os: getOS(),
                language: navigator.language || null,

                referrer_domain: getReferrerDomain(),

                utm_source: getQueryParam('utm_source'),
                utm_medium: getQueryParam('utm_medium'),
                utm_campaign: getQueryParam('utm_campaign'),
                utm_content: getQueryParam('utm_content'),
                utm_term: getQueryParam('utm_term'),

                ...extra
            };

            const body = JSON.stringify(data);

            // sendBeacon evita perder eventos al abandonar la página.
            if (navigator.sendBeacon) {

                const blob = new Blob(
                    [body], {
                        type: 'application/json'
                    }
                );

                navigator.sendBeacon(
                    ANALYTICS_ENDPOINT,
                    blob
                );

            } else {

                fetch(ANALYTICS_ENDPOINT, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: body,
                    keepalive: true
                }).catch(() => {});

            }
        }

        // -----------------------------------------------------
        // DEVICE
        // -----------------------------------------------------

        function getDeviceType() {

            const width = window.innerWidth;

            if (width < 768) {
                return 'mobile';
            }

            if (width < 1024) {
                return 'tablet';
            }

            return 'desktop';
        }

        // -----------------------------------------------------
        // BROWSER
        // -----------------------------------------------------

        function getBrowser() {

            const ua = navigator.userAgent;

            if (/Edg/i.test(ua)) {
                return 'Edge';
            }

            if (/Chrome/i.test(ua) && !/Edg/i.test(ua)) {
                return 'Chrome';
            }

            if (/Firefox/i.test(ua)) {
                return 'Firefox';
            }

            if (/Safari/i.test(ua) && !/Chrome/i.test(ua)) {
                return 'Safari';
            }

            return 'Other';
        }

        // -----------------------------------------------------
        // OS
        // -----------------------------------------------------

        function getOS() {

            const ua = navigator.userAgent;

            if (/Windows/i.test(ua)) {
                return 'Windows';
            }

            if (/Mac OS/i.test(ua)) {
                return 'macOS';
            }

            if (/Android/i.test(ua)) {
                return 'Android';
            }

            if (/iPhone|iPad/i.test(ua)) {
                return 'iOS';
            }

            if (/Linux/i.test(ua)) {
                return 'Linux';
            }

            return 'Other';
        }

        // -----------------------------------------------------
        // REFERRER
        // -----------------------------------------------------

        function getReferrerDomain() {

            if (!document.referrer) {
                return null;
            }

            try {

                return new URL(
                    document.referrer
                ).hostname;

            } catch (e) {

                return null;
            }
        }

        // -----------------------------------------------------
        // UTM
        // -----------------------------------------------------

        function getQueryParam(name) {

            const params = new URLSearchParams(
                window.location.search
            );

            return params.get(name);
        }

        // -----------------------------------------------------
        // PAGE VIEW
        // -----------------------------------------------------

        track('page_view');

        // -----------------------------------------------------
        // SCROLL
        // -----------------------------------------------------

        const scrollMilestones = {
            25: false,
            50: false,
            75: false,
            100: false
        };

        function checkScroll() {

            const documentHeight =
                document.documentElement.scrollHeight -
                window.innerHeight;

            if (documentHeight <= 0) {
                return;
            }

            const scrollPosition =
                window.scrollY / documentHeight;

            const percentage =
                Math.round(scrollPosition * 100);

            [25, 50, 75, 100].forEach(
                milestone => {

                    if (
                        percentage >= milestone &&
                        !scrollMilestones[milestone]
                    ) {

                        scrollMilestones[milestone] = true;

                        track(
                            `scroll_${milestone}`
                        );
                    }
                }
            );
        }

        window.addEventListener(
            'scroll',
            checkScroll, {
                passive: true
            }
        );

        // -----------------------------------------------------
        // LINKEDIN
        // -----------------------------------------------------

        document.querySelectorAll(
            '[data-analytics-linkedin]'
        ).forEach(link => {

            link.addEventListener(
                'click',
                function() {

                    const person =
                        this.dataset.analyticsLinkedin;

                    if (
                        /^team_[1-6]$/.test(person)
                    ) {

                        track(
                            `linkedin_${person}`
                        );

                    } else {

                        track(
                            'linkedin_startup'
                        );
                    }
                }
            );
        });

        // -----------------------------------------------------
        // INSTAGRAM
        // -----------------------------------------------------

        document.querySelectorAll(
            '[data-analytics-instagram]'
        ).forEach(link => {

            link.addEventListener(
                'click',
                function() {

                    track(
                        'instagram_startup'
                    );

                }
            );
        });

        // -----------------------------------------------------
        // MAILTO
        // -----------------------------------------------------

        document.querySelectorAll(
            'a[href^="mailto:"]'
        ).forEach(link => {

            link.addEventListener(
                'click',
                function() {

                    track('mailto');

                }
            );
        });
    })();
</script>
<script>
    (function() {

        let youtubePlayer = null;

        const videoMilestones = {
            25: false,
            50: false,
            75: false,
            100: false
        };

        // -----------------------------------------------------
        // CARGAR YOUTUBE IFRAME API
        // -----------------------------------------------------

        function loadYouTubeAPI() {

            if (window.YT && window.YT.Player) {
                initYouTubePlayer();
                return;
            }

            const tag = document.createElement('script');

            tag.src =
                'https://www.youtube.com/iframe_api';

            document.head.appendChild(tag);
        }

        // YouTube llama automáticamente a esta función
        window.onYouTubeIframeAPIReady = function() {
            initYouTubePlayer();
        };

        // -----------------------------------------------------
        // CREAR PLAYER
        // -----------------------------------------------------

        function initYouTubePlayer() {

            const iframe =
                document.getElementById('startup-video');

            if (!iframe) {
                return;
            }

            youtubePlayer = new YT.Player(
                iframe, {
                    events: {
                        onStateChange: onYouTubeStateChange
                    }
                }
            );
        }

        // -----------------------------------------------------
        // ESTADO DEL VIDEO
        // -----------------------------------------------------

        function onYouTubeStateChange(event) {

            switch (event.data) {

                case YT.PlayerState.PLAYING:

                    track('video_play');

                    checkVideoProgress();

                    break;

                case YT.PlayerState.ENDED:

                    if (!videoMilestones[100]) {

                        videoMilestones[100] = true;

                        track('video_100');
                        track('video_complete');
                    }

                    break;
            }
        }

        // -----------------------------------------------------
        // PROGRESO
        // -----------------------------------------------------

        function checkVideoProgress() {

            if (!youtubePlayer) {
                return;
            }

            const duration =
                youtubePlayer.getDuration();

            const currentTime =
                youtubePlayer.getCurrentTime();

            if (!duration || duration <= 0) {
                return;
            }

            const percentage =
                (currentTime / duration) * 100;

            [25, 50, 75].forEach(
                milestone => {

                    if (
                        percentage >= milestone &&
                        !videoMilestones[milestone]
                    ) {

                        videoMilestones[milestone] = true;

                        track(
                            `video_${milestone}`
                        );
                    }
                }
            );
        }

        // -----------------------------------------------------
        // COMPROBAR PROGRESO PERIÓDICAMENTE
        // -----------------------------------------------------

        setInterval(function() {

            if (!youtubePlayer) {
                return;
            }

            try {
                checkVideoProgress();
            } catch (e) {
                // Player todavía no está listo.
            }

        }, 1000);

        // -----------------------------------------------------
        // TRACK
        // -----------------------------------------------------

        function track(eventName) {

            const data = {
                event: eventName,
                page: window.location.pathname,

                device_type: getDeviceType(),
                browser: getBrowser(),
                os: getOS(),
                language: navigator.language || null,

                referrer_domain: getReferrerDomain(),

                utm_source: getQueryParam('utm_source'),

                utm_medium: getQueryParam('utm_medium'),

                utm_campaign: getQueryParam('utm_campaign'),

                utm_content: getQueryParam('utm_content'),

                utm_term: getQueryParam('utm_term')
            };

            const body =
                JSON.stringify(data);

            if (navigator.sendBeacon) {

                const blob = new Blob(
                    [body], {
                        type: 'application/json'
                    }
                );

                navigator.sendBeacon(
                    '/analytics/analytics.php',
                    blob
                );

            } else {

                fetch('/analytics/analytics.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: body,
                    keepalive: true
                }).catch(() => {});

            }
        }

        // -----------------------------------------------------
        // HELPERS
        // -----------------------------------------------------

        function getDeviceType() {

            const width =
                window.innerWidth;

            if (width < 768) {
                return 'mobile';
            }

            if (width < 1024) {
                return 'tablet';
            }

            return 'desktop';
        }

        function getBrowser() {

            const ua =
                navigator.userAgent;

            if (/Edg/i.test(ua)) {
                return 'Edge';
            }

            if (/Chrome/i.test(ua) &&
                !/Edg/i.test(ua)) {
                return 'Chrome';
            }

            if (/Firefox/i.test(ua)) {
                return 'Firefox';
            }

            if (/Safari/i.test(ua) &&
                !/Chrome/i.test(ua)) {
                return 'Safari';
            }

            return 'Other';
        }

        function getOS() {

            const ua =
                navigator.userAgent;

            if (/Windows/i.test(ua)) {
                return 'Windows';
            }

            if (/Mac OS/i.test(ua)) {
                return 'macOS';
            }

            if (/Android/i.test(ua)) {
                return 'Android';
            }

            if (/iPhone|iPad/i.test(ua)) {
                return 'iOS';
            }

            if (/Linux/i.test(ua)) {
                return 'Linux';
            }

            return 'Other';
        }

        function getReferrerDomain() {

            if (!document.referrer) {
                return null;
            }

            try {

                return new URL(
                    document.referrer
                ).hostname;

            } catch (e) {

                return null;
            }
        }

        function getQueryParam(name) {

            return new URLSearchParams(
                window.location.search
            ).get(name);
        }

        // -----------------------------------------------------
        // INICIAR
        // -----------------------------------------------------

        loadYouTubeAPI();

    })();
</script>
</body>

</html>