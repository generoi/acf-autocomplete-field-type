<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_autocomplete_field') ) :


class acf_field_autocomplete_field extends acf_field {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	1.0.0
	*/
	
	function __construct( $settings )
	{
		// vars
		$this->name = 'autocomplete';
		$this->label = __('Autocomplete');
		$this->category = __("Basic",'acf-autocomplete'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field. 
			// This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
			//'preview_size' => 'thumbnail'
		);
		
		
		// do not delete!
    	parent::__construct();

		// call wp ajax action 
 		add_action( 'wp_ajax_autocomplete_ajax', array( $this, 'autocomplete_ajax_callback' ) );
		
    	// settings
		$this->settings = $settings;

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
	        SELECT DISTINCT meta_value FROM {$wpdb->postmeta}
	        WHERE meta_key = '%s'
	        AND meta_value LIKE %s
			", $_REQUEST['field_key'], '%'.$_REQUEST['request'].'%' ) );

		echo json_encode($results);
		
		wp_die(); 
		
	}
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	1.0.0
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// key is needed in the field names to correctly save the data
		$key = $field['name'];
		
		
		// Create Field Options HTML
		?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Preview Size",'acf-autocomplete'); ?></label>
		<p class="description"><?php _e("Thumbnail is advised",'acf-autocomplete'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][preview_size]',
			'value'		=>	$field['preview_size'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'thumbnail' => __('Thumbnail', 'acf-autocomplete'),
				'something_else' => __('Something Else', 'acf-autocomplete'),
			)
		));
		
		?>
	</td>
</tr>
		<?php
		
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	1.0.0
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
		<div>
			
		</div>
		<?php
	}
	
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	1.0.0
	*/

	function input_admin_enqueue_scripts()
	{

		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
		// register & include JS
		wp_register_script('acf-autocomplete', "{$url}assets/js/input.js", array('acf-input',  'jquery-ui-autocomplete'), $version);
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