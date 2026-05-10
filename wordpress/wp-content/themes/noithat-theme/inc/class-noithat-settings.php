<?php
defined( 'ABSPATH' ) || exit;

class Noithat_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_noithat-settings' !== $hook ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_script(
			'noithat-admin',
			get_template_directory_uri() . '/assets/js/admin.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);
	}

	public function add_menu() {
		add_menu_page(
			'Cài đặt Nội Thất',
			'Nội Thất',
			'manage_options',
			'noithat-settings',
			array( $this, 'render_page' ),
			'dashicons-store',
			59
		);
	}

	public function save_settings() {
		if (
			! isset( $_POST['noithat_settings_nonce'] ) ||
			! wp_verify_nonce( $_POST['noithat_settings_nonce'], 'noithat_save_settings' ) ||
			! current_user_can( 'manage_options' )
		) {
			return;
		}

		$fields = array(
			'noithat_hotline', 'noithat_address', 'noithat_email',
			'noithat_zalo', 'noithat_facebook', 'noithat_map_embed',
		);
		foreach ( $fields as $field ) {
			update_option( $field, sanitize_text_field( $_POST[ $field ] ?? '' ) );
		}

		$banners = array();
		$images = $_POST['banner_image'] ?? array();
		$links  = $_POST['banner_link'] ?? array();
		$titles = $_POST['banner_title'] ?? array();
		foreach ( $images as $i => $img ) {
			if ( ! empty( $img ) ) {
				$banners[] = array(
					'image' => esc_url_raw( $img ),
					'link'  => esc_url_raw( $links[ $i ] ?? '' ),
					'title' => sanitize_text_field( $titles[ $i ] ?? '' ),
				);
			}
		}
		update_option( 'noithat_banners', $banners );

		add_action( 'admin_notices', function() {
			echo '<div class="notice notice-success is-dismissible"><p>✅ Đã lưu cài đặt!</p></div>';
		} );
	}

	public function render_page() {
		$banners   = get_option( 'noithat_banners', array() );
		$hotline   = get_option( 'noithat_hotline', '' );
		$address   = get_option( 'noithat_address', '' );
		$email     = get_option( 'noithat_email', '' );
		$zalo      = get_option( 'noithat_zalo', '' );
		$facebook  = get_option( 'noithat_facebook', '' );
		$map_embed = get_option( 'noithat_map_embed', '' );

		while ( count( $banners ) < 5 ) {
			$banners[] = array( 'image' => '', 'link' => '', 'title' => '' );
		}
		?>
		<div class="wrap">
			<h1>⚙️ Cài đặt Nội Thất</h1>
			<form method="post" action="">
				<?php wp_nonce_field( 'noithat_save_settings', 'noithat_settings_nonce' ); ?>

				<!-- BANNER SLIDER -->
				<h2 class="title">🖼️ Banner trang chủ (Slider)</h2>
				<p>Tối đa 5 banner. Kích thước khuyến nghị: <strong>1200×400px</strong>.</p>

				<div id="noithat-banners-wrap" style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
					<?php foreach ( $banners as $i => $banner ) : ?>
					<div class="noithat-banner-item" style="border:1px solid #ddd;padding:12px;border-radius:4px;width:220px;background:#fafafa;">
						<p style="font-weight:600;margin-bottom:8px;">Banner <?php echo $i + 1; ?></p>

						<div class="banner-preview" style="height:80px;background:#eee;margin-bottom:8px;overflow:hidden;border-radius:2px;">
							<?php if ( ! empty( $banner['image'] ) ) : ?>
								<img src="<?php echo esc_url( $banner['image'] ); ?>" style="width:100%;height:100%;object-fit:cover;">
							<?php else : ?>
								<div style="height:100%;display:flex;align-items:center;justify-content:center;color:#999;font-size:12px;">Chưa có ảnh</div>
							<?php endif; ?>
						</div>

						<input type="hidden" name="banner_image[]" class="banner-url" value="<?php echo esc_attr( $banner['image'] ); ?>">

						<button type="button" class="button noithat-select-image" style="width:100%;margin-bottom:6px;">
							<?php echo ! empty( $banner['image'] ) ? '🔄 Đổi ảnh' : '📷 Chọn ảnh'; ?>
						</button>
						<?php if ( ! empty( $banner['image'] ) ) : ?>
						<button type="button" class="button noithat-remove-image" style="width:100%;margin-bottom:6px;color:#a00;">✕ Xoá ảnh</button>
						<?php endif; ?>

						<input type="text" name="banner_link[]" value="<?php echo esc_attr( $banner['link'] ); ?>"
							placeholder="Link khi click (tuỳ chọn)" style="width:100%;margin-top:4px;font-size:12px;">
						<input type="text" name="banner_title[]" value="<?php echo esc_attr( $banner['title'] ); ?>"
							placeholder="Tiêu đề/Alt text" style="width:100%;margin-top:4px;font-size:12px;">
					</div>
					<?php endforeach; ?>
				</div>

				<!-- THÔNG TIN LIÊN HỆ -->
				<h2 class="title">📞 Thông tin liên hệ</h2>
				<table class="form-table">
					<tr>
						<th>Hotline</th>
						<td><input type="text" name="noithat_hotline" value="<?php echo esc_attr( $hotline ); ?>" class="regular-text" placeholder="VD: 0969.196.xxx"></td>
					</tr>
					<tr>
						<th>Địa chỉ</th>
						<td><input type="text" name="noithat_address" value="<?php echo esc_attr( $address ); ?>" class="regular-text" placeholder="VD: 196 Lý Thường Kiệt, TP. Thái Bình"></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><input type="text" name="noithat_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" placeholder="VD: info@noithat.com"></td>
					</tr>
					<tr>
						<th>Zalo</th>
						<td><input type="text" name="noithat_zalo" value="<?php echo esc_attr( $zalo ); ?>" class="regular-text" placeholder="Số Zalo"></td>
					</tr>
					<tr>
						<th>Facebook Fanpage</th>
						<td><input type="text" name="noithat_facebook" value="<?php echo esc_attr( $facebook ); ?>" class="regular-text" placeholder="https://facebook.com/..."></td>
					</tr>
					<tr>
						<th>Google Maps Embed URL</th>
						<td>
							<input type="text" name="noithat_map_embed" value="<?php echo esc_attr( $map_embed ); ?>" class="large-text"
								placeholder="Lấy từ Google Maps → Chia sẻ → Nhúng bản đồ → copy src=&quot;...&quot;">
							<p class="description">Chỉ dán phần URL trong <code>src="..."</code>, không dán cả thẻ &lt;iframe&gt;.</p>
						</td>
					</tr>
				</table>

				<?php submit_button( '💾 Lưu cài đặt' ); ?>
			</form>
		</div>
		<?php
	}
}
