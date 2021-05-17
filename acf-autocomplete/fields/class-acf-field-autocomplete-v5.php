<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_autocomplete_field') ) :


class acf_field_autocomplete_field extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'autocomplete';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Autocomplete', 'acf-autocomplete');
		
		
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
		*  var message = acf._e('FIELD_NAME', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-autocomplete'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();

		// call wp ajax action 
		add_action( 'wp_ajax_autocomplete_ajax', array( $this, 'autocomplete_ajax_callback' ) );
    	
	}
	
	/*
	* 
	*  Get request field result
	*  
	*/
	public function autocomplete_ajax_callback() {
		
		global $wpdb;
		
		$results = array();
        
        $results = $wpdb->get_col( $wpdb->prepare( "
         SELECT DISTINCT postmeta2.meta_value FROM $wpdb->postmeta as postmeta1, $wpdb->postmeta as postmeta2 WHERE postmeta1.meta_value = '%s' AND postmeta1.meta_key = CONCAT( '_', postmeta2.meta_key ) AND postmeta2.meta_value LIKE %s",
         $_REQUEST['field_key'],
         '%' . $_REQUEST['request'] . '%'
         ) );
                
		echo json_encode($results);

		wp_die(); 
		
	}

	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	1.0.0
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		
		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Font Size','acf-autocomplete'),
			'instructions'	=> __('Customise the input font size','acf-autocomplete'),
			'type'			=> 'number',
			'name'			=> 'font_size',
			'prepend'		=> 'px',
		));

	}
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	1.0.0
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
		
		
		/*
		*  Review the data of $field.
		*  This will show what data is available
		*/
		
		/*
		*  Create a simple text input using the 'font_size' setting.
		*/
		
		?>
		<input type="text" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>" style="font-size:<?php echo $field['font_size'] ?>px;" />
		<?php
	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/


	function input_admin_enqueue_scripts() {
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
		// register & include JS
		wp_register_script('acf-autocomplete', "{$url}assets/js/input.js", array('acf-input', 'jquery-ui-autocomplete'), $version);
		wp_enqueue_script('acf-autocomplete');
		
		
		// register & include CSS
		wp_register_style('acf-autocomplete', "{$url}assets/css/input.css", array('acf-input'), $version);
		wp_enqueue_style('acf-autocomplete');
		
	}
	
}


// initialize
new acf_field_autocomplete_field( $this->settings );


// class_exists check
endif;

?>