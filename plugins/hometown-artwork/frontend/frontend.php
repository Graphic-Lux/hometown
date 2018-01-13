<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function hometown_get_artwork() {

  $args2 = array(
      'post_type'             => 'Artwork',
      'post_status'           => 'publish',
      'ignore_sticky_posts'   => 1,
      'posts_per_page'        => '6',
  );

  $artwork = '<div class="swiper-container shirt_artwork">';

  $loop = new WP_Query( $args2 );

  $artworkFront = '<div class="swiper-wrapper hometown_artwork artwork-front">';
  $artworkBack = '<div class="swiper-wrapper hometown_artwork artwork-back">';
  $artworkSleeve = '<div class="swiper-wrapper hometown_artwork artwork-sleeve">';

  if ( $loop->have_posts() ) {
    while ( $loop->have_posts() ) : $loop->the_post();

      $categoryDataArray = get_the_category();

      foreach($categoryDataArray as $categoryData) {

        $category = $categoryData->slug;

        if ($category === 'front') {
          $artworkFront .= '<div class="single_art">';
            $artworkFront .= get_the_post_thumbnail(null, array(160,160));
            $artworkFront .= '<span class="artwork_title">' . get_the_title() . '</span>';
            $artworkFront .= custom_color_selector($categoryDataArray, $category);
          $artworkFront .= '</div>';
        } else if ($category === 'back') {
          $artworkBack .= '<div class="single_art">';
            $artworkBack .= get_the_post_thumbnail(null, array(160,160));
            $artworkBack .= '<span class="artwork_title">' . get_the_title() . '</span>';
            $artworkBack .= custom_color_selector($categoryDataArray, $category);
          $artworkBack .= '</div>';
        } else if ($category === 'sleeve') {
          $artworkSleeve .= '<div class="single_art">';
            $artworkSleeve .= get_the_post_thumbnail(null, array(160,160));
            $artworkSleeve .= '<span class="artwork_title">' . get_the_title() . '</span>';
            $artworkSleeve .= custom_color_selector($categoryDataArray, $category);
          $artworkSleeve .= '</div>';
        }

      }

    endwhile;



    $artworkFront .= "</div>";
    $artworkBack .= "</div>";
    $artworkSleeve .= "</div>";

    $artwork .= $artworkFront . $artworkBack . $artworkSleeve;

  } else {
    echo __( 'No products found' );
  }

  $artwork .= '</div>';

  echo $artwork;
  wp_reset_postdata();

}

function custom_color_selector($categoryDataArray, $orientation) {

  foreach($categoryDataArray as $categoryData) {

    if ($categoryData->slug === 'custom-color') {

      $customColorOutput = '<div class="hometown_custom_color_selector">';

        $customColorOutput .= '<div class="hometown_color_swatch green_swatch"></div>';
        $customColorOutput .= '<div class="hometown_color_swatch blue_swatch"></div>';
        $customColorOutput .= '<div class="hometown_color_swatch red_swatch"></div>';
        $customColorOutput .= '<div class="hometown_color_swatch yellow_swatch"></div>';
        $customColorOutput .= '<div class="hometown_color_swatch white_swatch"></div>';

        $customColorOutput .= '<div class="hometown_color_wheel">';
          $customColorOutput .= '<img src="'.HAA_PLUGIN_URL.'assets/img/color-wheel.png" class="'.$orientation.'" />';
          $customColorOutput .= '<input type="hidden" name="'.$orientation.'-color" id="'.$orientation.'-color_input" class="color_input" value="#ff0000" />';
        $customColorOutput .= '</div>';

      $customColorOutput .= '</div>';

      return $customColorOutput;
    }

  }

}