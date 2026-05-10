<?php
defined( 'ABSPATH' ) || exit;
global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

// Remove from after_summary so we can position them manually.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

$hotline = get_option( 'noithat_hotline', '0901 234 567' );
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<div class="sp-layout">

		<div class="sp-images">
			<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
		</div>

		<div class="sp-summary entry-summary">
			<?php do_action( 'woocommerce_single_product_summary' ); ?>

			<?php if ( $hotline ) : ?>
			<a href="tel:<?php echo esc_attr( preg_replace( '/\D/', '', $hotline ) ); ?>" class="sp-hotline-btn">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
				Hotline: <?php echo esc_html( $hotline ); ?>
			</a>
			<?php endif; ?>
		</div>

		<aside class="sp-sidebar">

			<div class="sp-benefits">
				<h3 class="sp-benefits__title">Giao Hàng Toàn Quốc</h3>
				<ul class="sp-benefits__list">
					<li class="sp-benefits__item">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="var(--color-primary)"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
						<span>Giao hàng toàn quốc, miễn phí nội thành</span>
					</li>
					<li class="sp-benefits__item">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="var(--color-primary)"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
						<span>Bảo hành chính hãng 12 tháng</span>
					</li>
					<li class="sp-benefits__item">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="var(--color-primary)"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
						<span>Đổi trả dễ dàng trong 7 ngày</span>
					</li>
					<li class="sp-benefits__item">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="var(--color-primary)"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
						<span>Tư vấn tận tâm 24/7</span>
					</li>
				</ul>
			</div>

			<div class="sp-newest">
				<h3 class="sp-newest__title">Sản phẩm mới</h3>
				<?php
				$sp_newest = new WP_Query( array(
					'post_type'      => 'product',
					'posts_per_page' => 5,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'post__not_in'   => array( get_the_ID() ),
					'post_status'    => 'publish',
				) );
				if ( $sp_newest->have_posts() ) :
					while ( $sp_newest->have_posts() ) : $sp_newest->the_post();
						$sp_item_product = wc_get_product( get_the_ID() );
						if ( ! $sp_item_product ) {
							continue;
						}
						?>
						<a href="<?php the_permalink(); ?>" class="sp-newest__item">
							<div class="sp-newest__img">
								<?php if ( has_post_thumbnail() ) :
									the_post_thumbnail( 'thumbnail' );
								else : ?>
									<img src="<?php echo esc_url( wc_placeholder_img_src( 'thumbnail' ) ); ?>" alt="">
								<?php endif; ?>
							</div>
							<div class="sp-newest__info">
								<p class="sp-newest__name"><?php the_title(); ?></p>
								<p class="sp-newest__price"><?php echo wp_kses_post( $sp_item_product->get_price_html() ); ?></p>
							</div>
						</a>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>

		</aside>

	</div><!-- .sp-layout -->

	<?php
	// Fire hook for any other plugins (default tabs/related/upsells already removed above).
	do_action( 'woocommerce_after_single_product_summary' );

	// Product data tabs.
	woocommerce_output_product_data_tabs();

	// Related products (uses our woocommerce/single-product/related.php override).
	woocommerce_output_related_products();
	?>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
