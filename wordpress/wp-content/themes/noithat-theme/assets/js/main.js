(function($) {
    'use strict';

    // Update cart count khi thêm sản phẩm
    $(document.body).on('wc_fragments_refreshed added_to_cart', function() {
        var count = $('.woocommerce-cart-form').length ? WC_CART.cart_count : 0;
        $('.site-header__cart-count, .topbar__cart-count').text(
            wc_cart_fragments_params ? wc_cart_fragments_params.cart_count : 0
        );
    });

    // ===== CART ORDER FORM (AJAX checkout) =====
    if ($('#noithat-order-form').length) {
        var checkoutUrl = (typeof noithat_cart !== 'undefined') ? noithat_cart.checkout_url : '/?wc-ajax=checkout';

        $('#noithat-order-form').on('submit', function(e) {
            e.preventDefault();

            var $form    = $(this);
            var $btn     = $('#noithat-place-order');
            var $notices = $('#cart-order-notices');

            var valid = true;
            $form.find('[required]').each(function() {
                if (!$(this).val().trim()) {
                    $(this).addClass('is-error');
                    valid = false;
                } else {
                    $(this).removeClass('is-error');
                }
            });

            if (!valid) {
                $notices.html('<div class="cart-notice cart-notice--error">Vui lòng điền đầy đủ thông tin bắt buộc.</div>').show();
                $('html, body').animate({ scrollTop: $notices.offset().top - 100 }, 400);
                return;
            }

            $btn.find('.btn-text').hide();
            $btn.find('.btn-loading').show();
            $btn.prop('disabled', true);
            $notices.hide();

            // Split full name: last word → first_name, rest → last_name
            var fullName  = $('#order_billing_name').val().trim();
            var nameParts = fullName.split(/\s+/);
            var firstName = nameParts.pop();
            var lastName  = nameParts.join(' ') || firstName;
            $form.find('input[name="billing_first_name"]').val(firstName);
            $form.find('input[name="billing_last_name"]').val(lastName);

            var data = $form.serialize();
            data += '&billing_city=' + encodeURIComponent($('#order_billing_address').val());

            $.ajax({
                url: checkoutUrl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.result === 'success') {
                        window.location.href = response.redirect;
                    } else {
                        var msg = response.messages || response.message || '<p>Có lỗi xảy ra, vui lòng thử lại.</p>';
                        $notices.html('<div class="cart-notice cart-notice--error">' + msg + '</div>').show();
                        $('html, body').animate({ scrollTop: $notices.offset().top - 100 }, 400);
                        $btn.find('.btn-text').show();
                        $btn.find('.btn-loading').hide();
                        $btn.prop('disabled', false);
                    }
                },
                error: function() {
                    $notices.html('<div class="cart-notice cart-notice--error"><p>Không thể kết nối. Vui lòng thử lại.</p></div>').show();
                    $btn.find('.btn-text').show();
                    $btn.find('.btn-loading').hide();
                    $btn.prop('disabled', false);
                }
            });
        });

        $('#noithat-order-form').on('input change', '[required]', function() {
            if ($(this).val().trim()) $(this).removeClass('is-error');
        });
    }

    // Banner slider dùng CSS opacity transition
    var $slides = $('.home-banner__slider .home-banner__item');
    if ($slides.length > 0) {
        var current = 0;
        $slides.first().addClass('is-active');

        if ($slides.length > 1) {
            setInterval(function() {
                $slides.eq(current).removeClass('is-active');
                current = (current + 1) % $slides.length;
                $slides.eq(current).addClass('is-active');
            }, 4000);
        }
    }

    // ===== CÔNG TRÌNH GALLERY =====
    if (typeof ctGallery !== 'undefined' && ctGallery.images.length) {
        var images    = ctGallery.images;
        var ctCurrent = 0;

        function ctOpenLightbox(index) {
            ctCurrent = (index + images.length) % images.length;
            $('#ct-lightbox-img').attr('src', images[ctCurrent].full);
            $('#ct-lightbox').fadeIn(180);
            $('body').css('overflow', 'hidden');
        }
        function ctCloseLightbox() {
            $('#ct-lightbox').fadeOut(180);
            $('body').css('overflow', '');
        }

        // Click photo grid item
        $(document).on('click', '.ct-photo-grid__item', function() {
            ctOpenLightbox(parseInt($(this).data('index')));
        });

        // Close
        $('#ct-lightbox-close, #ct-lightbox-close-btn').on('click', ctCloseLightbox);

        // Lightbox prev/next
        $('#ct-lb-prev').on('click', function(e) {
            e.stopPropagation();
            ctCurrent = (ctCurrent - 1 + images.length) % images.length;
            $('#ct-lightbox-img').attr('src', images[ctCurrent].full);
        });
        $('#ct-lb-next').on('click', function(e) {
            e.stopPropagation();
            ctCurrent = (ctCurrent + 1) % images.length;
            $('#ct-lightbox-img').attr('src', images[ctCurrent].full);
        });

        // Keyboard nav
        $(document).on('keydown', function(e) {
            if (!$('#ct-lightbox').is(':visible')) return;
            if (e.key === 'ArrowLeft')  { ctCurrent = (ctCurrent - 1 + images.length) % images.length; $('#ct-lightbox-img').attr('src', images[ctCurrent].full); }
            if (e.key === 'ArrowRight') { ctCurrent = (ctCurrent + 1) % images.length; $('#ct-lightbox-img').attr('src', images[ctCurrent].full); }
            if (e.key === 'Escape')     ctCloseLightbox();
        });
    }

})(jQuery);
