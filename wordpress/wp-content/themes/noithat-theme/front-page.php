<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>

<main class="site-main">

    <!-- Banner Section -->
    <section class="home-banner">
        <div class="home-banner__slider">
            <?php
            $banners = get_option( 'noithat_banners', array() );
            if ( ! empty( $banners ) ) :
                foreach ( $banners as $banner ) :
            ?>
            <div class="home-banner__item">
                <a href="<?php echo esc_url( $banner['link'] ); ?>">
                    <img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php echo esc_attr( $banner['title'] ); ?>">
                </a>
            </div>
            <?php
                endforeach;
            else :
            ?>
            <div class="home-banner__item home-banner__item--placeholder">
                <div class="home-banner__placeholder-text">
                    <h2><?php bloginfo( 'name' ); ?></h2>
                    <p>Chuyên nghiệp – Uy tín – Tận tâm</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Sản phẩm nổi bật -->
    <section class="home-featured">
        <div class="container">
            <h2 class="section-title">Sản phẩm nổi bật</h2>
            <div class="products-grid">
                <?php
                $featured = wc_get_featured_product_ids();
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 8,
                    'post__in'       => ! empty( $featured ) ? $featured : array( 0 ),
                    'orderby'        => 'post__in',
                );

                if ( empty( $featured ) ) {
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => 8,
                        'meta_key'       => 'total_sales',
                        'orderby'        => 'meta_value_num',
                        'order'          => 'DESC',
                    );
                }

                $products = new WP_Query( $args );

                if ( $products->have_posts() ) :
                    while ( $products->have_posts() ) : $products->the_post();
                        global $product;
                        get_template_part( 'template-parts/content/product-card' );
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            <div class="home-featured__more">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn--outline">
                    Xem tất cả sản phẩm
                </a>
            </div>
        </div>
    </section>

    <!-- Sản phẩm mới -->
    <section class="home-new-products">
        <div class="container">
            <h2 class="section-title">Sản phẩm mới nhất</h2>
            <div class="products-grid">
                <?php
                $new_products = new WP_Query( array(
                    'post_type'      => 'product',
                    'posts_per_page' => 8,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );

                if ( $new_products->have_posts() ) :
                    while ( $new_products->have_posts() ) : $new_products->the_post();
                        global $product;
                        get_template_part( 'template-parts/content/product-card' );
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
