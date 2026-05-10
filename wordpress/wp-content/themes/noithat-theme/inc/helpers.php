<?php
defined( 'ABSPATH' ) || exit;

function noithat_price( $price ) {
	if ( $price === '' || $price === null || ! is_numeric( $price ) ) {
		return 'Liên hệ';
	}
	return number_format( (float) $price, 0, ',', '.' ) . '₫';
}

function noithat_get_hotline() {
	return get_option( 'noithat_hotline', '0969.196.xxx' );
}

function noithat_get_address() {
	return get_option( 'noithat_address', '196 Lý Thường Kiệt, TP. Thái Bình' );
}

function noithat_get_email() {
	return get_option( 'noithat_email', 'website@gmail.com' );
}
