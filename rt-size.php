<?php
/**
 * Plugin Name: RT Size
 * Plugin URI:  https://github.com/Sidsector9/euclid-mam/tree/euclid-mam-v2
 * Description: A simple plugin to add a custom image size
 * Version:     1.0
 * Author:      Siddharth Thevaril
 * Author URI:  https://github.com/Sidsector9/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rtsize
 *
 * @package WordPress
 */

if ( ! class_exists( 'RT_Size' ) ) {

	/**
	 * RT Size class
	 *
	 * This class adds another image size under
	 * Settings > Media
	 */
	class RT_Size {
		/**
		 * Constructor function.
		 *
		 * The constructor is used to call 1 action hook
		 * that deals with the setup of the RT Size custom
		 * image size
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'rt_size_setup' ) );
		}

		/**
		 * Creates Section, Field and Setting.
		 *
		 * This function hooks functions that creates a section
		 * on the Media Admin Page and adds two number fields
		 * and then registers the setting.
		 */
		public function rt_size_setup() {
			add_settings_section( 'rt-size-section', 'RT Size', array( $this, 'rt_settings_section_callback' ), 'media' );
			add_settings_field( 'rt-size-field', 'Custom dimension', array( $this, 'rt_settings_field_callback' ), 'media', 'rt-size-section', array( 'label_for' => 'rt-size-field' ) );
			register_setting( 'media', 'rt-size-field' );
			add_filter( 'image_size_names_choose', array( $this, 'rt_size_in_admin' ) );
		}

		/**
		 * A function to display short description.
		 */
		public function rt_settings_section_callback() {
			echo '<p>Add a custom size for image uploads lols</p>';
		}

		/**
		 * This creates two number input fields that
		 * take Width and Height parameneters.
		 */
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

		/**
		 * Registers a new image size based on the input values
		 * set by the user.
		 */
		public function rt_add_image_size() {
			if ( function_exists( 'add_image_size' ) ) {
				$dimensions = get_option( 'rt-size-field' );
				if ( ! ( empty( $dimensions[0] ) || empty( $dimensions[1] ) ) ) {
					add_image_size( 'rt-image-size', $dimensions[0], $dimensions[1], false );
				}
			}
		}

		/**
		 * Adds the new image size to the WordPress
		 * Admin Page.
		 *
		 * @param Array $sizes holds all the different
		 * image sizes.
		 */
		public function rt_size_in_admin( $sizes ) {
		    return array_merge( $sizes, array(
		        'rt-image-size' => __( 'RT size' ),
		    ) );
		}
	}
}

if ( class_exists( 'RT_Size' ) ) {
	$p = new RT_Size();
	$p->rt_add_image_size();
}
