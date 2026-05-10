<?php
defined( 'ABSPATH' ) || exit;

/**
 * Custom payment gateway — "Đặt hàng" (COD/offline).
 * Luôn available, không cần shipping zone, không cần cấu hình gì thêm.
 */
class Noithat_Order_Gateway extends WC_Payment_Gateway {

	public function __construct() {
		$this->id                 = 'noithat_order';
		$this->method_title       = 'Đặt hàng (COD)';
		$this->method_description = 'Đặt hàng và thanh toán khi nhận hàng hoặc chuyển khoản.';
		$this->has_fields         = false;
		$this->supports           = array( 'products' );

		$this->init_form_fields();
		$this->init_settings();

		$this->enabled = 'yes';
		$this->title   = 'Đặt hàng';

		add_action(
			'woocommerce_update_options_payment_gateways_' . $this->id,
			array( $this, 'process_admin_options' )
		);
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => 'Bật/Tắt',
				'type'    => 'checkbox',
				'label'   => 'Bật phương thức đặt hàng',
				'default' => 'yes',
			),
		);
	}

	public function is_available() {
		return true;
	}

	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		$order->update_status( 'processing', 'Đơn hàng đặt qua website.' );
		WC()->cart->empty_cart();

		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}
}

class Noithat_WooCommerce {

	public function __construct() {
		// Bỏ sidebar mặc định WooCommerce
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		// Số sản phẩm mỗi trang
		add_filter( 'loop_shop_per_page', function() { return 12; } );

		// Số cột sản phẩm
		add_filter( 'loop_shop_columns', function() { return 3; } );

		// Breadcrumb
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'breadcrumb_defaults' ) );

		// Đăng ký custom payment gateway
		add_filter( 'woocommerce_payment_gateways', array( $this, 'register_gateway' ) );

		// Không bắt buộc các trường checkout không cần thiết
		add_filter( 'woocommerce_checkout_fields', array( $this, 'simplify_checkout_fields' ) );
	}

	public function register_gateway( $gateways ) {
		$gateways[] = 'Noithat_Order_Gateway';
		return $gateways;
	}

	public function breadcrumb_defaults( $defaults ) {
		$defaults['delimiter']   = ' › ';
		$defaults['home']        = 'Trang chủ';
		$defaults['wrap_before'] = '<nav class="noithat-breadcrumb">';
		$defaults['wrap_after']  = '</nav>';
		return $defaults;
	}

	public function simplify_checkout_fields( $fields ) {
		$optional = array( 'billing_last_name', 'billing_city', 'billing_state', 'billing_postcode' );
		foreach ( $optional as $key ) {
			if ( isset( $fields['billing'][ $key ] ) ) {
				$fields['billing'][ $key ]['required'] = false;
			}
		}
		return $fields;
	}
}
