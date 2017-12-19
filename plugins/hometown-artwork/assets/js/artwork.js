$=jQuery;

$(document).ready(function() {artwork_init();});

function artwork_init() {


  $('.step_2_shirt_designs figure').unbind().click(function() {
    $('.step_2_shirt_designs').fadeTo(200, .4);
    $(this).parent().fadeTo(100, 1);

    let orientation = $(this).attr('id');

    artwork_display(orientation);

  });

  $('.single_art').unbind().click(function() {

    // let positioning =

    // $(this).

  });

}

function artwork_display(orientation) {

  $('.hometown_artwork').hide();
  $('.artwork-'+orientation).show();

}