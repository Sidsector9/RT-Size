<?php
/*
Plugin Name: RT Size
Plugin URI:  https://github.com/Sidsector9/euclid-mam/tree/euclid-mam-v2
Description: A simple plugin to add a custom image size
Version:     1.0
Author:      Siddharth Thevaril
Author URI:  https://github.com/Sidsector9/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: rtsize
*/

class RT_Size {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'rt_size_setup' ) );
	}

	public function rt_size_setup() {
		add_settings_section( 'rt-size-section', 'RT Size', array( $this, 'rt_settings_section_callback' ), 'media' );
		add_settings_field( 'rt-size-field', 'Custom dimension', array( $this, 'rt_settings_field_callback' ), 'media', 'rt-size-section', array( 'label_for' => 'rt-size-field' ) );
		register_setting( 'media', 'rt-size-field' );
		add_filter( 'image_size_names_choose', array( $this, 'rt_size_in_admin' ) );
	}

	public function rt_settings_section_callback() {
		echo '<p>Add a custom size for image uploads lols</p>';
	}

	public function rt_settings_field_callback() {
		?>
		<label for="rt-size-field">Width</label>
		<input 
			type="number" 
			name="rt-size-field[]" 
			class="small-text" 
			value="<?php echo esc_html( get_option( 'rt-size-field' )[0] ) ?>"
		>

		<label for="rt-size-field">Height</label>
		<input 
			type="number" 
			name="rt-size-field[]" 
			class="small-text" 
			value="<?php echo esc_html( get_option( 'rt-size-field' )[1] ) ?>"
		>
		<?php
	}

	public function rt_add_image_size() {
		if ( function_exists( 'add_image_size' ) ) {
			$dimensions = get_option( 'rt-size-field' );
			if ( ! ( empty( $dimensions[0] ) || empty( $dimensions[1] ) ) ) {
				add_image_size( 'rt-image-size', $dimensions[0], $dimensions[1], false );
			}
		}
	}

	public function rt_size_in_admin( $sizes ) {
	    return array_merge( $sizes, array(
	        'rt-image-size' => __( 'RT size' ),
	    ) );
	}
}

if ( class_exists( 'RT_Size' ) ) {
	$p = new RT_Size();
	$p->rt_add_image_size();
}
