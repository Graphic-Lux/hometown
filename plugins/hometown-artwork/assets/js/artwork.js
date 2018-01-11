$=jQuery;

function artwork_init() {

  // console.log('artwork init');

  $('.product_image_wrap.subtype').fadeIn();
  $('.step_2_shirt_designs').fadeTo(100, .4);
  $('.step_2_shirt_designs figure').removeClass('selected');
  $('.step_2_shirt_designs:first').fadeTo(100, 1);
  $('.step_2_shirt_designs figure:first').addClass('selected');



  $('.step_2_shirt_designs figure').unbind().click(function() {
    $('.step_2_shirt_designs').fadeTo(200, .4);
    $('.step_2_shirt_designs').removeClass('selected');
    $(this).parent().fadeTo(100, 1);
    $(this).addClass('selected');

    let orientation = $(this).attr('id');

    artwork_display(orientation);

  });



  // CHANGE ARTWORK LOCATION ON DROPDOWN VALUE CHANGE
  $('.imprint_location_dropdown').unbind().change(function() {
    apply_artwork_to_shirt(false, $(this).attr('name').split('-imprint_location')[0]);
  });


  // CLICK ARTWORK TO PLACE ON T-SHIRT
  $(".single_art img").unbind().click(function() {
      apply_artwork_to_shirt($(this).clone(), $(this).parent().parent().attr('class').split('artwork-')[1]);
  });



}


function apply_artwork_to_shirt(artClone, shirtOrientation) {

  let imprintLocation = $("#"+shirtOrientation+"-imprint_location").val();

  console.log(imprintLocation, artClone, shirtOrientation);

  if (imprintLocation != 0) {

    if (artClone === false) {
      artClone = $('.'+shirtOrientation+'-selected_art')[0];
    }

    $(artClone).removeClass('full_front mid_chest pocket full_back upper_back lower_back left_sleeve right_sleeve');
    $(artClone).remove();

    if (imprintLocation === 'right_sleeve') {
      $('#'+shirtOrientation+' img').addClass('reflect');
    } else {
      $('#'+shirtOrientation+' img').removeClass('reflect');
    }

    $(artClone).addClass(shirtOrientation+'-selected_art');
    $(artClone).addClass(imprintLocation);
    $('#'+shirtOrientation).append(artClone);

  } else {

    alert('Please select an imprint location for the artwork using the dropdown.');

  }

}


function artwork_display(orientation) {

  $('.hometown_artwork').hide();
  $('.artwork-'+orientation).show();

}