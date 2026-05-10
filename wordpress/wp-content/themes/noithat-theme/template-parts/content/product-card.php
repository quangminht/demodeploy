<?php
defined( 'ABSPATH' ) || exit;
global $product;
if ( ! $product ) return;
?>
<div class="product-card">
    <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-card__img-wrap">
        <?php if ( $product->is_on_sale() ) : ?>
            <span class="product-card__badge product-card__badge--sale">Sale</span>
        <?php endif; ?>
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'noithat-product', array( 'class' => 'product-card__img', 'loading' => 'lazy' ) ); ?>
        <?php else : ?>
            <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php the_title_attribute(); ?>" class="product-card__img" loading="lazy">
        <?php endif; ?>
    </a>
    <div class="product-card__body">
        <h3 class="product-card__title">
            <a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
        </h3>
        <div class="product-card__price">
            <?php if ( $product->is_on_sale() ) : ?>
                <span class="product-card__price-sale"><?php echo esc_html( noithat_price( $product->get_sale_price() ) ); ?></span>
                <span class="product-card__price-regular"><?php echo esc_html( noithat_price( $product->get_regular_price() ) ); ?></span>
            <?php else : ?>
                <span class="product-card__price-regular product-card__price-regular--main">
                    <?php echo esc_html( noithat_price( $product->get_price() ) ); ?>
                </span>
            <?php endif; ?>
        </div>
        <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-card__btn">
            Xem chi tiết
        </a>
    </div>
</div>
