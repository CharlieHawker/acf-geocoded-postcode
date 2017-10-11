<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_geocoded_postcode') ) :


class acf_field_geocoded_postcode extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'geocoded-postcode';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Geocoded Postcode', 'acf-geocoded-postcode');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'basic';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'font_size'	=> 14,
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('geocoded-postcode', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-geocoded-postcode'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();
    	
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {

	}
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
		
		/*
		*  Create a simple text input using the 'font_size' setting.
		*/
		
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
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function input_admin_enqueue_scripts() {
		
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
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	function load_value( $value, $post_id, $field ) {
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
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	function update_value( $value, $post_id, $field ) {
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
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	
	function validate_value( $valid, $value, $field, $input ){
		$result = json_decode(file_get_contents('https://api.postcodes.io/postcodes/' . $value . '/validate'));
		if (!$result->result) {
			$valid = __('The postcode is invalid', 'acf-geocoded-postcode');
		}
		return $valid;
	}
}


// initialize
new acf_field_geocoded_postcode( $this->settings );


// class_exists check
endif;

?>