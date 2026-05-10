<?php
defined( 'ABSPATH' ) || exit;

class Noithat_Cong_Trinh {

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'init', array( $this, 'maybe_flush_rewrite' ), 99 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_cong_trinh', array( $this, 'save_meta' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	public function maybe_flush_rewrite() {
		if ( ! get_option( 'noithat_cpt_flushed' ) ) {
			flush_rewrite_rules();
			update_option( 'noithat_cpt_flushed', '1' );
		}
	}

	public function register_post_type() {
		register_post_type( 'cong_trinh', array(
			'labels' => array(
				'name'               => 'Công trình',
				'singular_name'      => 'Công trình',
				'add_new'            => 'Thêm công trình',
				'add_new_item'       => 'Thêm công trình mới',
				'edit_item'          => 'Sửa công trình',
				'view_item'          => 'Xem công trình',
				'all_items'          => 'Tất cả công trình',
				'search_items'       => 'Tìm công trình',
				'not_found'          => 'Không tìm thấy công trình.',
			),
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'cong-trinh' ),
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'          => 'dashicons-building',
			'menu_position'      => 6,
			'show_in_rest'       => true,
		) );
	}

	public function register_taxonomy() {
		register_taxonomy( 'loai_cong_trinh', 'cong_trinh', array(
			'labels' => array(
				'name'          => 'Loại công trình',
				'singular_name' => 'Loại công trình',
				'add_new_item'  => 'Thêm loại mới',
				'edit_item'     => 'Sửa loại',
				'all_items'     => 'Tất cả loại',
			),
			'public'            => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'loai-cong-trinh' ),
			'show_in_rest'      => true,
		) );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'cong_trinh_gallery',
			'Gallery ảnh công trình',
			array( $this, 'render_gallery_meta_box' ),
			'cong_trinh',
			'normal',
			'high'
		);
		add_meta_box(
			'cong_trinh_info',
			'Thông tin công trình',
			array( $this, 'render_info_meta_box' ),
			'cong_trinh',
			'side',
			'default'
		);
	}

	public function render_gallery_meta_box( $post ) {
		wp_nonce_field( 'cong_trinh_save', 'cong_trinh_nonce' );
		$gallery_ids = get_post_meta( $post->ID, '_cong_trinh_gallery', true );
		$ids_value   = is_array( $gallery_ids ) ? implode( ',', $gallery_ids ) : '';
		?>
		<div id="cong-trinh-gallery-wrap">
			<input type="hidden" id="cong_trinh_gallery_ids" name="cong_trinh_gallery_ids" value="<?php echo esc_attr( $ids_value ); ?>">

			<div id="cong-trinh-gallery-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;">
				<?php if ( ! empty( $gallery_ids ) ) :
					foreach ( $gallery_ids as $img_id ) :
						$src = wp_get_attachment_image_url( $img_id, 'thumbnail' );
						if ( $src ) : ?>
							<div class="ct-gallery-thumb" style="position:relative;width:80px;height:80px;">
								<img src="<?php echo esc_url( $src ); ?>" style="width:80px;height:80px;object-fit:cover;border-radius:3px;">
								<button type="button" class="ct-remove-img" data-id="<?php echo intval( $img_id ); ?>"
									style="position:absolute;top:-6px;right:-6px;background:#cc0000;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:12px;line-height:1;">✕</button>
							</div>
						<?php endif;
					endforeach;
				endif; ?>
			</div>

			<button type="button" id="cong-trinh-add-images" class="button button-primary">
				+ Thêm / Chọn ảnh
			</button>
			<p class="description" style="margin-top:6px;">Chọn nhiều ảnh cùng lúc từ thư viện media.</p>
		</div>

		<script>
		(function($){
			var frame;
			$('#cong-trinh-add-images').on('click', function(){
				if (frame) { frame.open(); return; }
				frame = wp.media({
					title: 'Chọn ảnh gallery công trình',
					button: { text: 'Thêm vào gallery' },
					multiple: true
				});
				frame.on('select', function(){
					var attachments = frame.state().get('selection').toJSON();
					var ids = $('#cong_trinh_gallery_ids').val().split(',').filter(Boolean);
					attachments.forEach(function(att){
						if (ids.indexOf(String(att.id)) === -1) {
							ids.push(att.id);
							var src = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
							$('#cong-trinh-gallery-preview').append(
								'<div class="ct-gallery-thumb" style="position:relative;width:80px;height:80px;">' +
								'<img src="' + src + '" style="width:80px;height:80px;object-fit:cover;border-radius:3px;">' +
								'<button type="button" class="ct-remove-img" data-id="' + att.id + '" style="position:absolute;top:-6px;right:-6px;background:#cc0000;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:12px;line-height:1;">✕</button>' +
								'</div>'
							);
						}
					});
					$('#cong_trinh_gallery_ids').val(ids.join(','));
				});
				frame.open();
			});

			$(document).on('click', '.ct-remove-img', function(){
				var id = String($(this).data('id'));
				$(this).closest('.ct-gallery-thumb').remove();
				var ids = $('#cong_trinh_gallery_ids').val().split(',').filter(function(v){ return v !== id; });
				$('#cong_trinh_gallery_ids').val(ids.join(','));
			});
		})(jQuery);
		</script>
		<?php
	}

	public function render_info_meta_box( $post ) {
		$location = get_post_meta( $post->ID, '_cong_trinh_location', true );
		$year     = get_post_meta( $post->ID, '_cong_trinh_year', true );
		$area     = get_post_meta( $post->ID, '_cong_trinh_area', true );
		?>
		<p>
			<label style="font-weight:600;display:block;margin-bottom:4px;">Địa điểm</label>
			<input type="text" name="cong_trinh_location" value="<?php echo esc_attr( $location ); ?>" style="width:100%" placeholder="VD: Hà Nội">
		</p>
		<p style="margin-top:10px;">
			<label style="font-weight:600;display:block;margin-bottom:4px;">Năm thực hiện</label>
			<input type="text" name="cong_trinh_year" value="<?php echo esc_attr( $year ); ?>" style="width:100%" placeholder="VD: 2024">
		</p>
		<p style="margin-top:10px;">
			<label style="font-weight:600;display:block;margin-bottom:4px;">Diện tích (m²)</label>
			<input type="text" name="cong_trinh_area" value="<?php echo esc_attr( $area ); ?>" style="width:100%" placeholder="VD: 120">
		</p>
		<?php
	}

	public function save_meta( $post_id ) {
		if ( ! isset( $_POST['cong_trinh_nonce'] ) || ! wp_verify_nonce( $_POST['cong_trinh_nonce'], 'cong_trinh_save' ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;

		// Gallery
		if ( isset( $_POST['cong_trinh_gallery_ids'] ) ) {
			$raw = sanitize_text_field( $_POST['cong_trinh_gallery_ids'] );
			$ids = array_filter( array_map( 'intval', explode( ',', $raw ) ) );
			update_post_meta( $post_id, '_cong_trinh_gallery', $ids );
		}

		// Info fields
		$fields = array( 'cong_trinh_location', 'cong_trinh_year', 'cong_trinh_area' );
		foreach ( $fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
			}
		}
	}

	public function admin_scripts( $hook ) {
		global $post_type;
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) && 'cong_trinh' === $post_type ) {
			wp_enqueue_media();
		}
	}
}
