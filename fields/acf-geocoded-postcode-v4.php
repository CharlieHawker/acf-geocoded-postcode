<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_geocoded_postcode') ) :


class acf_field_geocoded_postcode extends acf_field {
  
  // vars
  var $settings, // will hold info such as dir / path
    $defaults; // will hold default field options
    
    
  /*
  *  __construct
  *
  *  Set name / label needed for actions / filters
  *
  *  @since 3.6
  *  @date  23/01/13
  */
  
  function __construct( $settings )
  {
    // vars
    $this->name = 'geocoded-postcode';
    $this->label = __('Geocoded Postcode');
    $this->category = __("Basic",'acf'); // Basic, Content, Choice, etc
    $this->defaults = array(
      // add default here to merge into your field. 
      // This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
      //'preview_size' => 'thumbnail'
    );
    
    
    // do not delete!
      parent::__construct();
      
      
      // settings
    $this->settings = $settings;

  }
  
  
  /*
  *  create_options()
  *
  *  Create extra options for your field. This is rendered when editing a field.
  *  The value of $field['name'] can be used (like below) to save extra data to the $field
  *
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $field  - an array holding all the field's data
  */
  
  function create_options( $field )
  {
    // defaults?
    /*
    $field = array_merge($this->defaults, $field);
    */
  }
  
  
  /*
  *  create_field()
  *
  *  Create the HTML interface for your field
  *
  *  @param $field - an array holding all the field's data
  *
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */
  
  function create_field( $field )
  {
    // defaults?
    /*
    $field = array_merge($this->defaults, $field);
    */
    
    // perhaps use $field['preview_size'] to alter the markup?
    
    
    // create Field HTML
    ?>
    <div class="geocoded-postcode">
      <input type="text" class="geocoded-postcode__field" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>" placeholder="NW5 1LB" maxlength="8" />
      <div class="geocoded-postcode__invalid-message">
        <?php echo __('The postcode you have entered is invalid'); ?>
      </div>
    </div>
    <?php
  }
  
  
  /*
  *  input_admin_enqueue_scripts()
  *
  *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
  *  Use this action to add CSS + JavaScript to assist your create_field() action.
  *
  *  $info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */

  function input_admin_enqueue_scripts()
  {
    // Note: This function can be removed if not used
    
    
    // vars
    $url = $this->settings['url'];
    $version = $this->settings['version'];
    
    
    // register & include JS
    wp_register_script( 'acf-input-geocoded-postcode', "{$url}assets/js/input.js", array('acf-input'), $version );
    wp_enqueue_script('acf-input-geocoded-postcode');
    
    
    // register & include CSS
    wp_register_style( 'acf-input-geocoded-postcode', "{$url}assets/css/input.css", array('acf-input'), $version );
    wp_enqueue_style('acf-input-geocoded-postcode');
    
  }
  
  
  /*
  *  input_admin_head()
  *
  *  This action is called in the admin_head action on the edit screen where your field is created.
  *  Use this action to add CSS and JavaScript to assist your create_field() action.
  *
  *  @info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */

  function input_admin_head()
  {
    // Note: This function can be removed if not used
  }
  
  
  /*
  *  field_group_admin_enqueue_scripts()
  *
  *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
  *  Use this action to add CSS + JavaScript to assist your create_field_options() action.
  *
  *  $info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */

  function field_group_admin_enqueue_scripts()
  {
    // Note: This function can be removed if not used
  }

  
  /*
  *  field_group_admin_head()
  *
  *  This action is called in the admin_head action on the edit screen where your field is edited.
  *  Use this action to add CSS and JavaScript to assist your create_field_options() action.
  *
  *  @info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */

  function field_group_admin_head()
  {
    // Note: This function can be removed if not used
  }


  /*
  *  load_value()
  *
    *  This filter is applied to the $value after it is loaded from the db
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $value - the value found in the database
  *  @param $post_id - the $post_id from which the value was loaded
  *  @param $field - the field array holding all the field options
  *
  *  @return  $value - the value to be saved in the database
  */
  
  function load_value( $value, $post_id, $field )
  {
    // Note: This function can be removed if not used
    if (strlen($value)) {
      $json = json_decode($value);
      $value = $json->postcode;
    }
    return $value;
  }
  
  
  /*
  *  update_value()
  *
  *  This filter is applied to the $value before it is updated in the db
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $value - the value which will be saved in the database
  *  @param $post_id - the $post_id of which the value will be saved
  *  @param $field - the field array holding all the field options
  *
  *  @return  $value - the modified value
  */
  
  function update_value( $value, $post_id, $field )
  {
    $value = array('postcode' => $value);
    if (strlen($value['postcode'])) {
      // Lookup the postcode
      $result = json_decode(file_get_contents('https://api.postcodes.io/postcodes/' . $value['postcode']));
      if ($result->status == 200) {
        $value['latitude'] = $result->result->latitude;
        $value['longitude'] = $result->result->longitude;
      }
    }
    return json_encode($value);
  }
  
  
  /*
  *  format_value()
  *
  *  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $value  - the value which was loaded from the database
  *  @param $post_id - the $post_id from which the value was loaded
  *  @param $field  - the field array holding all the field options
  *
  *  @return  $value  - the modified value
  */
  
  function format_value( $value, $post_id, $field )
  {
    // defaults?
    /*
    $field = array_merge($this->defaults, $field);
    */
    
    // perhaps use $field['preview_size'] to alter the $value?
    
    
    // Note: This function can be removed if not used
    return $value;
  }
  
  
  /*
  *  format_value_for_api()
  *
  *  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $value  - the value which was loaded from the database
  *  @param $post_id - the $post_id from which the value was loaded
  *  @param $field  - the field array holding all the field options
  *
  *  @return  $value  - the modified value
  */
  
  function format_value_for_api( $value, $post_id, $field )
  {
    // defaults?
    /*
    $field = array_merge($this->defaults, $field);
    */
    
    // perhaps use $field['preview_size'] to alter the $value?
    
    
    // Note: This function can be removed if not used
    return $value;
  }
  
  
  /*
  *  load_field()
  *
  *  This filter is applied to the $field after it is loaded from the database
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $field - the field array holding all the field options
  *
  *  @return  $field - the field array holding all the field options
  */
  
  function load_field( $field )
  {
    // Note: This function can be removed if not used
    return $field;
  }
  
  
  /*
  *  update_field()
  *
  *  This filter is applied to the $field before it is saved to the database
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $field - the field array holding all the field options
  *  @param $post_id - the field group ID (post_type = acf)
  *
  *  @return  $field - the modified field
  */

  function update_field( $field, $post_id )
  {
    // Note: This function can be removed if not used
    return $field;
  }

}


// initialize
new acf_field_geocoded_postcode( $this->settings );


// class_exists check
endif;

?>