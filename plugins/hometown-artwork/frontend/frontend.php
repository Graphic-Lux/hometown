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
          $artworkFront .= '</div>';
        } else if ($category === 'back') {
          $artworkBack .= '<div class="single_art">';
           $artworkBack .= get_the_post_thumbnail(null, array(160,160));
          $artworkBack .= '</div>';
        } else if ($category === 'sleeve') {
          $artworkSleeve .= '<div class="single_art">';
            $artworkSleeve .= get_the_post_thumbnail(null, array(160,160));
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