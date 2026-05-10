<?php
defined( 'ABSPATH' ) || exit;
get_header();

$current_type = get_queried_object();
$loai_terms   = get_terms( array( 'taxonomy' => 'loai_cong_trinh', 'hide_empty' => true ) );
?>

<main class="site-main">
    <div class="container">

        <!-- Page heading -->
        <h1 class="page-title">Công Trình Đã Thực Hiện</h1>

        <!-- Filter by loại -->
        <?php if ( ! empty( $loai_terms ) && ! is_wp_error( $loai_terms ) ) : ?>
        <div class="ct-filter">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'cong_trinh' ) ); ?>"
               class="ct-filter__btn<?php echo ! is_tax( 'loai_cong_trinh' ) ? ' is-active' : ''; ?>">
                Tất cả
            </a>
            <?php foreach ( $loai_terms as $term ) : ?>
            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
               class="ct-filter__btn<?php echo ( $current_type instanceof WP_Term && $current_type->term_id === $term->term_id ) ? ' is-active' : ''; ?>">
                <?php echo esc_html( $term->name ); ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Grid -->
        <?php if ( have_posts() ) : ?>
        <div class="ct-grid">
            <?php while ( have_posts() ) : the_post();
                $gallery_ids = get_post_meta( get_the_ID(), '_cong_trinh_gallery', true );
                $thumb_src   = '';
                if ( has_post_thumbnail() ) {
                    $thumb_src = get_the_post_thumbnail_url( get_the_ID(), 'noithat-product' );
                } elseif ( ! empty( $gallery_ids ) ) {
                    $thumb_src = wp_get_attachment_image_url( $gallery_ids[0], 'noithat-product' );
                }
                $location = get_post_meta( get_the_ID(), '_cong_trinh_location', true );
                $year     = get_post_meta( get_the_ID(), '_cong_trinh_year', true );
                $terms    = get_the_terms( get_the_ID(), 'loai_cong_trinh' );
            ?>
            <a href="<?php the_permalink(); ?>" class="ct-card">
                <div class="ct-card__img">
                    <?php if ( $thumb_src ) : ?>
                        <img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                    <?php else : ?>
                        <div class="ct-card__img-placeholder">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>
                        </div>
                    <?php endif; ?>
                    <?php if ( ! empty( $gallery_ids ) ) : ?>
                        <span class="ct-card__count">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>
                            <?php echo count( $gallery_ids ); ?> ảnh
                        </span>
                    <?php endif; ?>
                </div>
                <div class="ct-card__body">
                    <?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
                        <span class="ct-card__type"><?php echo esc_html( $terms[0]->name ); ?></span>
                    <?php endif; ?>
                    <h3 class="ct-card__title"><?php the_title(); ?></h3>
                    <div class="ct-card__meta">
                        <?php if ( $location ) : ?>
                            <span>📍 <?php echo esc_html( $location ); ?></span>
                        <?php endif; ?>
                        <?php if ( $year ) : ?>
                            <span>📅 <?php echo esc_html( $year ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endwhile; ?>
        </div>

        <div class="ct-pagination">
            <?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
        </div>

        <?php else : ?>
        <div class="ct-empty">
            <p>Chưa có công trình nào. Vui lòng quay lại sau.</p>
        </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
