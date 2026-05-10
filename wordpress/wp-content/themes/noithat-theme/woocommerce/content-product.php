<?php
defined( 'ABSPATH' ) || exit;
global $product;
if ( ! $product ) return;
get_template_part( 'template-parts/content/product-card' );
