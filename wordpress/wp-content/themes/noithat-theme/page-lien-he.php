<?php
/**
 * Template Name: Trang Liên Hệ
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<main class="site-main">
    <div class="container">

        <h1 class="page-title">Liên Hệ</h1>

        <div class="contact-layout">

            <!-- Cột trái: Bản đồ + thông tin -->
            <div class="contact-info">

                <!-- Google Maps -->
                <div class="contact-map">
                    <?php
                    $map_url = get_option( 'noithat_map_embed', '' );
                    if ( $map_url ) :
                    ?>
                        <iframe
                            src="<?php echo esc_url( $map_url ); ?>"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    <?php else : ?>
                        <div class="contact-map__placeholder">
                            <p>Chưa cấu hình Google Maps.<br>Vào <strong>Cài đặt → Nội thất</strong> để thêm link embed.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Thông tin liên hệ -->
                <div class="contact-details">
                    <h2 class="contact-details__title"><?php bloginfo( 'name' ); ?></h2>
                    <ul class="contact-details__list">
                        <li>
                            <span class="contact-details__icon">📍</span>
                            <strong>Địa chỉ:</strong> <?php echo esc_html( noithat_get_address() ); ?>
                        </li>
                        <li>
                            <span class="contact-details__icon">📞</span>
                            <strong>SĐT:</strong>
                            <a href="tel:<?php echo esc_attr( noithat_get_hotline() ); ?>">
                                <?php echo esc_html( noithat_get_hotline() ); ?>
                            </a>
                        </li>
                        <li>
                            <span class="contact-details__icon">✉️</span>
                            <strong>Email:</strong>
                            <a href="mailto:<?php echo esc_attr( noithat_get_email() ); ?>">
                                <?php echo esc_html( noithat_get_email() ); ?>
                            </a>
                        </li>
                        <?php $zalo = get_option( 'noithat_zalo' ); if ( $zalo ) : ?>
                        <li>
                            <span class="contact-details__icon">💬</span>
                            <strong>Zalo:</strong> <?php echo esc_html( $zalo ); ?>
                        </li>
                        <?php endif; ?>
                        <?php $fb = get_option( 'noithat_facebook' ); if ( $fb ) : ?>
                        <li>
                            <span class="contact-details__icon">🔗</span>
                            <strong>Fanpage:</strong>
                            <a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener">
                                <?php echo esc_html( $fb ); ?>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>

            <!-- Cột phải: Form liên hệ -->
            <div class="contact-form-wrap">
                <h2 class="contact-form-wrap__title">Để lại tin nhắn cho chúng tôi</h2>
                <p class="contact-form-wrap__desc">Vui lòng hoàn thành biểu mẫu sau và gửi đến chúng tôi</p>

                <?php
                // Xử lý form
                $success = false;
                $errors  = array();

                if ( isset( $_POST['noithat_contact_submit'] ) ) {
                    if ( ! wp_verify_nonce( $_POST['noithat_contact_nonce'] ?? '', 'noithat_contact' ) ) {
                        $errors[] = 'Yêu cầu không hợp lệ.';
                    } else {
                        $name    = sanitize_text_field( $_POST['contact_name'] ?? '' );
                        $email   = sanitize_email( $_POST['contact_email'] ?? '' );
                        $message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

                        if ( empty( $name ) ) $errors[] = 'Vui lòng nhập họ tên.';
                        if ( empty( $email ) || ! is_email( $email ) ) $errors[] = 'Email không hợp lệ.';
                        if ( empty( $message ) ) $errors[] = 'Vui lòng nhập nội dung.';

                        if ( empty( $errors ) ) {
                            $to      = get_option( 'admin_email' );
                            $subject = '[' . get_bloginfo( 'name' ) . '] Liên hệ từ ' . $name;
                            $body    = "Họ tên: {$name}\nEmail: {$email}\n\nNội dung:\n{$message}";
                            $headers = array( 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$email}" );

                            wp_mail( $to, $subject, $body, $headers );
                            $success = true;
                        }
                    }
                }
                ?>

                <?php if ( $success ) : ?>
                    <div class="contact-success">
                        ✅ Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.
                    </div>
                <?php else : ?>

                    <?php if ( ! empty( $errors ) ) : ?>
                        <div class="contact-errors">
                            <?php foreach ( $errors as $error ) : ?>
                                <p>⚠️ <?php echo esc_html( $error ); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form class="contact-form" method="post">
                        <?php wp_nonce_field( 'noithat_contact', 'noithat_contact_nonce' ); ?>

                        <div class="contact-form__field">
                            <input
                                type="text"
                                name="contact_name"
                                placeholder="Họ tên *"
                                required
                                value="<?php echo esc_attr( $_POST['contact_name'] ?? '' ); ?>"
                            >
                        </div>

                        <div class="contact-form__field">
                            <input
                                type="email"
                                name="contact_email"
                                placeholder="Địa chỉ email *"
                                required
                                value="<?php echo esc_attr( $_POST['contact_email'] ?? '' ); ?>"
                            >
                        </div>

                        <div class="contact-form__field">
                            <textarea
                                name="contact_message"
                                placeholder="Nội dung liên hệ..."
                                rows="6"
                                required
                            ><?php echo esc_textarea( $_POST['contact_message'] ?? '' ); ?></textarea>
                        </div>

                        <button type="submit" name="noithat_contact_submit" class="contact-form__submit">
                            GỬI THÔNG TIN
                        </button>
                    </form>

                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

<?php get_footer(); ?>
