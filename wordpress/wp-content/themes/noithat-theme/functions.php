<?php
defined( 'ABSPATH' ) || exit;

define( 'NOITHAT_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'NOITHAT_DIR', get_template_directory() );
define( 'NOITHAT_URI', get_template_directory_uri() );

require NOITHAT_DIR . '/inc/class-noithat-setup.php';
require NOITHAT_DIR . '/inc/class-noithat-woocommerce.php';
require NOITHAT_DIR . '/inc/class-noithat-settings.php';
require NOITHAT_DIR . '/inc/class-noithat-cong-trinh.php';
require NOITHAT_DIR . '/inc/helpers.php';

new Noithat_Setup();
new Noithat_WooCommerce();
new Noithat_Settings();
new Noithat_Cong_Trinh();
