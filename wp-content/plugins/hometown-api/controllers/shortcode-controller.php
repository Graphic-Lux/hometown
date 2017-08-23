<?php


function shirt_slider_option_func( $atts ){

  $dir = REST_PLUGIN_PATH."assets/img/shirt-options/";
  $url = REST_PLUGIN_URL."assets/img/shirt-options/";

  $mens = array();
  $womens = array();
  $youth = array();

// Open a directory, and read its contents
  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)) !== false){
        if (strpos($file, 'mens') === 0) {
          array_push($mens, $url.$file);
        } else if (strpos($file, 'womens') !== false) {
          array_push($womens, $url.$file);
        } else if (strpos($file, 'youth') !== false) {
          array_push($youth, $url.$file);
        }
      }
      closedir($dh);
    }
  }

//  print_r($mens);
//  print_r($womens);
//  print_r($youth);

  echo '<div class="slider">';

    echo '<div class="mens_slider">';
      foreach($mens as $mensShirt) {
        echo '<div class="single_shirt">';
          echo "<img src='$mensShirt' />";
        echo '<div>';
      }
    echo '</div>';

    echo '<div class="womens_slider">';
      foreach($womens as $womensShirt) {
        echo '<div class="single_shirt">';
          echo "<img src='$womensShirt' />";
        echo '<div>';
      }
    echo '</div>';

    echo '<div class="youth_slider">';
      foreach($youth as $youthShirt) {
        echo '<div class="single_shirt">';
          echo "<img src='$youthShirt' />";
        echo '<div>';
      }
    echo '</div>';
  echo '</div>';

//  return "foo and bar";
}
add_shortcode( 'shirt_slider_option', 'shirt_slider_option_func' );





function product_slider_func( $atts ){

  echo 'product_slider as';

//  return "foo and bar";
}
add_shortcode( 'product_slider', 'product_slider_func' );






function product_grid_func( $atts ){

  echo 'product_grid as';

//  return "foo and bar";
}
add_shortcode( 'product_grid', 'product_grid_func' );