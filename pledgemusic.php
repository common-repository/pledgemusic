<?php
/*
  Plugin Name: PledgeMusic
  Plugin URI: http://www.pledgemusic.com
  Description: Provides the ability to display your PledgeMusic campaign via a sidebar widget or lightbox popup.
  Version: 1.0.1
  Author: 45PRESS Team
  Author URI: http://www.45press.com
  License: GPL2
 */
 
define ('PM_PLUGIN_DIR', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) );

// Define icon styles for the custom post type
function pm_icons() {
    ?>
    <style type="text/css" media="screen">
		#icon-pm {background: url(<?php echo PM_PLUGIN_DIR; ?>/includes/images/pledgemusic_32.png) no-repeat;}
    </style>
    <?php
}
add_action('admin_head', 'pm_icons');

// Init plugin options to white list our options
function pm_init(){
	register_setting('pm_plugin_options', 'pm_options', 'pm_validate_options');	
}
add_action('admin_init', 'pm_init' );

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'pm_add_defaults');
register_uninstall_hook(__FILE__, 'pm_delete_plugin_options');

// Delete options table entries ONLY when plugin deactivated AND deleted
function pm_delete_plugin_options() {
	delete_option('pm_options');
}

// Define default option settings
function pm_add_defaults() {
	$tmp = get_option('pm_options');
	delete_option('pm_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
	$arr = array('pm_id' => '', 'pm_lightbox_opacity' => '0.3');
	update_option('pm_options', $arr);
}
// Enqueue scripts and style
function pm_enqueue_scripts() {

	$options = get_option('pm_options');
	if (($options['pm_lightbox'] == 'true') && ($options['pm_id'])) {
	    wp_enqueue_script('jquery');
		wp_enqueue_script('pm-fancybox', plugins_url('/includes/js/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__));
		wp_enqueue_script('jquery-easing', plugins_url('/includes/js/fancybox/jquery.easing-1.3.pack.js', __FILE__));
		wp_enqueue_style('pm-fancybox-css', plugins_url('/includes/js/fancybox/jquery.fancybox-1.3.4.css', __FILE__));
		if (is_home()) {
			wp_enqueue_script('pledgemusic', plugins_url('/includes/js/pledgemusic.js', __FILE__));
			$pm_data = array('badge_url' => get_option('pm_badge_url'), 'pm_id' => $options['pm_id'], 'text' => (strlen($options['pm_lightbox_text']) > 0) ? $options['pm_lightbox_text']: 'PledgeMusic',
								'opacity' => (isset($options['pm_lightbox_opacity'])) ? $options['pm_lightbox_opacity']: '0.3');
			wp_localize_script('pledgemusic', 'pledgemusic_data', $pm_data );
		}
	}	
}    
add_action('wp_enqueue_scripts', 'pm_enqueue_scripts');

// Sanitize and validate input. Accepts an array, return a sanitized array.
function pm_validate_options($input) {
	$input['pm_id'] =  trim($input['pm_id']); 
	$input['pm_lightbox_text'] =  trim($input['pm_lightbox_text']); 
	return $input;
}
// Add menu pages
function pm_add_menu_pages() {
	add_menu_page('PledgeMusic', 'PledgeMusic', 'manage_options', 'pledgemusic', 'pm_render_manager_page', PM_PLUGIN_DIR.'includes/images/pledgemusic_16.png');
}
add_action('admin_menu', 'pm_add_menu_pages');

// PledgeMusic manager page
function pm_render_manager_page() {
?>
	<div class="wrap">			
		<div id="icon-pm" class="icon32"><br></div>
		<h2 style="border-bottom: 1px solid #DDD;margin-bottom: 10px;">PledgeMusic</h2>
		<span style="color: #777;font-style: italic;">Compliments of <a href="http://www.45press.com"><img src="<?php echo PM_PLUGIN_DIR.'includes/images/45p-logo.png';?>" alt="45PRESS" /></a></span>
		<form method="post" action="options.php">
			<?php settings_fields('pm_plugin_options'); ?>
			<?php $options = get_option('pm_options'); ?>
			<table class="form-table">
				<tr>
					<th scope="row">PledgeMusic ID:</th>
					<td>
						<input type="text" size="40" name="pm_options[pm_id]" value="<?php echo $options['pm_id']; ?>" placeholder="Enter your PledgeMusic user ID here"/>
					</td>
				</tr>			
				<tr>
					<th scope="row">Display PledgeMusic pop-up on homepage:</th>
					<td>
						<input type="checkbox" name="pm_options[pm_lightbox]" value="true" <?php if($options['pm_lightbox'] == 'true') {echo 'checked="checked"';} ?>/>
					</td>
				</tr>
				<tr>
					<th scope="row">Pop-up background opacity:</th>
					<td>
						<select name="pm_options[pm_lightbox_opacity]">
							<option value="0" <?php if($options['pm_lightbox_opacity'] == "0") {echo 'selected="selected"';} ?>>0%</option>
							<option value="0.1" <?php if($options['pm_lightbox_opacity']  == "0.1") {echo 'selected="selected"';} ?>>10%</option>
							<option value="0.2" <?php if($options['pm_lightbox_opacity']  == "0.2") {echo 'selected="selected"';} ?>>20%</option>
							<option value="0.3" <?php if($options['pm_lightbox_opacity']  == "0.3") {echo 'selected="selected"';} ?>>30%</option>
							<option value="0.4" <?php if($options['pm_lightbox_opacity']  == "0.4") {echo 'selected="selected"';} ?>>40%</option>
							<option value="0.5" <?php if($options['pm_lightbox_opacity']  == "0.5") {echo 'selected="selected"';} ?>>50%</option>
							<option value="0.6" <?php if($options['pm_lightbox_opacity'] == "0.6") {echo 'selected="selected"';} ?>>60%</option>
							<option value="0.7" <?php if($options['pm_lightbox_opacity']  == "0.7") {echo 'selected="selected"';} ?>>70%</option>
							<option value="0.8" <?php if($options['pm_lightbox_opacity']  == "0.8") {echo 'selected="selected"';} ?>>80%</option>
							<option value="0.9" <?php if($options['pm_lightbox_opacity']  == "0.9") {echo 'selected="selected"';} ?>>90%</option>
							<option value="1" <?php if($options['pm_lightbox_opacity']  == "1") {echo 'selected="selected"';} ?>>100%</option>
						</select>
					</td>
				</tr>		
				<tr>
					<th scope="row">Pop-up caption text:</th>
					<td>
						<input type="text" size="40" name="pm_options[pm_lightbox_text]" value="<?php echo $options['pm_lightbox_text']; ?>" placeholder="Enter a caption or description"/>
					</td>
				</tr>					
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
		<?php
		echo 'We also provide a sidebar widget. You can add it by <a href="'.get_admin_url().'widgets.php">visiting the widgets page</a>.';
		if($options['pm_id']) {
			$url = "http://www.pledgemusic.com/projects/" . $options['pm_id'] . ".json";
			$data = json_decode(file_get_contents($url), false);		
			if ($data) {
				update_option('pm_badge_url', $data->project->badge_url);
				echo '<h3>Current Campaign</h3>';
				echo '<img src="'.$data->project->badge_url.'"/><br/><br/>';
				echo '<strong>Full project name: </strong>' . $data->project->owner_project_full_name . '<br/><br/>';
				echo '<strong>Descripion: </strong>' . $data->project->description . '<br/><br/>';
				//echo '<strong>About: </strong>' . $data->project->about . '<br/><br/>';
				$exclusives = $data->project->exclusives;
				$i = 1;
				foreach($exclusives as $exclusive) {
					echo '<strong>Exclusive '.$i.': </strong>' . $exclusive->full_name . ' <strong>' . $exclusive->price_to_s. '</strong><br/><br/>';
					$i++;
				}
			}
		}
		?>
	</div>
<?php
}

/**
 * Adds PledgeMusic widget.
 */
class PledgeMusic_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'pledgemusic_widget', // Base ID
			'PledgeMusic Badge', // Name
			array( 'description' => __( 'Display your PledgeMusic campaign badge.', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		else {
			echo $before_title . 'PledgeMusic Campaign' . $after_title;
		}
		$options = get_option('pm_options');
		if($instance['pm_id']) {
			$url = "http://www.pledgemusic.com/projects/" . $instance['pm_id'] . ".json";
			$data = json_decode(file_get_contents($url), false);	
			echo '<a href="http://www.pledgemusic.com/projects/'.$instance['pm_id'].'"><img src="'.$data->project->badge_url.'"/></a>';
		}
		elseif($options['pm_id']) {
			$url = "http://www.pledgemusic.com/projects/" . $options['pm_id'] . ".json";
			$data = json_decode(file_get_contents($url), false);	
			echo '<a href="http://www.pledgemusic.com/projects/'.$options['pm_id'].'"><img src="'.$data->project->badge_url.'"/></a>';		
		}
		else {
			echo '<p>Please enter your PledgeMusic ID.</p>';
		}

		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'PledgeMusic Campaign', 'text_domain' );
		}
		if ( isset( $instance[ 'pm_id' ] ) ) {
			$pm_id = $instance[ 'pm_id' ];
		}
		else {
			$options = get_option('pm_options');
			$pm_id = $options['pm_id'];
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		<br/><br/>
		<label for="<?php echo $this->get_field_id( 'pm_id' ); ?>"><?php _e( 'Pledge Music ID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'pm_id' ); ?>" name="<?php echo $this->get_field_name( 'pm_id' ); ?>" type="text" value="<?php echo esc_attr( $pm_id ); ?>" />
		</p>
		<?php 
	}

} // class PledgeMusic_Widget
add_action( 'widgets_init', create_function( '', 'register_widget( "PledgeMusic_Widget" );' ) );
?>
