$ = jQuery;

// Convert svg's to inline elements
force_inline_svg();

function artwork_init() {

  console.log('artwork init');

  artwork_display('front');

  $('.step_2_shirt_designs').fadeTo(100, .4);
  $('.step_2_shirt_designs').removeClass('selected');
  $('.step_2_shirt_designs:first').fadeTo(100, 1);
  $('.step_2_shirt_designs:first').addClass('selected');

  // Initialize color inputs
  color_input_init();

  $('.step_2_shirt_designs').unbind().click(function () {

    $('.step_2_shirt_designs').removeClass('selected');
    $('.step_2_shirt_designs').fadeTo(200, .4);
    $(this).fadeTo(100, 1);
    $(this).addClass('selected');

    let orientation = $(this).find('figure').attr('id');

    artwork_display(orientation);

  });

  // CHANGE ARTWORK LOCATION ON DROPDOWN VALUE CHANGE
  $('.imprint_location_dropdown').unbind().change(function () {
    apply_artwork_to_shirt(false, $(this).attr('name').split('-imprint_location')[0]);
  });


  // CLICK ARTWORK TO PLACE ON T-SHIRT
  $(".single_art img").unbind().click(function () {
    let artClone = $(this).clone();
    $(artClone).attr('data-artwork-id', $(this).parent().attr('data-artwork-id'));
    apply_artwork_to_shirt(artClone, $(this).closest('.single_art').attr('data-orientation'));
  });

  // CLICK ARTWORK TO PLACE ON T-SHIRT
  $(".single_art svg").unbind().click(function () {
    let artClone = $(this).clone();
    $(artClone).attr('data-artwork-id', $(this).parent().attr('data-artwork-id'));
    apply_artwork_to_shirt(artClone, $(this).closest('.single_art').attr('data-orientation'));
  });

}

/**
 * Convert svg image into an inline svg element so it can be manipulated with ease
 *
 * @return void
 *
 */
function force_inline_svg() {
  $('img.force-inline-svg').filter(function () {
    return this.src.match(/.*\.svg$/);
  }).each(function () {
    let $img = $(this);
    let imgID = $img.attr('id');
    let imgClass = $img.attr('class');
    let imgURL = $img.attr('src');
    let imgColor = $img.attr('data-color');

    $.get(imgURL, function (data) {
      // Get the SVG tag, ignore the rest
      let $svg = $(data).find('svg');

      // Add replaced image's ID to the new SVG
      if (typeof imgID !== 'undefined') {
        $svg = $svg.attr('id', imgID);
      }
      // Add replaced image's classes to the new SVG
      if (typeof imgClass !== 'undefined') {
        $svg = $svg.attr('class', imgClass + ' replaced-svg');
      }
      // Add the image url as a data attribute
      $svg.attr('data-img-url', imgURL);

      // If there is a color attribute, add it to the svg
      $svg.find('g').css('fill', imgColor);
      $svg.find('path').css('fill', imgColor);

      // Remove any invalid XML tags as per http://validator.w3.org
      $svg = $svg.removeAttr('xmlns:a');

      // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
      if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
        $svg.attr('viewBox', "0 0 " + $svg.attr('height') + " " + $svg.attr('width'));
      }

      // Replace image with new SVG
      $img.replaceWith($svg);

    }, 'xml');
  });

}

/**
 * Initialize the color input by adding data attributes to each artwork orientation
 *
 * @return void
 *
 */
function color_input_init() {
  let orientation;
  let colorInputSVG;
  let colorInputSwatch;
  let colorInputSelector;

  // Assign ID's to each img on product customization page
  $('.hometown_artwork .single_art').each(function (i) {

    orientation = $(this).attr('data-orientation');

    colorInputSVG = $(this).find('svg').attr('data-svg', i);

    colorInputSwatch = $(this).find('.hometown_color_swatch').attr('data-color-selector', i);

    colorInputSelector = $(this).find('.hometown_color_wheel input.color_input').attr('data-color-selector', i);

    console.log(orientation, colorInputSVG, colorInputSwatch, colorInputSelector);


    apply_color_to_svg(i, colorInputSVG, colorInputSwatch, colorInputSelector, orientation);
  });

}

/**
 * Apply the color to the svg from the color selector and from predefined palette swatch
 *
 * @param id - the data key value of the artwork
 * @param svg - the svg element relative to it's data key value
 * @param swatch - the color swatch relative to it's data key value
 * @param selector - the color selector relative to it's data key value
 * @param orientation - the orientation of the artwork, so we can change the cloned artwork
 *
 * @return void
 *
 */
function apply_color_to_svg(id, svg, swatch, selector, orientation) {
  let hexColor;
  // Color Swatches
  swatch.unbind().click(function () {
    let swatchColor = rgb2hex($(this).css("background-color"));

    // Give svg a color data value (hex)
    svg.attr('data-color-val', swatchColor);

    // Assign color to the SVG element
    svg.find('g').css("fill", swatchColor);
    svg.find('path').css("fill", swatchColor);

    // If artwork is on a shirt, change it's color too
    if ($('figure#' + orientation).find($('[data-svg="' + id + '"]')).length) {
      apply_artwork_to_shirt($(svg).clone(), $(svg).closest('.single_art').attr('data-orientation'));
    }

  });

  // Color Selector
  selector.spectrum({
    preferredFormat: "hex",
    showInput: true,
    clickoutFiresChange: true,
    showButtons: false,
    move: function (color) {

      hexColor = color.toHexString();

      // Give svg a color data value (hex)
      svg.attr('data-color-val', hexColor);

      // Assign color to the SVG element based on movement in the color selection tool
      svg.find('g').css("fill", hexColor);
      svg.find('path').css("fill", hexColor);

      // If artwork is on a shirt, change it's color too
      if ($('figure#' + orientation).find($('[data-svg="' + id + '"]')).length) {
        apply_artwork_to_shirt($(svg).clone(), $(svg).closest('.single_art').attr('data-orientation'));

      }

    }
  });

}

