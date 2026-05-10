<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main class="site-main">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
