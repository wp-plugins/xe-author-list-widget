<?php
/**
* Plugin Name: XE Author List Widget
* Plugin URI: http://xieno.com
* Description: This widget displays a list of authors.
* Version: 1.0.0
* Author:  Xieno Devloper Team
* Author URI: http://xieno.com
* License: GPL2
*/


// register Xe_Author_List_Widget widget
function register_author_list_widget() {
    register_widget( 'Xe_Author_List_Widget' );
}
add_action( 'widgets_init', 'register_author_list_widget' );

add_action( 'wp_head','xe_autherlistwidget_css');

		// Enque css file
	function xe_autherlistwidget_css() {
		wp_register_style( 'authorListStylesheet', plugins_url('/css/xe-author-list-widget.css', __FILE__) );
		wp_enqueue_style( 'authorListStylesheet' );
	}

/**
 * Adds Xe_Author_List_Widget widget.
 */
class Xe_Author_List_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'author_list_widget', // Base ID
			__( 'XE Author List Widget', 'text_domain' ), // Name
			array( 'description' => __( 'A Foo Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 * @see WP_Widget::widget()
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		echo $args['before_widget'];
		
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		
		$nPost=($instance['noofauthors']!="" && is_numeric($instance['noofauthors']) )  ? $instance['noofauthors']:5;
		
		$avtSz=($instance['avatarsz']!="" && is_numeric($instance['avatarsz']) )  ? $instance['avatarsz']:60;
		
		//	echo $instance['showauname'];
			$author_args = array(
				
				'role'         => 'author',
				'meta_key'     => '',
				'meta_value'   => '',
				'meta_compare' => '',
				'meta_query'   => array(),
				'include'      => array(),
				'exclude'      => array(),
				'orderby'      => 'post_count',
				'order'        => 'DESC',
				'offset'       => '',
				'search'       => '',
				'number'       => $nPost,
				'count_total'  => false,
				'fields'       => 'all',
				'who'          => ''
			 ); 
			
			
			$authors= get_users( $author_args ); 
		
//	echo "<pre>";	print_r($authors);
		 ?>
		<ul class="xe-author-list">
        
        <?php foreach($authors as $author) { 
		?>
        
        	<li style="width:<?php echo $avtSz; ?>px;" >
            	<a href="<?php echo get_author_posts_url( $author->ID ); ?>"><?php echo get_avatar( $author->user_email, $avtSz ); ?></a>
                <?php if($instance['showauname']=="on") { ?>
            	<div class="xe-author-name"><a href=""><?php echo $author->display_name; ?></a></div>
                <?php } ?>
			</li>
        <?php } ?>
        
        
        </ul>		
        
		<?php	echo $args['after_widget'];		
	}

	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] :"";
		$noofauthors = ! empty( $instance['noofauthors'] ) ? $instance['noofauthors'] : __( '5', 'text_domain' );
		$avatarsz = ! empty( $instance['avatarsz'] ) ? $instance['avatarsz'] : __( '60', 'text_domain' );
		
		?>
        <p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        <p><label for=""><?php _e( 'Number of Authors:' ); ?></label> <input class="widefat" type="text" value="<?php echo esc_attr( $noofauthors ); ?>" name="<?php echo $this->get_field_name( 'noofauthors' ); ?>"  id="<?php echo $this->get_field_id( 'noofauthors' ); ?>" ></p>
        <p><label for=""><?php _e( 'Authors Image Size Email:' ); ?></label>  <input class="widefat" type="text" name="<?php echo $this->get_field_name( 'avatarsz' ); ?>" value="<?php echo esc_attr( $avatarsz ); ?>"  id="<?php echo $this->get_field_id( 'avatarsz' ); ?>" ></p>
        
        
       	<p>
			<label for="<?php echo $this->get_field_id( 'showauname' ); ?>"><?php _e( 'Show Author Name:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'showauname' ); ?>" name="<?php echo $this->get_field_name( 'showauname' ); ?>" type="checkbox" <?php if($instance['showauname'] == "on") { echo "checked"; } ?>  >
		</p>

        
		<?php 
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
		
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['noofauthors'] = ( ! empty( $new_instance['noofauthors'] ) ) ? strip_tags( $new_instance['noofauthors'] ) : '';
		$instance['avatarsz'] = ( ! empty( $new_instance['avatarsz'] ) ) ? strip_tags( $new_instance['avatarsz'] ) : '';
		$instance['showauname'] = ( ! empty( $new_instance['showauname'] ) ) ? strip_tags( $new_instance['showauname'] ) : '';
		
		

		return $instance;
	}

} // class Xe_Author_List_Widget