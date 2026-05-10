<?php
defined( 'ABSPATH' ) || exit;
/** @var WC_Order $order */
?>

<div class="order-thankyou">

<?php if ( $order ) :
    do_action( 'woocommerce_before_thankyou', $order->get_id() );

    if ( $order->has_status( 'failed' ) ) : ?>

        <div class="order-thankyou__failed">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="#e53935"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            <h2>Đặt hàng không thành công</h2>
            <p>Đơn hàng không thể được xử lý. Vui lòng thử lại.</p>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="thankyou-btn">Quay lại giỏ hàng</a>
        </div>

    <?php else : ?>

        <div class="order-thankyou__success">
            <div class="order-thankyou__icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="var(--color-primary)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14l-4-4 1.41-1.41L10 13.17l6.59-6.59L18 8l-8 8z"/></svg>
            </div>
            <h2 class="order-thankyou__heading">Cảm ơn bạn đã đặt hàng!</h2>
            <p class="order-thankyou__subtext">
                Chúng tôi đã nhận được đơn hàng của bạn và sẽ liên hệ xác nhận trong thời gian sớm nhất.
                <?php if ( $order->get_billing_email() ) : ?>
                    Email xác nhận đã được gửi đến <strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>.
                <?php endif; ?>
            </p>
        </div>

        <div class="order-thankyou__details">
            <h3 class="order-thankyou__details-title">Chi tiết đơn hàng</h3>
            <div class="order-thankyou__meta">
                <div class="order-thankyou__meta-item">
                    <span>Mã đơn hàng:</span>
                    <strong>#<?php echo esc_html( $order->get_order_number() ); ?></strong>
                </div>
                <div class="order-thankyou__meta-item">
                    <span>Ngày đặt:</span>
                    <strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
                </div>
                <div class="order-thankyou__meta-item">
                    <span>Khách hàng:</span>
                    <strong><?php echo esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></strong>
                </div>
                <div class="order-thankyou__meta-item">
                    <span>Điện thoại:</span>
                    <strong><?php echo esc_html( $order->get_billing_phone() ); ?></strong>
                </div>
                <div class="order-thankyou__meta-item">
                    <span>Địa chỉ:</span>
                    <strong><?php echo esc_html( $order->get_billing_address_1() ); ?></strong>
                </div>
                <div class="order-thankyou__meta-item order-thankyou__meta-item--total">
                    <span>Tổng đơn hàng:</span>
                    <strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
                </div>
            </div>

            <table class="order-thankyou__items">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $order->get_items() as $item_id => $item ) :
                    $_product = $item->get_product(); ?>
                    <tr>
                        <td>
                            <?php if ( $_product && $_product->is_visible() ) : ?>
                                <a href="<?php echo esc_url( $_product->get_permalink() ); ?>"><?php echo esc_html( $item->get_name() ); ?></a>
                            <?php else : ?>
                                <?php echo esc_html( $item->get_name() ); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html( $item->get_quantity() ); ?></td>
                        <td><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
        <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

        <div class="order-thankyou__actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="thankyou-btn thankyou-btn--outline">Về trang chủ</a>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="thankyou-btn">Tiếp tục mua sắm</a>
        </div>

    <?php endif; ?>

<?php else : ?>
    <div class="order-thankyou__success">
        <h2>Cảm ơn bạn đã đặt hàng!</h2>
        <p>Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="thankyou-btn">Về trang chủ</a>
    </div>
<?php endif; ?>

</div>
