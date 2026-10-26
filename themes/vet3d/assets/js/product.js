jQuery(document).ready(function($) {
    
    // === Thumbnail Slider (max 3 zichtbaar) ===
    const $thumbs = $('.flex-control-thumbs');
    const $thumbItems = $thumbs.find('li');
    const thumbCount = $thumbItems.length;
    
    if (thumbCount > 3) {
        let currentIndex = 0;
        const maxIndex = thumbCount - 3;
        const thumbWidth = 80;
        const thumbGap = 10;
        const scrollDistance = thumbWidth + thumbGap;
        
        // Wrap thumbs container
        $thumbs.wrap('<div class="gallery-with-nav" style="position:relative;display:inline-block;"></div>');
        const $wrapper = $thumbs.parent();
        
        // Voeg navigatie pijltjes toe
        $wrapper.prepend('<button class="vet3d-thumb-nav prev" disabled><svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg></button>');
        $wrapper.append('<button class="vet3d-thumb-nav next"><svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg></button>');
        
        const $prev = $wrapper.find('.vet3d-thumb-nav.prev');
        const $next = $wrapper.find('.vet3d-thumb-nav.next');
        
        // Style thumbs container
        $thumbs.css({
            'width': '270px',
            'overflow': 'hidden',
            'position': 'relative'
        });
        
        // Create inner wrapper for sliding
        const $innerWrapper = $('<div class="thumb-slider-inner"></div>').css({
            'display': 'flex',
            'gap': thumbGap + 'px',
            'transition': 'transform 0.3s ease',
            'position': 'relative'
        });
        
        $thumbItems.appendTo($innerWrapper);
        $thumbs.append($innerWrapper);
        
        function updateSlider() {
            const translateX = -(currentIndex * scrollDistance);
            $innerWrapper.css('transform', 'translateX(' + translateX + 'px)');
            
            $prev.prop('disabled', currentIndex === 0);
            $next.prop('disabled', currentIndex >= maxIndex);
        }
        
        $prev.on('click', function(e) {
            e.preventDefault();
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        });
        
        $next.on('click', function(e) {
            e.preventDefault();
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateSlider();
            }
        });
    }
    
    // === Sticky Cart (verschijnt bij scrollen) ===
    const $stickyCart = $('.vet3d-sticky-cart');
    const $addToCartForm = $('form.cart');
    const formOffset = $addToCartForm.length ? $addToCartForm.offset().top : 0;
    
    $(window).on('scroll', function() {
        const scrollTop = $(window).scrollTop();
        const windowHeight = $(window).height();
        
        // Toon sticky cart als gebruiker voorbij het add-to-cart formulier scrollt
        if (scrollTop > (formOffset + 200)) {
            $stickyCart.addClass('visible');
        } else {
            $stickyCart.removeClass('visible');
        }
    });
    
    // Quantity buttons sticky cart
    $('.vet3d-sticky-cart .minus').on('click', function() {
        const $input = $(this).siblings('.qty');
        const currentVal = parseInt($input.val()) || 1;
        if (currentVal > 1) {
            $input.val(currentVal - 1);
        }
    });
    
    $('.vet3d-sticky-cart .plus').on('click', function() {
        const $input = $(this).siblings('.qty');
        const currentVal = parseInt($input.val()) || 1;
        const max = parseInt($input.attr('max')) || 999;
        if (currentVal < max) {
            $input.val(currentVal + 1);
        }
    });
    
    // Add to cart vanuit sticky cart
    $('.vet3d-sticky-cart .add-btn').on('click', function(e) {
        e.preventDefault();
        
        // Update de quantity in het hoofdformulier
        const stickyQty = $('.vet3d-sticky-cart .qty').val();
        $('form.cart input[name="quantity"]').val(stickyQty);
        
        // Scroll naar het formulier en trigger de add to cart
        $('html, body').animate({
            scrollTop: $addToCartForm.offset().top - 100
        }, 400, function() {
            $addToCartForm.find('.single_add_to_cart_button').click();
        });
    });
    
    // Sync quantity tussen sticky cart en main form
    $('form.cart').on('change', 'input[name="quantity"]', function() {
        $('.vet3d-sticky-cart .qty').val($(this).val());
    });
});
