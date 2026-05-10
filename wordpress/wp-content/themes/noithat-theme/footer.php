<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">

            <!-- Cột 1: Thông tin cửa hàng -->
            <div class="site-footer__col">
                <h4 class="site-footer__title"><?php bloginfo( 'name' ); ?></h4>
                <ul class="site-footer__info">
                    <li><strong>Địa chỉ:</strong> <?php echo esc_html( noithat_get_address() ); ?></li>
                    <li><strong>SĐT:</strong> <?php echo esc_html( noithat_get_hotline() ); ?></li>
                    <li><strong>Email:</strong> <?php echo esc_html( noithat_get_email() ); ?></li>
                </ul>
                <div class="site-footer__social">
                    <?php $fb = get_option( 'noithat_facebook' ); ?>
                    <?php if ( $fb ) : ?>
                        <a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener" class="site-footer__social-link">Facebook</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Cột 2: Giới thiệu -->
            <div class="site-footer__col">
                <h4 class="site-footer__title">Giới thiệu</h4>
                <ul class="site-footer__links">
                    <li><a href="<?php echo esc_url( home_url( '/gioi-thieu' ) ); ?>">Về chúng tôi</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/lien-he' ) ); ?>">Liên hệ</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/tuyen-dung' ) ); ?>">Tuyển dụng</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/hang-chinh-hang' ) ); ?>">Hàng chính hãng</a></li>
                </ul>
            </div>

            <!-- Cột 3: Chính sách -->
            <div class="site-footer__col">
                <h4 class="site-footer__title">Chính sách</h4>
                <ul class="site-footer__links">
                    <li><a href="<?php echo esc_url( home_url( '/chinh-sach-ban-hang' ) ); ?>">Chính sách bán hàng</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/chinh-sach-bao-hanh' ) ); ?>">Chính sách bảo hành</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/chinh-sach-doi-tra' ) ); ?>">Chính sách đổi trả</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/chinh-sach-giao-hang' ) ); ?>">Chính sách giao hàng</a></li>
                </ul>
            </div>

            <!-- Cột 4: Kết nối -->
            <div class="site-footer__col">
                <h4 class="site-footer__title">Kết nối với chúng tôi</h4>
                <div class="site-footer__fanpage">
                    <?php $fb = get_option( 'noithat_facebook' ); ?>
                    <?php if ( $fb ) : ?>
                        <a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener">Trang Facebook</a>
                    <?php endif; ?>
                </div>
                <h4 class="site-footer__title" style="margin-top:16px">Đăng ký nhận tin</h4>
                <form class="site-footer__subscribe" method="post">
                    <?php wp_nonce_field( 'noithat_subscribe', 'noithat_nonce' ); ?>
                    <input type="email" name="subscribe_email" placeholder="Địa chỉ email (*)" required>
                    <button type="submit">Đăng ký</button>
                </form>
            </div>

        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="site-footer__bottom">
        <div class="container">
            <p><?php echo esc_html( date( 'Y' ) ); ?> &copy; <?php bloginfo( 'name' ); ?>. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
