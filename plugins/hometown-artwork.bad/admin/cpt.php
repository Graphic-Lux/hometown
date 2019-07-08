<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

// Register Custom Post Type
function artwork_post_type() {

$labels = array(
'name'                  => _x( 'Artwork', 'Post Type General Name', 'text_domain' ),
'singular_name'         => _x( 'Artwork', 'Post Type Singular Name', 'text_domain' ),
'menu_name'             => __( 'Artwork', 'text_domain' ),
'name_admin_bar'        => __( 'Artwork', 'text_domain' ),
'archives'              => __( 'Artwork Archives', 'text_domain' ),
'attributes'            => __( 'Artwork Attributes', 'text_domain' ),
'parent_artwork_colon'     => __( 'Parent Artwork:', 'text_domain' ),
'all_artwork'             => __( 'All artworks', 'text_domain' ),
'add_new_artwork'          => __( 'Add New Artwork', 'text_domain' ),
'add_new'               => __( 'Add New', 'text_domain' ),
'new_artwork'              => __( 'New Artwork', 'text_domain' ),
'edit_artwork'             => __( 'Edit Artwork', 'text_domain' ),
'update_artwork'           => __( 'Update Artwork', 'text_domain' ),
'view_artwork'             => __( 'View Artwork', 'text_domain' ),
'view_artwork'            => __( 'View Artwork', 'text_domain' ),
'search_artwork'          => __( 'Search Artwork', 'text_domain' ),
'not_found'             => __( 'Not found', 'text_domain' ),
'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
'featured_image'        => __( 'Featured Image', 'text_domain' ),
'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
'insert_into_artwork'      => __( 'Insert into artwork', 'text_domain' ),
'uploaded_to_this_artwork' => __( 'Uploaded to this artwork', 'text_domain' ),
'artwork_list'            => __( 'Artwork list', 'text_domain' ),
'artwork_list_navigation' => __( 'Artwork list navigation', 'text_domain' ),
'filter_artwork_list'     => __( 'Filter artwork list', 'text_domain' ),
);
$args = array(
'label'                 => __( 'Artwork', 'text_domain' ),
'description'           => __( 'Add, Edit and Delete artwork for custom t-shirt creation.', 'text_domain' ),
'labels'                => $labels,
//'supports'              => array( 'title', 'thumbnail', 'custom-fields' ),
'supports'              => array( 'title', 'thumbnail' ),
'taxonomies'            => array( 'category', 'post_tag' ),
'hierarchical'          => false,
'public'                => true,
'show_ui'               => true,
'show_in_menu'          => true,
'menu_position'         => 30,
'menu_icon'             => 'dashicons-art',
'show_in_admin_bar'     => true,
'show_in_nav_menus'     => true,
'can_export'            => true,
'has_archive'           => true,
'exclude_from_search'   => true,
'publicly_queryable'    => true,
'capability_type'       => 'page',
);
register_post_type( 'artwork', $args );

register_taxonomy( 'post_tag', array( 'Artwork' ) );

}
add_action( 'init', 'artwork_post_type', 0 );




