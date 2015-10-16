
<!-- Polling Form Start Here ---->



 <?php do_action('bootsrtap_hook'); ?>
 
<div class="poll-wrap">
  
<div>

  <div class="pull-right">
Welcome To Team Dashboard
    
  </div>
  
  <div class="clear"></div>
  
<div class="row">
	<h1 class="col-xs-12 mr-bt-20">Create Team </h1> 
	<div class="clear"></div>
  <?php echo @$msg; ?>
		<div class="clear mr-bt-20"></div>
    
	<form name="polling form" method="post">

  <div class="form-group mr-bt-10 clearfix">
      <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2">Frankly Username :</label>
        <input class='col-xs-12 col-sm-8 col-md-8 col-lg-6' placeholder="Add your Frankly User Id ?" id="frankly_userid" name="frankly_userid" required>
        <span id="user-result"></span>
       
    </div>

    <div class="form-group mr-bt-10 clearfix">
      <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2">About:</label>
        <textarea class='col-xs-12 col-sm-8 col-md-8 col-lg-6' placeholder="Tell me about Yourself?" id="frankly_about" name="frankly_about" required></textarea>
        <span id="user-result"></span>
       
    </div>

			<input type="submit"  disabled id="AskFrankly" class="btn button-primary button-large submit-btn" placeholder="" value="Add Member">
		</div>
	</form>

</div>


<!-- Polling Form End Here ---->

  <hr />

<!-- Display poll from Here ---->

  <div class="mr-tp-20 allpollwrap">
  
	<h2> Your Team </h2>

 <table class="wp-list-table widefat fixed striped posts" border="1">
   <thead>
                  <tr>
                    <th><strong>Username</strong></th>
                    <th><strong>Full Name</strong></th>
                    <th><strong>Intro Video</strong></td>
                    <th><strong>About</strong></th>
                    <th><strong>Since</strong></th>
                    <th><strong>Status</strong></th>
                    <th><strong>Action</strong></th>
                  </tr>
                </thead>
    
    <tbody>
		 <?php 
		 /*****************************************************
		 @ Display Poll On Table 
		 *****************************************************/

		$sql="SELECT * FROM wp_frankly_team WHERE status != '4' ORDER BY id DESC";
		$result=$wpdb->get_results($sql, OBJECT);
		foreach ($result as $team) {

      if($team->status){
        $status='<label class="label label-success">Active</label>';
      }else{
         $status='<label class="label label-danger">Inactive</label>';
      }


       // Call Question Api
    
            $url='https://api.frankly.me/user/profile/'.$team->username.'';
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

                ?>
               <tr>
                  <td id="username"><?php echo $team->username;?></td>
                  <td id="full_name"><?php echo $rs->user->first_name;?></td>
                  <td>
                      <video class="video" width="200" height="100">
                        <source src="<?php echo $rs->user->profile_video;?>" type="video/mp4">
                        Your browser does not support the video.
                      </video>
                </td>
                  <td id="about"><?php echo $team->about;?></td>
                  <td><?php echo $team->created;?></td>
                  <td id="status"><?php echo $status;?></td>
                  <td><select class="action">
                      <option>-Select-</option>
                      <option value="1" >Active</option>
                      <option value="0">Inactive</option>
                      <option value="edit">Edit</option>
                      <option value="4">Delete</option>
                    </select></td>
                </tr>
               
			<?php	} ?>
    </tbody>
    
  </table>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="myModalLabel" class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">

        <form id="update" method="post" action="#">
          
          <div class="form-group mr-bt-10 clearfix">
            <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2">About:</label>
            <textarea class='col-xs-12 col-sm-8 col-md-8 col-lg-6' placeholder="Tell me about Yourself?" id="about_edit" name="about_edit" required></textarea>
           <span id="user-result"></span>
           <input type="hidden" class='col-xs-12 col-sm-8 col-md-8 col-lg-6' placeholder="Add your Frankly User Id ?" id="frankly_userid_edit" name="frankly_userid_edit" required>
        
         </div>
        <input type="submit" id="AskFrankly" class="btn button-primary button-large submit-btn" placeholder="" value="Update">
    

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


  
  </div>

</div>

