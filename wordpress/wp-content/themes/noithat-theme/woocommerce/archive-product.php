<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>

<main class="site-main">
    <div class="container">

        <div class="archive-layout">

            <!-- Sidebar trái -->
            <aside class="archive-sidebar">

                <!-- Danh mục sản phẩm -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-widget__title">Danh mục</h3>
                    <ul class="sidebar-price-filter">
                        <?php
                        $current_cat = get_queried_object();
                        $product_cats = get_terms( array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'parent'     => 0,
                        ) );
                        foreach ( $product_cats as $cat ) {
                            $is_active = ( $current_cat instanceof WP_Term && $current_cat->term_id === $cat->term_id );
                            printf(
                                '<li%s><a href="%s">%s</a></li>',
                                $is_active ? ' class="current-cat"' : '',
                                esc_url( get_term_link( $cat ) ),
                                esc_html( $cat->name )
                            );
                        }
                        ?>
                    </ul>
                </div>

                <!-- Lọc theo giá (nếu có WooCommerce widget) -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-widget__title">Khoảng giá</h3>
                    <ul class="sidebar-price-filter">
                        <?php
                        $price_ranges = array(
                            array( 'label' => 'Dưới 5 triệu',    'min' => 0,       'max' => 5000000 ),
                            array( 'label' => '5 - 10 triệu',    'min' => 5000000, 'max' => 10000000 ),
                            array( 'label' => '10 - 20 triệu',   'min' => 10000000,'max' => 20000000 ),
                            array( 'label' => '20 - 50 triệu',   'min' => 20000000,'max' => 50000000 ),
                            array( 'label' => 'Trên 50 triệu',   'min' => 50000000,'max' => 0 ),
                        );
                        $current_url = strtok( get_pagenum_link(), '?' );
                        foreach ( $price_ranges as $range ) {
                            $url = $range['max']
                                ? add_query_arg( array( 'min_price' => $range['min'], 'max_price' => $range['max'] ), $current_url )
                                : add_query_arg( array( 'min_price' => $range['min'] ), $current_url );
                            echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $range['label'] ) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>

            </aside>

            <!-- Main content -->
            <div class="archive-main">

                <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                    <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
                <?php endif; ?>

                <?php do_action( 'woocommerce_archive_description' ); ?>

                <?php if ( woocommerce_product_loop() ) : ?>

                    <div class="archive-toolbar">
                        <span class="archive-toolbar__count">
                            <?php woocommerce_result_count(); ?>
                        </span>
                        <div class="archive-toolbar__ordering">
                            <?php woocommerce_catalog_ordering(); ?>
                        </div>
                    </div>

                    <?php woocommerce_product_loop_start(); ?>

                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php wc_get_template_part( 'content', 'product' ); ?>
                        <?php endwhile; ?>

                    <?php woocommerce_product_loop_end(); ?>

                    <?php
                    woocommerce_pagination();
                    ?>

                <?php else : ?>
                    <?php do_action( 'woocommerce_no_products_found' ); ?>
                <?php endif; ?>

            </div><!-- .archive-main -->

        </div><!-- .archive-layout -->

    </div><!-- .container -->
</main>

<?php get_footer(); ?>
