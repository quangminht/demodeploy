<?php
defined( 'ABSPATH' ) || exit;

$related_products = wc_get_related_products( get_the_ID(), 8 );
if ( empty( $related_products ) ) return;

$args = array(
    'post_type'      => 'product',
    'post__in'       => $related_products,
    'posts_per_page' => 8,
    'orderby'        => 'rand',
);
$related = new WP_Query( $args );
?>

<section class="related-products">
    <h2 class="section-title">Sản phẩm tương tự</h2>
    <div class="products-grid products-grid--4">
        <?php
        if ( $related->have_posts() ) :
            while ( $related->have_posts() ) : $related->the_post();
                global $product;
                $product = wc_get_product( get_the_ID() );
                if ( ! $product ) continue;
                get_template_part( 'template-parts/content/product-card' );
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</section>
