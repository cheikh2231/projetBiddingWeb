document.addEventListener('DOMContentLoaded', () => {
 
    const card      = document.querySelector('[data-item-id]');
    const imageArea = document.getElementById('item-image-area');
    const thumbsWrap = document.getElementById('thumbnails');
 
    if (!card || !imageArea) return;
 
    fetch(`item/images_async/${card.dataset.itemId}`)
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(({ images }) => {
            if (!images || images.length <= 1) {
                // 0 ou 1 image : juste mettre à jour le src 
                if (images.length === 1)
                    imageArea.querySelector('img').src = images[0];
                return;
            }
 
            // Carousel
            imageArea.className = '';
            imageArea.innerHTML = `
                <div id="itemCarousel" class="carousel slide">
                    <div class="carousel-indicators">
                        ${images.map((_, i) => `
                            <button type="button" data-bs-target="#itemCarousel"
                                    data-bs-slide-to="${i}" ${i === 0 ? 'class="active" aria-current="true"' : ''}
                                    aria-label="Slide ${i+1}"></button>`).join('')}
                    </div>
                    <div class="carousel-inner">
                        ${images.map((url, i) => `
                            <div class="carousel-item ${i === 0 ? 'active' : ''}">
                                <img src="${url}" class="d-block w-100"
                                     style="object-fit:contain; max-height:340px; background:#000;"
                                     alt="Image ${i+1}">
                            </div>`).join('')}
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#itemCarousel" data-bs-slide="prev"
                            style="width:40px; background:rgba(255,255,255,0.2); border-radius:4px;">
                        <span class="carousel-control-prev-icon" style="filter:drop-shadow(0 0 2px #000);"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#itemCarousel" data-bs-slide="next"
                            style="width:40px; background:rgba(255,255,255,0.2); border-radius:4px;">
                        <span class="carousel-control-next-icon" style="filter:drop-shadow(0 0 2px #000);"></span>
                    </button>
                </div>`;
 
            const carouselEl = document.getElementById('itemCarousel');
            const carousel   = bootstrap.Carousel.getOrCreateInstance(carouselEl, { wrap: true });
 
            // Vignettes
            if (!thumbsWrap) return;
 
            thumbsWrap.innerHTML = images.map((url, i) => `
                <button type="button" class="thumb-btn border-0 bg-transparent p-0" data-slide="${i}">
                    <img src="${url}" width="80" height="60"
                         class="img-thumbnail ${i === 0 ? 'border border-primary border-2' : ''}"
                         style="object-fit:cover;" alt="Vignette ${i+1}">
                </button>`).join('');
 
            thumbsWrap.addEventListener('click', e => {
                const btn = e.target.closest('.thumb-btn');
                if (btn) carousel.to(parseInt(btn.dataset.slide, 10));
            });
 
            carouselEl.addEventListener('slid.bs.carousel', e => {
                thumbsWrap.querySelectorAll('.thumb-btn img').forEach((img, i) => {
                    img.classList.toggle('border',         i === e.to);
                    img.classList.toggle('border-primary', i === e.to);
                    img.classList.toggle('border-2',       i === e.to);
                });
            });
        })
        .catch(err => console.error('Erreur images:', err));
});
 