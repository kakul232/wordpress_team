<?php
ob_start();
function create_team(){
	//require_once('./wp-load.php');
	global $wpdb;

/****************************************************************************
@ Create Table If not Exist

*****************************************************************************/



	 $sql_team="CREATE TABLE IF NOT EXISTS `wp_frankly_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` text NOT NULL,
  `about` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `created` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

	 
    $result_ans=$wpdb->get_results($sql_team);
	

/****************************************************************************
@ Create TEAM pAGE

*****************************************************************************/

$contact_page_content='[Team]';

$check_title=get_page_by_title('Team', 'OBJECT', 'page') ;
$user_id = get_current_user_id();

if (!$check_title or empty($check_title)){

    $post = array(
      'ID'             => '' ,  
      'post_content'   => $contact_page_content, 
      'post_name'      => 'your-team', // The name (slug) for your post
      'post_title'     => 'Team' ,// The title of your post.
      'post_status'    => 'publish', // Default 'draft'.
      'post_type'      => 'page' , // Default 'post'.
      'post_author'    => $user_id, // The user ID number of the author. Default is the current user ID.
      'post_parent'    => 0 , // Sets the parent of the new post, if any. Default 0.
      'menu_order'     => 5, // If new post is a page, sets the order in which it should appear in supported menus. Default 0.
      'comment_status' => 'closed' ,// Default is the option 'default_comment_status', or 'closed'.
    );  

    // Insert the post into the database

    $post_id=wp_insert_post( $post );
    echo '<h1>Contact Us Dashbord ! </h1> ';


}
else
{
   $post = array(
      'ID'             => '' ,  
      'post_content'   => $contact_page_content, 
      'post_name'      => 'your-team', // The name (slug) for your post
      'post_title'     => 'Team' ,// The title of your post.
      'post_status'    => 'publish', // Default 'draft'.
      'post_type'      => 'page' , // Default 'post'.
      'post_author'    => $user_id, // The user ID number of the author. Default is the current user ID.
      'post_parent'    => 0 , // Sets the parent of the new post, if any. Default 0.
      'menu_order'     => 5, // If new post is a page, sets the order in which it should appear in supported menus. Default 0.
      'comment_status' => 'closed' ,// Default is the option 'default_comment_status', or 'closed'.
    );  


     // update the post into the database

    $post_id=wp_update_post( $post );
  


}


/****************************************************************************
@ Insert team to Database

*****************************************************************************/
if(isset($_REQUEST['frankly_userid']))
{
	$frankly_userid=$_REQUEST['frankly_userid'];
  $frankly_about=$_REQUEST['frankly_about'];
  
	$status='1'   ;      //$_REQUEST['status']

	
	 // Call Question Api
		
	 	$url='https://api.frankly.me/user/profile/'.$frankly_userid.'';
    	$response = wp_remote_post( $url, array(
	        'method' => 'GET',
	        'timeout' => 45,
	        'redirection' => 5,
	        'httpversion' => '1.0',
	        'blocking' => true,
	        'headers' => array(),
	        'body' => array(),
	        'cookies' => array()
	        )
    	);

      // collect data from response

      $rs=json_decode($response['body']);
     // print_r($rs->user); 
      $date=date("Y-m-d");

    	if($rs->user)
    	{

        $Insert=$wpdb->insert(
            'wp_frankly_team',
            array('userid' => $rs->user->id,
                  'username' => $rs->user->username,
                  'about'=>$frankly_about,
                  'status'=> $status,
                  'created'=>$date
             )
          );
  
    		$lastid=$wpdb->insert_id;
  
        if($lastid){
          
          $msg= '<div id="message" class="updated notice notice-success is-dismissible below-h2"><p>Member Add Successfully. </p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }

    		
    	}

}




/***********************************************************************************
@
@  Display On Admin Panel Add Team 
@
*************************************************************************************/
include_once('frankly_add_team.php');

 }

/***********************************************************************************
@
@  Update Ajax Callback
@
*************************************************************************************/
add_action('wp_ajax_nopriv_update_member', 'update_member_callback');
add_action('wp_ajax_update_member', 'update_member_callback');

function update_member_callback(){

global $wpdb;

  $update =$wpdb->update('wp_frankly_team', array('status'=>$_REQUEST['select']), array('username'=>$_REQUEST['username']));
  if($update>0){echo json_encode(array('status'=>'updated', 'action'=>$_REQUEST['select']));}

  wp_die();

}




if(isset($_POST['about_edit']))
{
   $update =$wpdb->update('wp_frankly_team', array('about'=>$_POST['about_edit']), array('username'=>$_POST['frankly_userid_edit']));
   if($update>0){
          
          $msg= '<div id="message" class="updated notice notice-success is-dismissible below-h2"><p>Edit Successfully. </p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }
}

ob_flush();
?>