</div>

<script type="text/javascript">
/**********************************************************************************
@ check user exit or not
***********************************************************************************/
(function($){
              
                var delay = (function(){
                  var timer = 0;
                  return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                  };
                })();

                var keyup_callback = function(){

                    $('#frankly_userid').val($('#frankly_userid').val().replace(/\s/g, ''));
                    var username = $('#frankly_userid').val();
                    var url = 'http://api.frankly.me/user/profile/' + username;
                    
                    if(username.length < 4){
                        $('#user-result').html('');
                        return;
                    }
                    
                    if(username.length >= 4){
                    
                        $('#user-result').html('<img src="'+ '<?php echo plugins_url( 'images/ajax-loader.gif' , __FILE__ );?>' +'" />');
                        /*
                        $.ajax({
                            method: 'POST',
                            url: 'http://api.frankly.me/user/exists',
                            data: {'username': username }
                            error: function()
                            {
                                $('#user-result').html('<img src="<?php echo plugins_url( 'images/not-available.png' , __FILE__ );?> + '" />');
                                $('#proceed').attr('disabled','disabled');
                            },
                            success: function()
                            {
                                $('#user-result').html('<img src="<?php echo plugins_url( 'images/available.png' , __FILE__ );?> + '" />'');
                                $('#proceed').removeAttr('disabled');

                            }
                        });
                        */
                        $.ajax({
                            method: 'GET',
                            url: url,
                            
                            error: function()
                            {
                                $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/not-available.png' , __FILE__ );?> ' + '" /> Username Not Available');
                                $('#AskFrankly').attr('disabled','disabled');
                            },
                            success: function()
                            {
                                $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/available.png' , __FILE__ );?> ' + '"  /> Username Available');
                                $('#AskFrankly').removeAttr('disabled');

                            }
                            /*
                            ,
                            statusCode: 
                            {
                                404: function() {
                                        console.log('a')            ;
                                    }
                            }*/

                        });
                        
                    }
                        
                };



                
                $('#frankly_userid').keyup( function(){
                    delay(  keyup_callback, 1000 );
                    });
                

              

/****************************************************************************************************
                       Video Play Pause
*****************************************************************************************************/

 $('.video').click(function(){


   $('video').each(function () {
                this.pause();
                this.currentTime = 0;
            });

  this.paused?this.play():this.pause();


 });



 /****************************************************************************************************
                       Action Onchange
*****************************************************************************************************/

$('.action').on("change",function(e){
  e.preventDefault();
var def=$(this);
var select=$(this).val();
var username=$(this).closest("tr").children('td#username').text();
var full_name=$(this).closest("tr").children('td#full_name').text();
var about=$(this).closest("tr").children('td#about').text();




// Edit 
if( select ==='edit')
{
    $('#myModal').modal('show');

    if(full_name ==null || full_name==''){
      $('#myModalLabel').html('<b>'+username+'</b>');
    }else{
       $('#myModalLabel').html('<b>'+full_name+'</b>');
    }
    
    $('#about_edit').val(about);
    $('#frankly_userid_edit').val(username);
    console.log(full_name);

}


// Active Inactive Delete

if( select ==='0' || select ==='1' || select ==='4'){
    $.ajax({
      method: 'post',
      url:'<?php echo admin_url( 'admin-ajax.php' ); ?>',
      data:{
        action: 'update_member',
        username : username,
        select : select,
      },
      success:function(response){
            if(response != ''){

            var obj = JSON.parse(response);
            

                // if inactive
                if(obj.action =='0')
                {
                  def.closest("tr").children('td#status').html('<label class="label label-danger">Inactive</label>');
                }
                 // if active
                if(obj.action =='1')
                {
                  def.closest("tr").children('td#status').html('<label class="label label-success">Active</label>');
                }
                 // if Delete
                if(obj.action =='4')
                {
                  def.closest("tr").remove();
                }
          }else{
            alert('Inactive Member First ');
          }
     }
    });

}



})

               

})(jQuery);
            
</script>
<!-- Display poll End Here -->

<script src="https://frankly.me/js/franklywidgets.js"> </script>


<?php  
include_once('embed-shortcode.php');
?>
