<?php
/*
 * Plugin Name: Widgets In Tabs
 * Plugin URI: http://wordpress.org/plugins/widgets-in-tabs/
 * Description: Group any number of widgets into one tabbed, light, and beautiful widget.
 * Author: Anas H. Sulaiman
 * Version: 0.1
 * Author URI: http://ahs.pw/
 * Text Domain: wit
 * Domain Path: /languages/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

define( 'WIT_VERSION', '0.1' );

/**
 * Register the widget for use in Appearance -> Widgets
 */
add_action('widgets_init', 'register_widgets_in_tabs');
function register_widgets_in_tabs() {
	 register_sidebar(array(
        'id' => 'widgets_in_tabs_area',
        'name' => __('Widgets In Tabs Area', 'wit'),
        'description'   => __('This is a virtual area (i.e. it won\'t appear anywhere in your blog). Add widgets here to show them in the "Widgets In Tabs" widget. Make sure to give each widget a title or an "@" will be used instead. You DON\'T want to add Widgets In Tabs widget here.', 'wit'),
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
			array( 'description' => __( 'Group any number of widgets into one tabbed, light, and beautiful widget.', 'wit' ), )
		);

		if ( is_active_widget( false, false, $this->id_base ) && !is_admin()) {
			add_action( 'wp_print_styles', array( $this, 'enqueue_style' ) );
			add_action( 'wp_print_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}


	public function enqueue_style() {
		wp_register_style('widgets_in_tabs', plugins_url( 'widgets-in-tabs.min.css', __FILE__ ), array(), WIT_VERSION);
		wp_enqueue_style( 'widgets_in_tabs' );
		if (is_rtl()) {
			wp_register_style('widgets_in_tabs_rtl', plugins_url( 'widgets-in-tabs-rtl.min.css', __FILE__ ), array( 'widgets_in_tabs' ), WIT_VERSION);
			wp_enqueue_style( 'widgets_in_tabs_rtl' );
		}
	}

	public function enqueue_scripts() {
		wp_register_script('widgets_in_tabs', plugins_url( 'widgets-in-tabs.min.js', __FILE__ ), array('jquery'), WIT_VERSION, true);
		wp_enqueue_script( 'widgets_in_tabs' );
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
			'interval' => (empty($instance['interval']) ? '0' : $instance['interval'])
			);
		wp_localize_script('widgets_in_tabs', 'WIT_OPTIONS', $options);

		$title = __('Widgets In Tabs', 'wit');

		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		echo '<ul class = "wit-tab-container">';
		dynamic_sidebar('widgets_in_tabs_area');
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
		<p>Add your widgets to Widgets In Tabs Area, and they will appear in place of this widget.</p>
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
