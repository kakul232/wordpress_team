<?php // Creating the widget 
class frankly_poll extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'frankly_poll', 

// Widget name will appear in UI
__('Frankly Poll ', 'FramklyMe_Poll'), 

// Widget description
array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'FramklyMe_Poll' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
  if(isset($instance['poll_shrt']))
  {
    $poll_shrt = apply_filters( 'widget_title', $instance['poll_shrt'] );
    // before and after widget arguments are defined by themes
   echo do_shortcode( $poll_shrt );;
  }
}
    
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance['poll_shrt']  ) ) {
$poll_shrt = $instance['poll_shrt'] ;
}
else {
$poll_shrt = __( '', 'FramklyMe_Poll' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'Short Code' ); ?>"><?php _e( 'Short Code:<sup>*</sup>' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'poll_shrt' ); ?>" name="<?php echo $this->get_field_name( 'poll_shrt' ); ?>" type="text" value="<?php echo esc_attr( $poll_shrt ); ?>" />
</p>
<p> * Create New Poll and copy Shortcode from <a href="admin.php?page=Frankly">Frankly Poll</a> </p>
<?php 
}
  
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['poll_shrt'] = ( ! empty( $new_instance['poll_shrt'] ) ) ? strip_tags( $new_instance['poll_shrt'] ) : '';
return $instance;
}
} // Class frankly_poll ends here

// Register and load the widget
function wpb_load_widget() {
  register_widget( 'frankly_poll' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

?>