/**
 * Function to convert rgb format to a hex color
 *
 * @param orig - the original color
 *
 * @return string
 *
 */
function rgb2hex(orig){
  let rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
  return (rgb && rgb.length === 4) ? "#" +
    ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
    ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
    ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
}

/**
 * Apply artwork to the shirt
 *
 * @param artClone - the cloned image element that is placed on the shirt
 * @param shirtOrientation - the location of the artwork on the shirt
 *
 * @return void
 *
 */
function apply_artwork_to_shirt(artClone, shirtOrientation) {

  let imprintLocation = $("#" + shirtOrientation + "-imprint_location").val();

  if (imprintLocation != 0) {

    if (artClone === false) {
      artClone = $('.' + shirtOrientation + '-selected_art');
    }

    $('.' + shirtOrientation + '-selected_art').remove();

    artClone.removeClass('full_front mid_chest pocket full_back upper_back lower_back left_sleeve right_sleeve');

    if (imprintLocation === 'right_sleeve') {
      $('#' + shirtOrientation + ' img').addClass('reflect');
    } else {
      $('#' + shirtOrientation + ' img').removeClass('reflect');
    }

    $(artClone).addClass(shirtOrientation + '-selected_art');
    $(artClone).addClass(imprintLocation);
    $('#' + shirtOrientation).append(artClone);

  } else {

    $('#'+shirtOrientation+'-imprint_location').bounce({
      interval: 100,
      distance: 10,
      times: 5
    });

  }

}

/**
 * Save the image src and custom color of each image orientation to the user meta table
 *
 * @return void
 *
 */
function save_artwork_to_user_meta(uniqueCartKey) {
  let frontImgURL;
  let backImgURL;
  let sleeveImgURL;

  // Front image data
  if ($('figure#front').has('img.front-selected_art').length) {
    frontImgURL = $('figure#front img.front-selected_art').attr('src');
  } else if ($('figure#front').has('svg.front-selected_art').length) {
    frontImgURL = $('figure#front svg.front-selected_art').attr('data-img-url');
  } else {
    frontImgURL = null;
  }
  let frontImgColor = ($('figure#front .front-selected_art').attr('data-color-val') == null) ? 'No custom color' : $('figure#front .front-selected_art').attr('data-color-val');

  // Back Image data
  if ($('figure#back').has('img.back-selected_art').length) {
    backImgURL = $('figure#back img.back-selected_art').attr('src');
  } else if ($('figure#back').has('svg.back-selected_art').length) {
    backImgURL = $('figure#back svg.back-selected_art').attr('data-img-url');
  } else {
    backImgURL = null;
  }
  let backImgColor = ($('figure#back .back-selected_art').attr('data-color-val') == null) ? 'No custom color' : $('figure#back .back-selected_art').attr('data-color-val');

  // Sleeve Image data
  if ($('figure#sleeve').has('img.sleeve-selected_art').length) {
    sleeveImgURL = $('figure#sleeve img.sleeve-selected_art').attr('src');
  } else if ($('figure#sleeve').has('svg.sleeve-selected_art').length) {
    sleeveImgURL = $('figure#sleeve svg.sleeve-selected_art').attr('data-img-url');
  } else {
    sleeveImgURL = null;
  }
  let sleeveImgColor = ($('figure#sleeve .sleeve-selected_art').attr('data-color-val') == null) ? 'No custom color' : $('figure#sleeve .sleeve-selected_art').attr('data-color-val');

  let product_id = $("#continue_3").data('product-id');
  let variation_id = $("#continue_3").data('product-variant-id');

  let frontArtworkID = parseInt($('figure#front .front-selected_art').attr('data-artwork-id'));
  let backArtworkID = parseInt($('figure#back .back-selected_art').attr('data-artwork-id'));
  let sleeveArtworkID = parseInt($('figure#sleeve .sleeve-selected_art').attr('data-artwork-id'));

  let artworkData = {
    "action":           "hometown_save_imprint_artwork",
    "product_id":       product_id,
    "variation_id":     variation_id,
    "unique_cart_key":  uniqueCartKey,
    "frontURL":         frontImgURL,
    "frontColor":       frontImgColor,
    "frontArtworkID":   frontArtworkID,
    "backURL":          backImgURL,
    "backColor":        backImgColor,
    "backArtworkID":    backArtworkID,
    "sleeveURL":        sleeveImgURL,
    "sleeveColor":      sleeveImgColor,
    "sleeveArtworkID":  sleeveArtworkID
  };

  $.post( ha_artwork_config.ajaxurl, artworkData, function(result) {
  } );

}


function artwork_display(orientation) {

  $('.shirt_artwork').hide();
  $('.artwork-' + orientation).show();

}



(function($){
  $.fn.bounce = function(settings) {
    if(typeof settings.interval == 'undefined'){
      settings.interval = 100;
    }

    if(typeof settings.distance == 'undefined'){
      settings.distance = 10;
    }

    if(typeof settings.times == 'undefined'){
      settings.times = 4;
    }

    if(typeof settings.complete == 'undefined'){
      settings.complete = function(){};
    }

    $(this).css('position','relative');

    for(var iter=0; iter<(settings.times+1); iter++){
      $(this).animate({ top:((iter%2 == 0 ? settings.distance : settings.distance * -1)) }, settings.interval);
    }

    $(this).animate({ top: 0}, settings.interval, settings.complete);
  };
})(jQuery);