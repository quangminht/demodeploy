<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top Bar -->
<div class="topbar">
    <div class="container">
        <div class="topbar__left">
            <span class="topbar__item">
                <i class="icon-location"></i>
                <?php echo esc_html( noithat_get_address() ); ?>
            </span>
            <span class="topbar__item">
                <i class="icon-email"></i>
                <?php echo esc_html( noithat_get_email() ); ?>
            </span>
        </div>
        <div class="topbar__right">
            <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>">Tài khoản</a>
                <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Đăng xuất</a>
            <?php else : ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Đăng nhập</a>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Đăng ký</a>
            <?php endif; ?>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                Giỏ hàng
                <span class="topbar__cart-count"><?php echo intval( WC()->cart->get_cart_contents_count() ); ?></span>
            </a>
        </div>
    </div>
</div>

<!-- Header Main -->
<header class="site-header">
    <div class="container">
        <div class="site-header__inner">

            <!-- Logo -->
            <div class="site-header__logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php
                    if ( has_custom_logo() ) {
                        the_custom_logo();
                    } else {
                        ?>
                        <span class="site-header__logo-text"><?php bloginfo( 'name' ); ?></span>
                        <?php
                    }
                    ?>
                </a>
            </div>

            <!-- Search -->
            <div class="site-header__search">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input
                        type="search"
                        class="site-header__search-input"
                        placeholder="<?php esc_attr_e( 'Tìm kiếm sản phẩm, danh mục...', 'noithat' ); ?>"
                        value="<?php echo esc_attr( get_search_query() ); ?>"
                        name="s"
                    >
                    <input type="hidden" name="post_type" value="product">
                    <button type="submit" class="site-header__search-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </form>
            </div>

            <!-- Hotline + Cart -->
            <div class="site-header__actions">
                <div class="site-header__hotline">
                    <span class="site-header__hotline-label">Hotline</span>
                    <a href="tel:<?php echo esc_attr( noithat_get_hotline() ); ?>" class="site-header__hotline-number">
                        <?php echo esc_html( noithat_get_hotline() ); ?>
                    </a>
                </div>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="site-header__cart">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    <span class="site-header__cart-count"><?php echo intval( WC()->cart->get_cart_contents_count() ); ?></span>
                </a>
            </div>

        </div>
    </div>
</header>

<!-- Navigation -->
<nav class="site-nav">
    <div class="container">
        <?php
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'menu_class'     => 'site-nav__menu',
            'container'      => false,
            'fallback_cb'    => false,
        ) );
        ?>
    </div>
</nav>
