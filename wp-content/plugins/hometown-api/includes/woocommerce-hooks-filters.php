<?php

add_action( 'wp_ajax_nopriv_woocommerce_json_search_products', array( 'WC_AJAX', 'json_search_products') );
// WC AJAX can be used for frontend ajax requests.
add_action( 'wc_ajax_json_search_products', array( 'WC_AJAX', 'json_search_products') );



//add_action( 'wp_ajax_nopriv_hometown_get_products_by_category', 'hometown_get_products_by_category' );

// WE WANT ONLY LOGGED IN USERS TO BE ALLOWED TO DO THIS
add_action( 'wp_ajax_hometown_get_products_by_category', 'hometown_get_products_by_category' );

function hometown_get_products_by_category() {

  if ((isset($_GET['style'])) && (isset($_GET['type']))) {
    $style = preg_replace('/\PL/u', '', $_GET['style']);
    $type = preg_replace('/\PL/u', '', $_GET['type']);
  }

  // TODO: FIX META_QUERY
  $args2 = array(
        'post_type'             => 'product',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => 1,
        'posts_per_page'        => '12',
//        'meta_query'            => array(
//            array(
//                'key'           => '_visibility',
//                'value'         => array('catalog', 'visible'),
//                'compare'       => 'IN'
//            )
//        ),
        'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
//                'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
                'field'         => 'slug',
//                'terms'         => array($categoryIDType, $categoryIDStyle),
                'terms'         => array($style, $type),
                'operator'      => 'AND' // Possible values are 'IN', 'NOT IN', 'AND'.
            )
        )
    );

    ?>
    <ul class="products">
      <?php
        $loop = new WP_Query( $args2 );
//      echo $loop->request;
        if ( $loop->have_posts() ) {
          while ( $loop->have_posts() ) : $loop->the_post();
            wc_get_template_part( 'content', 'product' );
          endwhile;
        } else {
          echo __( 'No products found' );
        }
        wp_reset_postdata();
      ?>
    </ul><!--/.products-->
    <?php
}