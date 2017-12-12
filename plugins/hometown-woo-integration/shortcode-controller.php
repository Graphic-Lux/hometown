<?php


function hometown_step_1_func( $atts ){

  ?>


<div class="step_1">

  <div class="step-holder">
    <h3 class="custom_step">Step 1</h3>
    <h3 class="step_heading">Choose Your Style</h3>
  </div>
  <div class="type">
    <a>UNISEX</a>
    <a>MENS</a>
    <a>WOMENS</a>
    <a>YOUTH</a>
  </div>

  <?php



  $dir = HAWI_PLUGIN_PATH."assets/img/shirt-options/";
  $url = HAWI_PLUGIN_URL."assets/img/shirt-options/";

  $mensAndUnisex = array();
  $womens = array();
  $youth = array();

  // Open a directory, and read its contents
  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)) !== false){
        if (strpos($file, 'mens') === 0) {
          array_push($mensAndUnisex, $url.$file);
        } else if (strpos($file, 'womens') !== false) {
          array_push($womens, $url.$file);
        } else if (strpos($file, 'youth') !== false) {
          array_push($youth, $url.$file);
        }
      }
      closedir($dh);
    }
  }

  ?>
  <div class='subtype shirt_type'>
    <div class='shirt_slider_wrap'>
      <div class="swiper-container">
        <div class="swiper-wrapper mens-slider shirt-slider">
        <?php

          foreach($mensAndUnisex as $mensAndUnisexShirt) {
            $style = explode("$url"."mens-",$mensAndUnisexShirt);
            $style = explode('.png', $style[1])[0];
            echo "<div class='single_shirt swiper-slide' id='$style' data-type='mens'>";
              echo "<img src='$mensAndUnisexShirt' class='$style' />";
            echo '</div>';
          }

        ?>
        </div>

        <div class="swiper-wrapper womens-slider shirt-slider">
          <?php

          foreach($womens as $womensShirt) {
            $style = explode("$url"."womens-",$womensShirt);
            $style = explode('.png', $style[1])[0];
            echo "<div class='single_shirt swiper-slide' id='$style' data-type='womens'>";
              echo "<img src='$womensShirt' class='$style' />";
            echo '</div>';
          }

        ?>
      </div>

        <div class="swiper-wrapper youth-slider shirt-slider">
          <?php

          foreach($youth as $youthShirt) {
            $style = explode("$url"."youth-",$youthShirt);
            $style = explode('.png', $style[1])[0];
            echo "<div class='single_shirt swiper-slide' id='$style' data-type='youth'>";
              echo "<img src='$youthShirt' class='$style' />";
            echo '</div>';
          }

          ?>
        </div> <!-- swiper-wrapper -->

      </div> <!-- swiper-container -->

    </div> <!-- shirt_slider_wrap -->

  </div> <!-- subtype -->

  <div class="subtype product">
    <a class="shirt_view">GRID VIEW</a>
    <div class="product_slider_wrap"></div>
    <div class="product_grid_wrap"></div>
  </div>
<!--  <a href="#/" class="continue_button" id="continue_1" data-product-id="" data-product-variant-id="">CONTINUE &rarr;</a>-->
</div> <!-- step_1 -->
  <?php



}
add_shortcode( 'hometown_step_1', 'hometown_step_1_func' );













function hometown_step_2_func() {

?>

  <div class="step_2">
    <div class="step-holder">
        <h3 class="custom_step">Step 2</h3>
        <h3 class="step_heading">Create Your Design</h3>
    </div>
    <div class="shirt_positions">
<!--        --><?php //hometown_get_product_variant_images(); ?>
    </div>
    <div class="artwork_selection">
      <h4>Choose Your Artwork</h4>
      <div class="artwork_slider">

      </div>
    </div>
    <a>BACK IMPRINT</a>
  </div>
  <?php

}
add_shortcode( 'hometown_step_2', 'hometown_step_2_func' );









function hometown_step_3_func() {

  ?>
  <div class="step_3">
    <div class="step-holder">
      <h3 class="custom_step">Step 3</h3>
      <h3 class="step_heading">Choose Quantities</h3>
    </div>
    <div class="product_image_wrap">
      <div class="product_image">

      </div>
      <div class="product_thumbnails">

      </div>
    </div>
    <div class="shirt_sizes_wrap">

    </div>
    <a>REVIEW & PURCHASE</a>
  </div>
  <?php


}
add_shortcode( 'hometown_step_3', 'hometown_step_3_func' );









function product_grid_and_slider_func( $atts ){

//  $mensArgs = array( 'post_type' => 'product', 'posts_per_page' => 10, 'product_cat' => 'mens' );
//  $womensArgs = array( 'post_type' => 'product', 'posts_per_page' => 10, 'product_cat' => 'womens' );
//  $youthArgs = array( 'post_type' => 'product', 'posts_per_page' => 10, 'product_cat' => 'youth' );
//  $loop = new WP_Query( $mensArgs );
//
////  print_r($loop);
//
//  $tees = array();


  ?>
  <div class="subtype">
    <a class="shirt_view">GRID VIEW</a>
    <div class="shirt_slider_wrap">

    </div>
    <div class="shirt_grid_wrap">

    </div>
  </div>
  <?php

    // MENS
//    echo '<div class="swiper-wrapper mens">';
//
//      while ( $loop->have_posts() ) : $loop->the_post();
//
//      global $product;
//      $product_cats = wp_get_post_terms( get_the_ID(), 'product_cat' );
//
////      print_r($product_cats);
//
//      if ( $product_cats && ! is_wp_error ( $product_cats ) ){
//
//        foreach($product_cats as $cat) {
//
//          if (strtolower($cat->slug) === 'mens') {
//            array_push($mensAndUnisex, $product);
//          } else if (strtolower($cat->name) === 'womens') {
//            array_push($womens, $product);
//          } else if (strtolower($cat->name) === 'youth') {
//            array_push($youth, $product);
//          }
//
//        }
//
//      }
//
//      echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';
//
//      endwhile;
//    echo '</div>';
//
//
//
//
//
//
//
//  echo '</div>';
//  wp_reset_query();

}
add_shortcode( 'hometown_products', 'product_grid_and_slider_func' );




function test_shorty() {
  $output = '<div>';
  $output .= 'this is appended.';
  $output .= '</div>';

  return $output;
}
add_shortcode( 'test_shorty', 'test_shorty' );



