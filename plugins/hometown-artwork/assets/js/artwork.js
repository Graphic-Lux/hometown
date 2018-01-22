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

  // $('#front-color_input').wheelColorPicker();
  // $('#back-color_input').wheelColorPicker();
  // $('#sleeve-color_input').wheelColorPicker();

  // $('#front-color_input').spectrum();
  // $('#back-color_input').spectrum();
  // $('#sleeve-color_input').spectrum();


  // $('.hometown_custom_color_selector .hometown_color_wheel').unbind().click(function() {
  //
  //   let img = $(this).find('img');
  //   let orientation = img.attr('class');
  //
  //   console.log(orientation);

  //$('#'+orientation+'-color_input').wheelColorPicker('show');

  // $('#'+orientation+'-color_input').spectrum({
  //   preferredFormat: "hex",
  //   clickoutFiresChange: true,
  //   showButtons: false,
  //   move: function(color) {
  //     $("svg g").css("fill", color.toHexString());
  //   }
  // });

  //   return false;
  //
  //});


}


/**
 * Initailize the color input by adding data attributes and calling spectrum.js
 *
 * @return void
 *
 */
function color_input_init() {

  let colorInputSVG;
  let colorInputSelector;

  // Assign ID's to each SVG
  $('.single_art svg').each(function (i) {
    colorInputSVG = $(this);
    colorInputSVG.attr('data-svg', i);
  });

  // Find all color inputs
  $('.hometown_custom_color_selector .hometown_color_wheel input.color_input').each(function (i) {
    colorInputSelector = $(this);

    // Assign ID's to the color inputs
    colorInputSelector.attr('data-color-selector', i);

    // Initialize color selection tool
    $('[data-color-selector="' + i + '"]').spectrum({
      preferredFormat: "hex",
      showInput: true,
      allowEmpty: true,
      clickoutFiresChange: true,
      showButtons: false,
      showAlpha: true,
      move: function (color) {
        // Assign color to the SVG element based on movement in the color selection tool
        $('[data-svg="' + i + '"]').find('g').css("fill", color);
        $('[data-svg="' + i + '"]').find('path').css("fill", color);

      }
    });
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
      sleeve: sleeveImprintAtrworkURL
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