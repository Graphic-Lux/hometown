<?php
/**
 * Created by PhpStorm.
 * User: gerhard
 * Date: 8/11/17
 * Time: 2:14 PM
 */

require_once(REST_PLUGIN_PATH . 'controllers/shortcode-controller.php');

?>

<?php get_header(); ?>


  <div class="step_1">
	<div class="step-holder">
	    <h3 class="custom_step">Step 1</h3>
	    <h3 class="step_heading">Choose Your Style</h3>
	</div>
	<div class="step-holder">
	    <div class="type">
	      <a>UNISEX</a>
	      <a>MENS</a>
	      <a>WOMENS</a>
	      <a>YOUTH</a>
	    </div>
	</div>
    <div class="subtype">
      <div class="shirt_slider_wrap">
        <?php echo do_shortcode('[shirt_slider_option]'); ?>
      </div>
    </div>
    <div class="subtype">
      <a class="shirt_view">GRID VIEW</a>
      <div class="shirt_slider_wrap">
        <?php echo do_shortcode('[product_slider]'); ?>
      </div>
      <div class="shirt_grid_wrap">
        <?php echo do_shortcode('[product_grid]'); ?>
      </div>
    </div>
    <a class="continue">CONTINUE</a>
  </div>

  <div class="step_2">
	<div class="step-holder">  
	    <h3 class="custom_step">Step 2</h3>
	    <h3 class="step_heading">Create Your Design</h3>
	</div>
    <div class="shirt_positions">
      <div class="shirt_front">

      </div>
      <div class="shirt_back">

      </div>
      <div class="shirt_sleeve">

      </div>
    </div>
    <div class="artwork_selection">
      <h4>Choose Your Artwork</h4>
      <div class="artwork_slider">

      </div>
    </div>
    <a>BACK IMPRINT</a>
  </div>

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


<?php get_footer(); ?>