if ( !class_exists('hometownArtworkMetaFields') ) {

  class hometownArtworkMetaFields {
    /**
     * @var  string  $prefix  The prefix for storing custom fields in the postmeta table
     */
    var $prefix = 'hamf_';
    /**
     * @var  array  $postTypes  An array of public custom post types, plus the standard "post" and "page" - add the custom types you want to include here
     */
    var $postTypes = array( "artwork" );
    /**
     * @var  array  $customFields  Defines the custom fields available
     */
    var $customFields = array(
        array(
            "name"          => "artwork_price",
            "title"         => "Artwork Price",
            "description"   => "",
            "type"          => "text",
            "scope"         =>   array( "artwork" ),
            "capability"    => "edit_pages",
        )
    );
    /**
     * PHP 4 Compatible Constructor
     */
    function hometownArtworkMetaFields() { $this->__construct(); }
    /**
     * PHP 5 Constructor
     */
    function __construct() {
      add_action( 'admin_menu', array( $this, 'createCustomFields' ) );
            add_action( 'save_post', array( $this, 'saveCustomFields' ), 1, 2 );
            // Comment this line out if you want to keep default custom fields meta box
            add_action( 'do_meta_boxes', array( $this, 'removeDefaultCustomFields' ), 10, 3 );
        }
    /**
     * Remove the default Custom Fields meta box
     */
    function removeDefaultCustomFields( $type, $context, $post ) {
      foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
        foreach ( $this->postTypes as $postType ) {
          remove_meta_box( 'postcustom', $postType, $context );
        }
      }
    }
    /**
     * Create the new Custom Fields meta box
     */
    function createCustomFields() {
      if ( function_exists( 'add_meta_box' ) ) {
        foreach ( $this->postTypes as $postType ) {
          add_meta_box( 'my-custom-fields', 'Additional Artwork Settings', array( $this, 'displayCustomFields' ), $postType, 'normal', 'high' );
                }
      }
    }
    /**
     * Display the new Custom Fields meta box
     */
    function displayCustomFields() {
      global $post;
      ?>
      <div class="form-wrap">
        <?php
        wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );
        foreach ( $this->customFields as $customField ) {
          // Check scope
          $scope = $customField[ 'scope' ];
          $output = false;
          foreach ( $scope as $scopeItem ) {
            switch ( $scopeItem ) {
              default: {
                if ( $post->post_type == $scopeItem )
                  $output = true;
                break;
              }
            }
            if ( $output ) break;
          }
          // Check capability
          if ( !current_user_can( $customField['capability'], $post->ID ) )
            $output = false;
          // Output if allowed
          if ( $output ) { ?>
            <div class="form-field form-required">
              <?php
              switch ( $customField[ 'type' ] ) {
                case "checkbox": {
                  // Checkbox
                  echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:inline;"><b>' . $customField[ 'title' ] . '</b></label>nbsp;nbsp;';
                  echo '<input type="checkbox" name="' . $this->prefix . $customField['name'] . '" id="' . $this->prefix . $customField['name'] . '" value="yes"';
                  if ( get_post_meta( $post->ID, $this->prefix . $customField['name'], true ) == "yes" )
                    echo ' checked="checked"';
                  echo '" style="width: auto;" />';
                  break;
                }
                case "textarea":
                case "wysiwyg": {
                  // Text area
                  echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                  echo '<textarea name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" columns="30" rows="3">' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '</textarea>';
                  // WYSIWYG
                  if ( $customField[ 'type' ] == "wysiwyg" ) { ?>
                    <script type="text/javascript">
                      jQuery( document ).ready( function() {
                        jQuery( "<?php echo $this->prefix . $customField[ 'name' ]; ?>" ).addClass( "mceEditor" );
                        if ( typeof( tinyMCE ) == "object"  typeof( tinyMCE.execCommand ) == "function" ) {
                          tinyMCE.execCommand( "mceAddControl", false, "<?php echo $this->prefix . $customField[ 'name' ]; ?>" );
                        }
                      });
                    </script>
                  <?php }
                  break;
                }
                default: {
                  // Plain text field
                  echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                  echo '<input type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
                  break;
                }
              }
              ?>
              <?php if ( $customField[ 'description' ] ) echo '<p>' . $customField[ 'description' ] . '</p>'; ?>
            </div>
            <?php
          }
        } ?>
      </div>
      <?php
    }
    /**
     * Save the new Custom Fields values
     */
    function saveCustomFields( $post_id, $post ) {
      if ( !isset( $_POST[ 'my-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
        return;
      if ( !current_user_can( 'edit_post', $post_id ) )
        return;
      if ( ! in_array( $post->post_type, $this->postTypes ) )
        return;
      foreach ( $this->customFields as $customField ) {
        if ( current_user_can( $customField['capability'], $post_id ) ) {
          if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
            $value = $_POST[ $this->prefix . $customField['name'] ];
            // Auto-paragraphs for any WYSIWYG
            if ( $customField['type'] == "wysiwyg" ) $value = wpautop( $value );
            update_post_meta( $post_id, $this->prefix . $customField[ 'name' ], $value );
          } else {
            delete_post_meta( $post_id, $this->prefix . $customField[ 'name' ] );
          }
                }
      }
    }

  } // End Class

} // End if class exists statement

// Instantiate the class
if ( class_exists('hometownArtworkMetaFields') ) {
  $hometownArtworkMetaFields_var = new hometownArtworkMetaFields();
}