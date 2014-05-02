<?php
/*
 * Plugin Name: Widgets In Tabs
 * Plugin URI: http://wordpress.org/plugins/widgets-in-tabs/
 * Description: Group any number of widgets into one tabbed, light, and beautiful widget.
 * Author: Anas H. Sulaiman
 * Version: 0.7
 * Author URI: http://ahs.pw/
 * Text Domain: wit
 * Domain Path: /langs/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

define( 'WIT_VERSION', '0.7' );

// Register WIT
add_action('widgets_init', 'register_wit');

function register_wit() {
	 
	 register_sidebar(
	 	array (
			'id' => 'wit_area',
			'name' => __('Widgets In Tabs Area', 'wit'),
			'description'   => __('Add widgets here to show them in the tab widget.', 'wit'),
			'before_widget' => '<section id="%1$s" class="%2$s wit-tab-content">',
			'after_widget' => '</section>',
			'before_title' => '<h4 class="wit-tab-title">',
			'after_title' => '</h4>'
        )
	);

	register_widget('Widgets_In_Tabs');
}

add_action( 'plugins_loaded', 'wit_load_textdomain' );

function wit_load_textdomain() {
	load_plugin_textdomain( 'wit', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}

// WIT class
class Widgets_In_Tabs extends WP_Widget {

	function __construct() {
		
		parent::__construct( 
			'Widgets_In_Tabs', 
			__('Widgets In Tabs', 'wit'),
			array( 'description' => __( 'Group any number of widgets into one tabbed, light, and beautiful widget.', 'wit' ) )
		);

		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'wit_go_to_widgets_link' ) );

		// register assets
		if ( is_active_widget( false, false, $this->id_base ) && !is_admin()) {
			add_action( 'wp_print_styles', array( $this, 'enqueue_style' ) );
			add_action( 'wp_print_scripts', array( $this, 'enqueue_scripts' ) );
		}
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ));

	}

	public function enqueue_style() {
		wp_register_style('wit', plugins_url( 'wit-all.min.css', __FILE__ ), array(), WIT_VERSION);
		wp_enqueue_style( 'wit' );
	}

	public function enqueue_scripts() {
		wp_register_script('wit', plugins_url( 'wit-all.min.js', __FILE__ ), array('jquery', 'jquery-ui-tabs'), WIT_VERSION, true);
		wp_enqueue_script( 'wit' );
	}

	public function enqueue_admin_styles($hook) {
		if ('widgets.php' != $hook)
			return;
		wp_register_style('wit_admin', plugins_url( 'wit-admin.min.css', __FILE__ ), array(), WIT_VERSION);
		wp_enqueue_style( 'wit_admin' );
	}

	public function wit_go_to_widgets_link($actions) {
		return array_merge(
			array( 'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'widgets.php' ), __( 'Go to Widgets', 'wit' ) ) ),
			$actions
		);
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

		// protect from stupidity - this widget cannot exist within itself
		if ( $args['id'] == 'wit_area' ) return;

		// get the widgets associated to the wit sidebar
		$wit_widgets = $this->widget_data_for_sidebar('wit_area');
		
		// sidebar is empty - return
		if (empty( $wit_widgets ) ) return;

		echo $args['before_widget'];

		echo '<ul class="wit-nav">';

		foreach ($wit_widgets as $id => $widget) {
			
			if ($widget->title == '') {
				$title = __('Widget', 'wit');
			} else {
				$title = $widget->title;
			}

			echo '<li><a href="#'. $id .'"><span class="tab-icon tab-icon-'. preg_replace('/-([0-9]+)/', '', $id) .'"></span><span class="tab-title">'. $title .'</span></a></li>';
		}
		
		echo '</ul>';

		dynamic_sidebar('wit_area');

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
		?>
		<p><?php _e('Widgets added to the Widgets In Tabs Area sidebar will appear here (in tabs).', 'wit' ); ?></p>
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
		return $instance;
	}

	/**
	 * widget_data_for_sidebar
	 *
	 * Return details of the widgets contained within the specified sidebar
	 * 
	 * @param  string $sidebar_id [description]
	 * @return array 
	 */
	public function widget_data_for_sidebar($sidebar_id) {
		global $wp_registered_sidebars, $wp_registered_widgets;
		
		// Holds the final data to return
		$output = array();

		if( !$sidebar_id ) {
			// There is no sidebar registered with the name provided.
			return $output;
		} 
		
		// A nested array in the format $sidebar_id => array( 'widget_id-1', 'widget_id-2' ... );
		$sidebars_widgets = wp_get_sidebars_widgets();
		$widget_ids = $sidebars_widgets[$sidebar_id];
		
		if( !$widget_ids ) {
			// Without proper widget_ids we can't continue. 
			return array();
		}
		
		// Loop over each widget_id so we can fetch the data out of the wp_options table.
		foreach( $widget_ids as $id ) {
			
			// The name of the option in the database is the name of the widget class.  
			$option_name = $wp_registered_widgets[$id]['callback'][0]->option_name;
			
			// Widget data is stored as an associative array. To get the right data we need to get the right key which is stored in $wp_registered_widgets
			$key = $wp_registered_widgets[$id]['params'][0]['number'];
			
			$widget_data = get_option($option_name);
			
			// Add the widget data on to the end of the output array.
			$output[$id] = (object) $widget_data[$key];
		}
		
		return $output;
	}


} // class Widgets_In_Tabs
