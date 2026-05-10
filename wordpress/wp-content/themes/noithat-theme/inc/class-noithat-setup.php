<?php
defined( 'ABSPATH' ) || exit;

class Noithat_Setup {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	public function setup() {
		load_theme_textdomain( 'noithat', NOITHAT_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption' ) );

		register_nav_menus( array(
			'primary'  => __( 'Menu chính', 'noithat' ),
			'topbar'   => __( 'Menu top bar', 'noithat' ),
			'footer'   => __( 'Menu footer', 'noithat' ),
		) );

		set_post_thumbnail_size( 400, 300, true );
		add_image_size( 'noithat-product', 300, 300, true );
		add_image_size( 'noithat-banner', 1200, 500, true );
	}

	public function enqueue_assets() {
		// Google Fonts
		wp_enqueue_style(
			'noithat-fonts',
			'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
			array(),
			null
		);

		// Main CSS
		wp_enqueue_style(
			'noithat-main',
			NOITHAT_URI . '/assets/css/main.css',
			array(),
			NOITHAT_VERSION
		);

		// jQuery (WordPress có sẵn)
		wp_enqueue_script( 'jquery' );

		// Main JS
		wp_enqueue_script(
			'noithat-main',
			NOITHAT_URI . '/assets/js/main.js',
			array( 'jquery' ),
			NOITHAT_VERSION,
			true
		);

		wp_localize_script( 'noithat-main', 'noithatData', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'noithat_nonce' ),
			'cartUrl' => wc_get_cart_url(),
		) );

		if ( is_cart() ) {
			wp_localize_script( 'noithat-main', 'noithat_cart', array(
				'checkout_url' => add_query_arg( 'wc-ajax', 'checkout', home_url( '/' ) ),
			) );
		}
	}

	public function register_sidebars() {
		register_sidebar( array(
			'name'          => __( 'Sidebar danh mục', 'noithat' ),
			'id'            => 'sidebar-shop',
			'before_widget' => '<div class="noithat-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="noithat-widget__title">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => __( 'Footer cột 1', 'noithat' ),
			'id'            => 'footer-1',
			'before_widget' => '<div class="footer-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget__title">',
			'after_title'   => '</h4>',
		) );
	}
}
