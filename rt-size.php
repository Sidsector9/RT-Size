<?php
/**
 * Plugin Name: RT Size
 * Plugin URI:  https://github.com/Sidsector9/RT-Size/blob/non-anonymous/rt-size.php
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
			add_filter( 'image_size_names_choose', array( $this, 'rt_size_in_media_popup' ) );
		}

		/**
		 * A function to display short description.
		 */
		public function rt_settings_section_callback() {
			printf( '<p>%s</p>', esc_html__( 'Add a custom size for image uploads', 'rtsize' ) );
		}

		/**
		 * This creates two number input fields that
		 * take Width and Height parameneters.
		 */
		public function rt_settings_field_callback() {
			$dimensions = get_option( 'rt-size-field' );
			$width = ! empty( $dimensions[0] ) ? $dimensions[0] : null;
			$height = ! empty( $dimensions[1] ) ? $dimensions[1] : null;
			?>
			<label for="rt-size-field"><?php echo esc_html__( 'Width', 'rtsize' ) ?></label>
			<input 
				type="number" 
				name="rt-size-field[]" 
				class="small-text" 
				value="<?php if ( ! is_null( $width ) ) { echo esc_attr( $width ); } ?>"
			>

			<label for="rt-size-field"><?php echo esc_html__( 'Height', 'rtsize' ) ?></label>
			<input 
				type="number" 
				name="rt-size-field[]" 
				class="small-text" 
				value="<?php if ( ! is_null( $height ) ) { echo esc_attr( $height ); } ?>"
			>
			<?php
		}

		/**
		 * Registers a new image size based on the input values
		 * set by the user.
		 *
		 * @since v1.0
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
		 *
		 * @since v1.0
		 */
		public function rt_size_in_media_popup( $sizes ) {
		    return array_merge( $sizes, array(
		        'rt-image-size' => __( 'RT size' ),
		    ) );
		}
	}
}

if ( class_exists( 'RT_Size' ) ) {
	global $rt_size;
	$rt_size = new RT_Size();
	$rt_size->rt_add_image_size();
}
