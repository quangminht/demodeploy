<?php
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) : the_post();

$gallery_ids = get_post_meta( get_the_ID(), '_cong_trinh_gallery', true );
$location    = get_post_meta( get_the_ID(), '_cong_trinh_location', true );
$year        = get_post_meta( get_the_ID(), '_cong_trinh_year', true );
$area        = get_post_meta( get_the_ID(), '_cong_trinh_area', true );
$terms       = get_the_terms( get_the_ID(), 'loai_cong_trinh' );

// Collect all gallery images (exclude featured image)
$all_images = array();
if ( ! empty( $gallery_ids ) ) {
    foreach ( $gallery_ids as $img_id ) {
        $full_url  = wp_get_attachment_image_url( $img_id, 'full' );
        $thumb_url = wp_get_attachment_image_url( $img_id, 'large' );
        if ( $thumb_url ) {
            $all_images[] = array(
                'thumb' => $thumb_url,
                'full'  => $full_url,
                'alt'   => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
            );
        }
    }
}
?>

<main class="site-main">
    <div class="container">

        <!-- Breadcrumb -->
        <nav class="ct-breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
            <span>›</span>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'cong_trinh' ) ); ?>">Công trình</a>
            <?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
                <span>›</span>
                <a href="<?php echo esc_url( get_term_link( $terms[0] ) ); ?>"><?php echo esc_html( $terms[0]->name ); ?></a>
            <?php endif; ?>
            <span>›</span>
            <span><?php the_title(); ?></span>
        </nav>

        <!-- Title -->
        <h1 class="page-title"><?php the_title(); ?></h1>

        <!-- Meta info -->
        <?php if ( $location || $year || $area || ( $terms && ! is_wp_error( $terms ) ) ) : ?>
        <div class="ct-single-meta">
            <?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
            <span class="ct-single-meta__item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                <?php echo esc_html( $terms[0]->name ); ?>
            </span>
            <?php endif; ?>
            <?php if ( $location ) : ?>
            <span class="ct-single-meta__item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo esc_html( $location ); ?>
            </span>
            <?php endif; ?>
            <?php if ( $year ) : ?>
            <span class="ct-single-meta__item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Năm <?php echo esc_html( $year ); ?>
            </span>
            <?php endif; ?>
            <?php if ( $area ) : ?>
            <span class="ct-single-meta__item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3zM3 9h18M9 3v18"/></svg>
                <?php echo esc_html( $area ); ?> m²
            </span>
            <?php endif; ?>
            <?php if ( ! empty( $all_images ) ) : ?>
            <span class="ct-single-meta__item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>
                <?php echo count( $all_images ); ?> ảnh
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Description -->
        <?php if ( get_the_content() ) : ?>
        <div class="ct-single-content">
            <?php the_content(); ?>
        </div>
        <?php endif; ?>

        <!-- Gallery grid -->
        <?php if ( ! empty( $all_images ) ) : ?>
        <div class="ct-photo-grid" id="ct-photo-grid">
            <?php foreach ( $all_images as $i => $img ) : ?>
            <div class="ct-photo-grid__item" data-index="<?php echo $i; ?>">
                <img src="<?php echo esc_url( $img['thumb'] ); ?>"
                     alt="<?php echo esc_attr( $img['alt'] ); ?>"
                     loading="<?php echo $i < 4 ? 'eager' : 'lazy'; ?>">
                <div class="ct-photo-grid__overlay">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Back link -->
        <div class="ct-single-footer">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'cong_trinh' ) ); ?>" class="ct-single-back">
                ← Xem tất cả công trình
            </a>
            <a href="<?php echo esc_url( home_url( '/lien-he' ) ); ?>" class="ct-single-cta-btn">
                Tư vấn công trình tương tự
            </a>
        </div>

    </div>
</main>

<!-- Lightbox -->
<?php if ( ! empty( $all_images ) ) : ?>
<div id="ct-lightbox" class="ct-lightbox" style="display:none;">
    <div class="ct-lightbox__overlay" id="ct-lightbox-close"></div>
    <div class="ct-lightbox__inner">
        <button class="ct-lightbox__close" id="ct-lightbox-close-btn">✕</button>
        <img id="ct-lightbox-img" src="" alt="">
        <?php if ( count( $all_images ) > 1 ) : ?>
        <button class="ct-lightbox__nav ct-lightbox__nav--prev" id="ct-lb-prev">&#8249;</button>
        <button class="ct-lightbox__nav ct-lightbox__nav--next" id="ct-lb-next">&#8250;</button>
        <?php endif; ?>
    </div>
</div>
<?php
$js_images = array_map( function( $img ) {
    return array( 'thumb' => $img['thumb'], 'full' => $img['full'] );
}, $all_images );
wp_localize_script( 'noithat-main', 'ctGallery', array( 'images' => $js_images ) );
endif;

endwhile;
get_footer();
?>
