<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function hometown_get_artwork() {


  ////////////// FRONT ARTWORK

  $frontArtworkArgs = array(
      'post_type'             => 'Artwork',
      'post_status'           => 'publish',
      'posts_per_page'        => 99,
      'category'              => 40,
  );

  $artworkFront = '<div class="swiper-container shirt_artwork artwork-front">';

  $postslist = get_posts( $frontArtworkArgs );

  $artworkFront .= '<div class="swiper-wrapper hometown_artwork">';

  foreach($postslist as $post) :
    setup_postdata($post);

    $artworkFront .= '<div class="single_art" data-artwork-id="' . $post->ID . '" data-orientation="front">';
    $artworkFront .= get_the_post_thumbnail($post, array(160, 160, 'class' => ' force-inline-svg'));
    $artworkFront .= '<span class="artwork_title">' . get_the_title($post) . '</span>';

      $categoryDataArray = get_the_category($post);
      foreach($categoryDataArray as $categoryData) {
        $artworkFront .= custom_color_selector($categoryData, $categoryData->slug);
      }

    $artworkFront .= '</div>';

  endforeach;
  wp_reset_postdata();


  $artworkFront .= "</div>"; // swiper-wrapper
  $artworkFront .= "</div>"; // swiper-container




  ////////////// BACK ARTWORK

  $backArtworkArgs = array(
      'post_type'             => 'Artwork',
      'post_status'           => 'publish',
      'posts_per_page'        => 99,
      'category'              => 41,
  );

  $artworkBack = '<div class="swiper-container shirt_artwork artwork-back">';

  $postslist = get_posts( $backArtworkArgs );

  $artworkBack .= '<div class="swiper-wrapper hometown_artwork">';

  foreach($postslist as $post) :
    setup_postdata($post);

    $artworkBack .= '<div class="single_art" data-artwork-id="' . $post->ID . '" data-orientation="back">';
    $artworkBack .= get_the_post_thumbnail($post, array(160, 160, 'class' => ' force-inline-svg'));
    $artworkBack .= '<span class="artwork_title">' . get_the_title($post) . '</span>';

    $categoryDataArray = get_the_category($post);
      foreach($categoryDataArray as $categoryData) {
        $artworkBack .= custom_color_selector($categoryData, $categoryData->slug);
      }

    $artworkBack .= '</div>';

  endforeach;
  wp_reset_postdata();


  $artworkBack .= "</div>"; // swiper-wrapper
  $artworkBack .= "</div>"; // swiper-container




  /////////////// SLEEVE ARTWORK

  $sleeveArtworkArgs = array(
      'post_type'             => 'Artwork',
      'post_status'           => 'publish',
      'posts_per_page'        => 99,
      'category'              => 42,
  );

  $artworkSleeve = '<div class="swiper-container shirt_artwork artwork-sleeve">';

  $postslist = get_posts( $sleeveArtworkArgs );

  $artworkSleeve .= '<div class="swiper-wrapper hometown_artwork">';

  foreach ($postslist as $post) :
    setup_postdata($post);

    $artworkSleeve .= '<div class="single_art" data-artwork-id="' . $post->ID . '" data-orientation="sleeve">';
    $artworkSleeve .= get_the_post_thumbnail($post, array(160, 160, 'class' => ' force-inline-svg'));
    $artworkSleeve .= '<span class="artwork_title">' . get_the_title($post) . '</span>';

      $categoryDataArray = get_the_category($post);
      foreach($categoryDataArray as $categoryData) {
        $artworkSleeve .= custom_color_selector($categoryData, $categoryData->slug);
      }

    $artworkSleeve .= '</div>';

  endforeach;
  wp_reset_postdata();

  $artworkSleeve .= "</div>"; // swiper-wrapper
  $artworkSleeve .= "</div>"; // swiper-container


  $artwork = $artworkFront . $artworkBack . $artworkSleeve;

  echo $artwork;


}

function custom_color_selector($categoryData, $orientation) {

    if ($categoryData->slug === 'custom-color') {

      $customColorOutput = '<div class="hometown_custom_color_selector">';

        $customColorOutput .= '<div class="hometown_color_swatch green_swatch"></div>';
        $customColorOutput .= '<div class="hometown_color_swatch blue_swatch"></div>';
        $customColorOutput .= '<div class="hometown_color_swatch red_swatch"></div>';
        $customColorOutput .= '<div class="hometown_color_swatch yellow_swatch"></div>';
        $customColorOutput .= '<div class="hometown_color_swatch white_swatch"></div>';

        $customColorOutput .= '<div class="hometown_color_wheel">';
          $customColorOutput .= '<input type="hidden" name="'.$orientation.'-color" id="'.$orientation.'-color_input" class="color_input">';
        $customColorOutput .= '</div>';

      $customColorOutput .= '</div>';

      return $customColorOutput;
    }

}