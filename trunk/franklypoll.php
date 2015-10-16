<?php
ob_start();
function create_poll(){
	//require_once('./wp-load.php');
	global $wpdb;

/****************************************************************************
@ Create Table If not Exist

*****************************************************************************/



	 $sql_poll="CREATE TABLE IF NOT EXISTS `wp_frankly_poll` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `question` text NOT NULL,
              `question_id` longtext NOT NULL,
              `slug` longtext NOT NULL,
              `username` text,
              `status` varchar(11) NOT NULL,
              `updated` datetime NOT NULL,
              `created` datetime NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `id` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        	$result_poll=$wpdb->get_results($sql_poll);

	 $sql_ans ="CREATE TABLE IF NOT EXISTS `wp_frankly_ans` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `poll_id` int(11) NOT NULL,
                  `vote` varchar(50) NOT NULL,
                  `created` int(11) NOT NULL,
                  `updated` int(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$result_ans=$wpdb->get_results($sql_ans);

    $sql_option ="CREATE TABLE IF NOT EXISTS `wp_frankly_option` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `poll_id` int(11) NOT NULL,
                  `option` longtext NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
    $result_ans=$wpdb->get_results($sql_option);
	
	/*if(!$result_poll && $result_ans){
		echo "Not Found";
	}else{
		echo " Found";
	} */





/****************************************************************************
@ Insert Poll to User Database

*****************************************************************************/
if(isset($_REQUEST['poll']))
{
	$poll=$_REQUEST['poll'];
	$status='Yes'   ;      //$_REQUEST['status'];
  $options=$_REQUEST['option'];

	$username = get_user_meta (get_current_user_id(), 'frankly', true );
	$userid = get_user_meta (get_current_user_id(), 'frankly_id', true );
	$token = get_user_meta (get_current_user_id(), 'frankly_token', true );

	
	 // Call Question Api
		
	 	$url='https://frankly.me/openquestionwordpress/'.rawurlencode($poll).'';
    	$response = wp_remote_post( $url, array(
	        'method' => 'POST',
	        'timeout' => 45,
	        'redirection' => 5,
	        'httpversion' => '1.0',
	        'blocking' => true,
	        'headers' => array(),
	        'body' => array('token' => $token ),
	        'cookies' => array()
	        )
    	);

      // collect data from response

      $rs=json_decode($response['body']);
      //print_r($rs); 
      $question_id  = $rs->id;
      $slug=$rs->slug;

    	if($rs->id)
    	{
  
    		// Poll Insert Into WpDb
    		/********************************************
    			@ If Display Yes Set All Status NO 
    		********************************************/
    		if($status=='Yes')
    		{
				$sql="UPDATE wp_frankly_poll SET status='No'";
	    		$rs=$wpdb->get_results($sql);
	    		
	    		$Insert=$wpdb->insert(
						'wp_frankly_poll',
						array('question' => $poll,
              'question_id'=>$question_id,
								'slug'=>$slug,
								'username' => $username,
							   'status'=> $status
						 )
					);
                $lastid = $wpdb->insert_id;
	    	}else{

				 $Insert=$wpdb->insert(
						'wp_frankly_poll',
						array('question' => $poll,
                'question_id'=>$question_id,
								'slug'=>$slug,
								'username' => $username,
							   'status'=> $status
						 )
					);
                 $lastid = $wpdb->insert_id;
				}

          /***************************
          @ Insert option 
          ***************************/  



          foreach ($options as $option) {
           
                    $Insert=$wpdb->insert(
                            'wp_frankly_option',
                            array('poll_id' => $lastid,
                                    'option'=>$option,
                            )
                        ); 
                    $lastid_op = $wpdb->insert_id;
                }  
        if($lastid_op){
          
          $msg= '<div id="message" class="updated notice notice-success is-dismissible below-h2"><p>Poll published. </p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }

    		
    	}

}


/***********************************************************************************
@
@ user Not login With frankly Me  
@
*************************************************************************************/
if(!get_user_meta (get_current_user_id(), 'frankly', true ))
	{

      include_once('frankly_login.php');

	}

/***********************************************************************************
@
@ user login With frankly Me  
@
*************************************************************************************/



else
	{

      include('frankly_poll_action.php');

  } // Else user End here

 }



/***************************************************************
@ login Function  Ajax callback 
**************************************************************/

 add_action( 'wp_ajax_my_login', 'my_login_callback' );


function my_login_callback() {


    // HTTP REQUEST TO LOGIN API 

    $url='https://frankly.me/auth/local';
    $response = wp_remote_post( $url, array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => array( 'username' => $_POST['frankly_uname'], 'password' => $_POST['frankly_pass'] ),
        'cookies' => array()
        )
    );

    // DECODE RESPONDSE 
   //print_r($response);
   $data= json_decode($response['body'] ,true);
   
    if(isset($data['user']))
    {
         $user=$data['user']['username'];
         $token=$data['user']['token'];
         $id =$data['user']['id'];

        update_user_meta( get_current_user_id(), 'frankly', $user );
        update_user_meta( get_current_user_id(), 'frankly_id', $id );
        update_user_meta( get_current_user_id(), 'frankly_token',  $token );
        $json = array('flag' => 1);                      //  Login Successfull Collect token
     }else{
         $json = array('flag' => 0);                       //  Login failed
     }
     echo json_encode($json);
    wp_die();
}

/***************************************************************
@ logout Function  Ajax callback 
**************************************************************/

 add_action( 'wp_ajax_my_logout', 'my_logout_callback' );


function my_logout_callback() {
    delete_user_meta( get_current_user_id(), 'frankly');
    delete_user_meta( get_current_user_id(), 'frankly_id');
    delete_user_meta( get_current_user_id(), 'frankly_token');
    $json=array('flag' => 1 );

     echo json_encode($json);
    wp_die();
}


ob_flush();
?>


