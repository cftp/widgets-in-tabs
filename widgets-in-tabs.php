<?php
/*
 * Plugin Name: Widgets In Tabs
 * Plugin URI: http://wordpress.org/plugins/widgets-in-tabs/
 * Description: Group any number of widgets into one tabbed, light, and beautiful widget.
 * Author: Anas H. Sulaiman
 * Version: 0.5
 * Author URI: http://ahs.pw/
 * Text Domain: wit
 * Domain Path: /languages/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

define( 'WIT_VERSION', '0.5' );

/**
 * Register the widget for use in Appearance -> Widgets
 */
add_action('widgets_init', 'register_wit');
function register_wit() {
	 register_sidebar(array(
        'id' => 'wit_area',
        'name' => __('Widgets In Tabs Area', 'wit'),
        'description'   => __('Add widgets here to show them in WIT Widget. If you put WIT widget here, bad things will happen!', 'wit'),
        'before_widget' => '<li id="%1$s" class="%2$s wit-tab-content">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class = "wit-tab-title">',
        'after_title' => '</h3>',));

	register_widget('Widgets_In_Tabs');
}

class Widgets_In_Tabs extends WP_Widget {

	function __construct() {
		parent::__construct(
			'Widgets_In_Tabs',
			__('Widgets In Tabs', 'wit'),
			array( 'description' => __( 'Group any number of widgets into one tabbed, light, and beautiful widget.', 'wit' ) )
		);

		if ( is_active_widget( false, false, $this->id_base ) && !is_admin()) {
			add_action( 'wp_print_styles', array( $this, 'enqueue_style' ) );
			add_action( 'wp_print_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}


	public function enqueue_style() {
		wp_register_style('wit', plugins_url( 'wit-all.min.css', __FILE__ ), array(), WIT_VERSION);
		wp_enqueue_style( 'wit' );
	}

	public function enqueue_scripts() {
		wp_register_script('wit', plugins_url( 'wit-all.min.js', __FILE__ ), array('jquery'), WIT_VERSION, true);
		wp_enqueue_script( 'wit' );
	}

	/**
	 * Front-end display of Widgets In Tabs.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$options = array(
			'interval' => (empty($instance['interval']) ? '0' : $instance['interval']),
			'string_untitled' => __('Untitled', 'wit')
			);
		wp_localize_script('wit', 'WIT_OPTIONS', $options);

		$title = __('Widgets In Tabs', 'wit');

		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		echo '<ul class = "wit-tab-container">';
		dynamic_sidebar('wit_area');
		echo '</ul>';
		echo $args['after_widget'];
	}

	/**
	 * Back-end Widgets In Tabs form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$defaults = array('interval' => '0');
		$instance = wp_parse_args((array) $instance, $defaults);

		?>
		<p><?php _e('All widgets added to Widgets In Tabs Area will appear as tabs in place of this widget.', 'wit' ); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'interval' ); ?>"><?php _e( 'Animation Interval in seconds: (0 = disable)' , 'wit'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'interval' ); ?>" name="<?php echo $this->get_field_name( 'interval' ); ?>" type="text" value="<?php echo esc_attr( $instance['interval'] ); ?>" />
		</p>
		<?php
	}

	/**
	 * Sanitize Widgets In Tabs form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$intervali = (int)$new_instance['interval'];

		// make sure the interval string is an integer && is greater than or equal to zero
		if (((string)$intervali == $new_instance['interval']) && $intervali >= 0)
			$instance['interval'] =  $new_instance['interval'];
		else 
			$instance['interval'] = '0';

		return $instance;
	}

} // class Widgets_In_Tabs

add_action( 'plugins_loaded', 'wit_load_textdomain' );
function wit_load_textdomain() {
	load_plugin_textdomain( 'wit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function wit_go_to_widgets_link($actions) {
	return array_merge(
		array( 'settings' => sprintf( '<a href="%s">%s</a>', 'widgets.php', __( 'Go to Widgets', 'wit' ) ) ),
		$actions
	);
	return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wit_go_to_widgets_link' );

function wit_load_admin_style($hook) {
	if ('widgets.php' != $hook)
		return;
	wp_register_style('wit_admin', plugins_url( 'wit-admin.min.css', __FILE__ ), array(), WIT_VERSION);
	wp_enqueue_style( 'wit_admin' );
}
add_action('admin_enqueue_scripts', 'wit_load_admin_style');


