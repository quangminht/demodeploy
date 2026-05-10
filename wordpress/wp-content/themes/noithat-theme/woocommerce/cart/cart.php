<?php
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<div class="cart-page">

    <div class="cart-page__breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
        <span class="cart-page__breadcrumb-sep">›</span>
        <span>Giỏ hàng</span>
    </div>

    <?php if ( WC()->cart->is_empty() ) : ?>
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
    <?php else : ?>

        <?php do_action( 'woocommerce_before_cart_table' ); ?>

        <div class="cart-table-wrap">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th class="cart-col-img">Hình ảnh</th>
                        <th class="cart-col-name">Sản phẩm</th>
                        <th class="cart-col-price">Đơn giá</th>
                        <th class="cart-col-qty">Số lượng</th>
                        <th class="cart-col-total">Thành tiền</th>
                        <th class="cart-col-remove"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) continue;
                        if ( ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) continue;

                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                        ?>
                        <tr class="cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                            <td class="cart-col-img">
                                <?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
                                if ( $product_permalink ) {
                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore
                                } else {
                                    echo $thumbnail; // phpcs:ignore
                                } ?>
                            </td>

                            <td class="cart-col-name">
                                <?php if ( $product_permalink ) {
                                    echo '<a href="' . esc_url( $product_permalink ) . '">' . esc_html( $_product->get_name() ) . '</a>';
                                } else {
                                    echo esc_html( $product_name );
                                }
                                do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
                                echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore
                                ?>
                            </td>

                            <td class="cart-col-price">
                                <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore ?>
                            </td>

                            <td class="cart-col-qty">
                                <?php
                                $min_q = $_product->is_sold_individually() ? 1 : 0;
                                $max_q = $_product->is_sold_individually() ? 1 : $_product->get_max_purchase_quantity();
                                echo apply_filters( 'woocommerce_cart_item_quantity', // phpcs:ignore
                                    woocommerce_quantity_input( array(
                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                        'input_value'  => $cart_item['quantity'],
                                        'max_value'    => $max_q,
                                        'min_value'    => $min_q,
                                        'product_name' => $product_name,
                                    ), $_product, false ),
                                    $cart_item_key, $cart_item
                                );
                                ?>
                            </td>

                            <td class="cart-col-total">
                                <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore ?>
                            </td>

                            <td class="cart-col-remove">
                                <?php echo apply_filters( 'woocommerce_cart_item_remove_link', // phpcs:ignore
                                    sprintf(
                                        '<a role="button" href="%s" class="cart-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() )
                                    ),
                                    $cart_item_key
                                ); ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                    <?php do_action( 'woocommerce_cart_contents' ); ?>
                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                </tbody>
            </table>
        </div>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>

        <!-- Cart totals summary -->
        <div class="cart-summary-row">
            <div class="cart-summary">
                <div class="cart-summary__line">
                    <span>Tạm tính:</span>
                    <strong><?php echo WC()->cart->get_cart_subtotal(); // phpcs:ignore ?></strong>
                </div>
                <?php if ( wc_tax_enabled() ) : ?>
                <div class="cart-summary__line">
                    <span>Thuế (VAT):</span>
                    <strong><?php wc_cart_totals_taxes_total_html(); ?></strong>
                </div>
                <?php endif; ?>
                <div class="cart-summary__line cart-summary__line--total">
                    <span>Tổng cộng:</span>
                    <strong><?php wc_cart_totals_order_total_html(); ?></strong>
                </div>
            </div>
        </div>

        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
        <?php // Suppress default collaterals (cross-sells + totals box) — we handle totals above. ?>
        <div style="display:none"><?php do_action( 'woocommerce_cart_collaterals' ); ?></div>

        <!-- ===== CUSTOMER INFO FORM ===== -->
        <div class="cart-customer-form" id="cart-customer-form">
            <h3 class="cart-customer-form__title">Thông Tin Khách Hàng</h3>

            <div class="cart-customer-form__notices" id="cart-order-notices" style="display:none;"></div>

            <form id="noithat-order-form" class="cart-order-form" novalidate>
                <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
                <input type="hidden" name="payment_method" value="noithat_order">
                <input type="hidden" name="billing_country" value="VN">
                <input type="hidden" name="billing_postcode" value="">
                <input type="hidden" name="billing_state" value="">
                <input type="hidden" name="billing_company" value="">
                <input type="hidden" name="billing_address_2" value="">
                <input type="hidden" name="billing_last_name" value="">
                <input type="hidden" name="ship_to_different_address" value="0">
                <input type="hidden" name="woocommerce-process-checkout" value="1">

                <div class="cart-order-form__row">
                    <div class="cart-order-form__field">
                        <label for="order_billing_name">Họ và tên <span class="required">*</span></label>
                        <input type="text" id="order_billing_name" name="billing_first_name" placeholder="Nhập họ và tên đầy đủ" required>
                    </div>
                    <div class="cart-order-form__field">
                        <label for="order_billing_email">Email <span class="required">*</span></label>
                        <input type="email" id="order_billing_email" name="billing_email" placeholder="Nhập địa chỉ email" required>
                    </div>
                </div>

                <div class="cart-order-form__row">
                    <div class="cart-order-form__field">
                        <label for="order_billing_phone">Điện thoại <span class="required">*</span></label>
                        <input type="tel" id="order_billing_phone" name="billing_phone" placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="cart-order-form__field">
                        <label for="order_billing_address">Địa chỉ <span class="required">*</span></label>
                        <input type="text" id="order_billing_address" name="billing_address_1" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố" required>
                    </div>
                </div>

                <div class="cart-order-form__field cart-order-form__field--full">
                    <label for="order_comments">Yêu cầu / Ghi chú</label>
                    <textarea id="order_comments" name="order_comments" rows="3" placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay địa điểm giao hàng cụ thể..."></textarea>
                </div>

                <p class="cart-order-form__note">
                    Xin nhập vào Đặt hàng, vui lòng cho số điện thoại để chúng tôi liên hệ xác nhận đơn hàng.
                </p>

                <div class="cart-order-form__actions">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="cart-order-form__btn-back">
                        ← Tiếp tục mua sắm
                    </a>
                    <button type="submit" class="cart-order-form__btn-order" id="noithat-place-order">
                        <span class="btn-text">Đặt hàng ngay</span>
                        <span class="btn-loading" style="display:none;">Đang xử lý...</span>
                    </button>
                </div>
            </form>
        </div>
        <!-- /.cart-customer-form -->

    <?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
