<?php
defined( 'ABSPATH' ) || exit;

wc_print_notices();
?>
<div class="cart-page">
    <div class="cart-page__breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
        <span class="cart-page__breadcrumb-sep">›</span>
        <span>Giỏ hàng</span>
    </div>

    <div class="cart-empty-notice">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--color-border)" stroke-width="1.5">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <p>Giỏ hàng của bạn đang trống.</p>
        <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="cart-empty-notice__btn">
            Tiếp tục mua sắm
        </a>
    </div>
</div>
