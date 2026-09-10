<!-- Mobile menu toggler (hamburger icon) -->
<button class="navbar-toggler d-sm-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas" aria-label="Toggle navigation" style="border: none; padding: 0.25rem 0.5rem;">
    <svg style="vertical-align: 14px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
    </svg>
</button>

<!-- Mobile offcanvas menu (slides from right) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel" data-bs-scroll="false">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="navbarOffcanvasLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column nav-pills" id="mobileNavMenu">
            <li class="nav-item"><a href="#inicio" class="nav-link" aria-current="page">Inicio</a></li>
            <li class="nav-item"><a href="#producto" class="nav-link">Producto</a></li>
            <li class="nav-item"><a href="#equipo" class="nav-link">Equipo</a></li>
            <li class="nav-item"><a href="#contacto" class="nav-link active">Contacto</a></li>
        </ul>
    </div>
</div>

<!-- Desktop/tablet navbar (centered) -->
<div class="d-none d-sm-block">
    <ul class="nav align-items-center nav-pills w-100 justify-content-center">
        <li class="nav-item"><a href="#inicio" class="nav-link" aria-current="page">Inicio</a></li>
        <li class="nav-item"><a href="#producto" class="nav-link">Producto</a></li>
        <li class="nav-item"><a href="#equipo" class="nav-link">Equipo</a></li>
        <li class="nav-item"><a href="#contacto" class="nav-link active">Contacto</a></li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const offcanvasElement = document.getElementById('navbarOffcanvas');
        const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
        const menuLinks = offcanvasElement.querySelectorAll('.nav-link');
        let scrollPos = 0;

        // Save scroll position when menu opens
        offcanvasElement.addEventListener('show.bs.offcanvas', function() {
            scrollPos = window.scrollY || document.documentElement.scrollTop;
        });

        // Restore scroll position when menu closes
        offcanvasElement.addEventListener('hide.bs.offcanvas', function() {
            // Immediately restore scroll on both window and document
            window.scrollTo(0, scrollPos);
            document.documentElement.scrollTop = scrollPos;
            document.body.scrollTop = scrollPos;
        });

        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const href = this.getAttribute('href');
                
                // Close the menu first
                bsOffcanvas.hide();
                
                // After menu closes, navigate to the anchor
                if (href && href.startsWith('#')) {
                    setTimeout(() => {
                        const target = document.querySelector(href);
                        if (target) {
                            target.scrollIntoView({ behavior: 'smooth' });
                        }
                    }, 300);
                }
            });
        });
    });
</script>