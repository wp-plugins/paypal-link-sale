<?php
class ppls_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'ppls_widget', // Base ID
			__('Paypal Link Sale','ppls'), // Name
			array( 'description' => __('Widget to display sold links','ppls') ) // Args
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
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			global $wpdb;
			$results=$wpdb->get_results('select ltext,url from '.$wpdb->prefix . 'pplsorders where status IN (1,2)');
			if($results)
			{
			echo '<ul>';
			foreach($results as $r)
			{
			echo '<li><a href='.$r->url.' target="_blank">'.$r->ltext.'</a></li>';
			}
			echo '</ul>';
			}
			else
			{
			/*$dwmy=array("D"=>"Day","W"=>"Week","M"=>"Month","Y"=>"Year");
			$currency=get_option('ppls_currency','USD');
			$price=get_option('ppls_price',10);
			$term=get_option('ppls_term','M');
			echo '<p>Buy Text Link for '.$price.$currency."/".$dwmy[$term].'</p>';*/
			}
			echo '<b><a href="'.get_bloginfo('url').'?ppls_page=form'.'" target="_blank" style="display:block;text-align:right;color:gray;font-size:12px">'.__('Advertise here','ppls').'</a></b>';
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
			$title =__('Sponsored Links','ppls');
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','ppls' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" >
		</p>
		<?php 
	}

}
add_action('widgets_init',
     create_function('', 'return register_widget("ppls_Widget");')
);
?>