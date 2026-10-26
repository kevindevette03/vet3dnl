/**
 * Scroll-gestuurde video: de video blijft vast in beeld en het afspeelpunt
 * volgt de scrollpositie binnen de sectie.
 */
(function () {
    const section = document.querySelector('.vet-scrollvideo');
    if (!section) return;

    const video = section.querySelector('video');
    const src = video.dataset.src;

    // Bij 'minder beweging' gewoon een normale video met bediening tonen
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        section.classList.add('is-static');
        video.controls = true;
        video.preload = 'metadata';
        video.src = src;
        return;
    }

    let target = 0;
    let current = 0;
    let running = false;

    // Hele video in het geheugen laden, zodat zoeken direct werkt (geen range-requests)
    fetch(src)
        .then((r) => r.blob())
        .then((blob) => { video.src = URL.createObjectURL(blob); })
        .catch(() => { video.src = src; });

    update();

    // iOS Safari toont pas frames na een eerste play()
    video.addEventListener('loadedmetadata', () => {
        const p = video.play();
        if (p) p.then(() => video.pause()).catch(() => {});
        update();
    }, { once: true });

    function progress() {
        const rect = section.getBoundingClientRect();
        const total = section.offsetHeight - window.innerHeight;
        if (total <= 0) return 0;
        return Math.min(1, Math.max(0, -rect.top / total));
    }

    function update() {
        const p = progress();
        // Hero-tekst vervaagt in het eerste kwart van de scroll (zie style.css)
        section.style.setProperty('--p', p.toFixed(3));
        if (!video.duration) return;
        target = p * (video.duration - 0.05);
    }

    function tick() {
        if (!running) return;
        current += (target - current) * 0.2;
        if (video.duration && !video.seeking && Math.abs(video.currentTime - current) > 0.01) {
            video.currentTime = current;
        }
        requestAnimationFrame(tick);
    }

    // Alleen rekenen zolang de sectie (bijna) in beeld is
    new IntersectionObserver((entries) => {
        running = entries[0].isIntersecting;
        if (running) {
            update();
            requestAnimationFrame(tick);
        }
    }, { rootMargin: '100px 0px' }).observe(section);

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
})();
