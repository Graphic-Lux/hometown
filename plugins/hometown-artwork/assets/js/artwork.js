$ = jQuery;

function artwork_init() {

  //console.log('artwork init');

  $('.product_image_wrap.subtype').fadeIn();
  $('.step_2_shirt_designs').fadeTo(100, .4);
  $('.step_2_shirt_designs figure').removeClass('selected');
  $('.step_2_shirt_designs:first').fadeTo(100, 1);
  $('.step_2_shirt_designs figure:first').addClass('selected');


  // Initialize color inputs
  color_input_init();


  $('.step_2_shirt_designs figure').unbind().click(function () {
    $('.step_2_shirt_designs').fadeTo(200, .4);
    $('.step_2_shirt_designs').removeClass('selected');
    $(this).parent().fadeTo(100, 1);
    $(this).addClass('selected');

    let orientation = $(this).attr('id');

    artwork_display(orientation);

  });


  // CHANGE ARTWORK LOCATION ON DROPDOWN VALUE CHANGE
  $('.imprint_location_dropdown').unbind().change(function () {
    apply_artwork_to_shirt(false, $(this).attr('name').split('-imprint_location')[0]);
  });


  // CLICK ARTWORK TO PLACE ON T-SHIRT
  $(".single_art img").unbind().click(function () {
    apply_artwork_to_shirt($(this).clone(), $(this).parent().parent().attr('class').split('artwork-')[1]);
  });

  // CLICK ARTWORK TO PLACE ON T-SHIRT
  $(".single_art svg").unbind().click(function () {
    apply_artwork_to_shirt($(this).clone(), $(this).parent().parent().attr('class').split('artwork-')[1]);
  });


}


/**
 * Initailize the color input by adding data attributes to each artwork orientation
 *
 * @return void
 *
 */
function color_input_init() {

  let orientation;

  let colorInputSVG;
  let colorInputSelector;

  // Assign ID's to each front SVG
  $('.hometown_artwork .single_art').each(function (i) {

    orientation = $(this).parent().attr('class').split('artwork-')[1];

    colorInputSVG = $(this).find('svg').attr('data-svg', i);

    colorInputSelector = $(this).find('.hometown_color_wheel input.color_input').attr('data-color-selector', i);

    apply_color_to_svg(colorInputSVG, colorInputSelector, orientation, i );
  });

}

/**
 * Apply the color to the svg
 *
 * @param svg - the svg element relative to it's orientation
 * @param selector - the color selector relative to it's orientation
 * @param orientation - the orientation of the artwork, so we can change the cloned artwork
 * @param id - the data key value of the artwork
 *
 * @return void
 *
 */
function apply_color_to_svg(svg, selector, orientation, id) {
  selector.spectrum({
    preferredFormat: "hex",
    showInput: true,
    clickoutFiresChange: true,
    showButtons: false,
    showAlpha: true,
    move: function (color) {
      // Give svg a color data value (hex)
      svg.attr('data-color-val', color.toHexString());

      // Assign color to the SVG element based on movement in the color selection tool
      svg.find('g').css("fill", color);
      svg.find('path').css("fill", color);

      // If there is a clone, change it's color too
      if( $('figure#' + orientation).find($('[data-svg="' + id + '"]')).length ) {
        let clone = $('figure#' + orientation);

        clone.find('g').css("fill", color);
        clone.find('path').css("fill", color);
      }

    }
  });

}


function apply_artwork_to_shirt(artClone, shirtOrientation) {

  let imprintLocation = $("#" + shirtOrientation + "-imprint_location").val();

  console.log(imprintLocation, artClone, shirtOrientation);

  if (imprintLocation != 0) {

    if (artClone === false) {
      artClone = $('.' + shirtOrientation + '-selected_art');
    }

    $('.' + shirtOrientation + '-selected_art').remove();
    console.log(artClone);

    //artClone.removeClass('full_front mid_chest pocket full_back upper_back lower_back left_sleeve right_sleeve');

    if (imprintLocation === 'right_sleeve') {
      $('#' + shirtOrientation + ' img').addClass('reflect');
    } else {
      $('#' + shirtOrientation + ' img').removeClass('reflect');
    }

    $(artClone).addClass(shirtOrientation + '-selected_art');
    $(artClone).addClass(imprintLocation);
    $('#' + shirtOrientation).append(artClone);

    // TODO: SUMNER SAVE ARTWORK SELECTION TO USER META
    let data = {
      action: 'hometown_save_imprint_artwork',
      product_id: $("#continue_3").data('product-id'),
      variation_id: $("#continue_3").data('product-variant-id'),
      front: frontImprintArtworkURL,
      frontColor: frontImprintArtworkColor,
      back: backImprintArtworkURL,
      backColor: backImprintArtworkColor,
      sleeve: sleeveImprintAtrworkURL,
      sleeveColor: sleeveImprintAtrworkColor
    };

    $.post( wc_add_to_cart_params.ajax_url, data, function( response ) {
      // console.log(response);
    });

  } else {

    alert('Please select an imprint location for the artwork using the dropdown.');

  }

}


function artwork_display(orientation) {

  $('.hometown_artwork').hide();
  $('.artwork-' + orientation).show();

}