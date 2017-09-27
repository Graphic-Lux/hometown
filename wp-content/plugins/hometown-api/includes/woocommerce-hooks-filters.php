<?php

add_action( 'wp_ajax_nopriv_woocommerce_json_search_products', array( 'WC_AJAX', 'json_search_products') );
// WC AJAX can be used for frontend ajax requests.
add_action( 'wc_ajax_json_search_products', array( 'WC_AJAX', 'json_search_products') );

function hometown_get_products_by_category() {

  if ((isset($_GET['style'])) && (isset($_GET['type']))) {
    $style = $_GET['style'];
    $type = $_GET['type'];
  }

  $style='mens';
  $type="tee";

  $taxonomy     = 'product_cat';
  $orderby      = 'name';
  $show_count   = 0;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no
  $title        = '';
  $empty        = 0;

  $args = array(
      'taxonomy'     => $taxonomy,
      'orderby'      => $orderby,
      'show_count'   => $show_count,
      'pad_counts'   => $pad_counts,
      'hierarchical' => $hierarchical,
      'title_li'     => $title,
      'hide_empty'   => $empty
  );
  $all_categories = get_categories( $args );

//  print_r($all_categories);

  $categories = array();

  foreach ($all_categories as $cat) {
    $categories[$cat->slug] = $cat->term_id;
  }

//  print_r($categories);

  if ((array_key_exists($style, $categories)) && (array_key_exists($type, $categories))){
    $categoryIDStyle = $categories[$style];
    $categoryIDType = $categories[$type];

//    $args = array(
//        'post_type'             => 'product',
//        'post_status'           => 'publish',
//        'ignore_sticky_posts'   => 1,
//        'posts_per_page'        => '12',
//        'meta_query'            => array(
//            array(
//                'key'           => '_visibility',
//                'value'         => array('catalog', 'visible'),
//                'compare'       => 'IN'
//            )
//        ),
//        'tax_query'             => array(
//            array(
//                'taxonomy'      => 'product_cat',
//                'field' => 'term_id', //This is optional, as it defaults to 'term_id'
////                'terms'         => array($categoryIDStyle, $categoryIDType),
//                'terms'         => $categoryIDType,
//                'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
//            )
//        )
//    );
//    $products = new WP_Query($args);
//    var_dump($products);


//  $args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => 2,'product_cat' => $categoryIDType, 'orderby' =>'date','order' => 'ASC' );
//  $loop = new WP_Query( $args );
//  while ( $loop->have_posts() ) : $loop->the_post();
//  global $product;
//  print_r($product);
//    do_action( 'woocommerce_shop_loop' );
//  endwhile;
//  wp_reset_query();
//
  }


}