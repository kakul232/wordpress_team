
<!-- Polling Form Start Here ---->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>


 
<div class="poll-wrap">
  
<div>

  <div class="pull-right">
Welcome @ <?=get_user_meta (get_current_user_id(), 'frankly', true ); ?>
<span id="logout">logout</span>
    
  </div>
  
  <div class="clear"></div>
  
<div class="row">
	<h1 class="col-xs-12 mr-bt-20">Add New Poll </h1> 
	<div class="clear"></div>
  <?php echo @$msg; ?>
		<div class="clear mr-bt-20"></div>
    
	<form name="polling form" method="post" accept="<?=$_SERVER['php_self']; ?>">
		<div class="form-group mr-bt-10 clearfix">
			<label class="col-xs-12 col-sm-4 col-md-3 col-lg-2">Poll Question :</label>
				<textarea class='col-xs-12 col-sm-8 col-md-8 col-lg-6' placeholder="Ask Anything for polling ?" id="poll" name="poll" required></textarea>
        <div id="charCount">0/300</div>
			
			 <span class="error" id="error"></span>
		</div>
    
        <div class="form-group">
            <!-- <label>Option :</label> -->
                <input class='form-control 'type="hidden" placeholder="Your Poll option"  name="option[]" value="No" required>
                
        </div>
        <div class="form-group" id="option">
            <!-- <label>Option :</label> -->
                <input class='form-control 'type="hidden" placeholder="Your Poll option"  name="option[]" value="Yes" required>
                
        </div>
        <div id="moreoption"></div>
        <!-- <input type="button" class="btn btn-primary" id="addmore" value=" + Add More"> -->

		<!-- <div class="form-group">
			<label>Want to active on Sidebar :</label>
				<select class="form-control" name="status">
					<option selected>Yes</option>
					<option >No</option>
				</select>
		</div>	 -->

		<div class="form-group col-xs-12">
			<input type="submit"  disabled id="AskFrankly" class="btn button-primary button-large submit-btn" placeholder="" value="Ask Frankly :)">
		</div>
	</form>

</div>


<!-- Polling Form End Here ---->

  <hr />

<!-- Display poll from Here ---->

  <div class="mr-tp-20 allpollwrap">
  
	<h2> All Polls </h2>

 <table class="wp-list-table widefat fixed striped posts" border="1">
   <thead>
                  <tr>
                    <th><strong>Poll</strong></th>
                    <th><strong>Yes %</strong></td>
                    <th><strong>No %</strong></th>
                    <th><strong>Shortcode</strong></th>
                  </tr>
                </thead>
    
    <tbody>
		 <?php 
		 /*****************************************************
		 @ Display Poll On Table 
		 *****************************************************/
		$uid = get_user_meta (get_current_user_id(), 'frankly', true );

		$sql="SELECT * FROM wp_frankly_poll WHERE username='$uid'ORDER BY id DESC";
		$result=$wpdb->get_results($sql, OBJECT);
		foreach ($result as $poll) {

                $slug=$poll->slug;
                $poll_id=$poll->id;

      // Count Poll 

      $sql="SELECT count(*) FROM wp_frankly_ans WHERE poll_id='$poll_id'";
      $count_total=$wpdb->get_var($sql);

      $sql="SELECT * FROM wp_frankly_option WHERE poll_id='$poll_id'ORDER BY id DESC";
      $rs=$wpdb->get_results($sql, OBJECT);


                ?>
               <tr>
                  <td><?=$poll->question;?></td>
                <?php  foreach ($rs as $op) {

                $sql="SELECT count(*) FROM wp_frankly_ans WHERE vote='$op->option' AND poll_id='$poll_id'";
                $count=$wpdb->get_var($sql);
                $count_per=sprintf ("%.2f", ($count/$count_total) * 100);
               // $html[]=$op->option.':<progress value="'.$count_per.'" max="100"></progress> '.$count_per.'%</br>';
                echo '<td>'.$count_per.'</td>';
                
        }     ?>



                  <td><?php echo '[franklyPoll poll='.$poll_id.' id='.get_current_user_id().']' ;?></td>
                </tr>
               
			<?php	} ?>
    </tbody>
    
  </table>
  
  </div>

</div>

</div>

<script type="text/javascript">
/**********************************************************************************
@ Logout Button Call
***********************************************************************************/

                $('#logout').click(function(){


                    $.ajax({
                            method: 'POST',
                            url: ajaxurl,
                            data: {
                                    
                                    'action': 'my_logout',
                                    

                                 },
                            success: function(response)
                            {
                                 var obj = JSON.parse(response);
                                 console.log(obj);
                                if(obj.flag=='1')
                                {
                                     window.location.assign('admin.php?page=Frankly');
                                }else{
                                     $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/not-available.png' , __FILE__ );?>' + '" /> Unable to proceed request !');
                                }
                              console.log(response);
                            }
                        });

                });



/****************************************************************************************************
                        Add And Remove OPTION
*****************************************************************************************************/

                $('#addmore').click(function(){
                    $( "#option" ).clone().append('<span class="remove" style="color:red;">[X]Remove</span>').appendTo( "#moreoption" );
                    $('.remove').bind("click",".remove",function(e) {
                          $(this).closest('#option').remove();
                     });;

                });


                $("#poll").bind("input",function(e){
									
									
												if (this.value.length == 300) {
                                e.preventDefault();
                            } else if (this.value.length > 300) {
                                // Maximum exceeded
                                this.value = this.value.substring(0, 300);
                            }
									
                        var count_poll_char=$(this).val().length;
									
                        $('#charCount').html(count_poll_char+'/300');
                        if(count_poll_char<15 || count_poll_char>300)
                        {
                          $('#AskFrankly').attr("disabled", 'disabled');
                          $("#error").html('<font color="red">Question Should Be Between 15 -300 Character Long</font>');
                        }else
                        {
                          $('#AskFrankly').removeAttr('disabled');
                          $("#error").html("");
                        }


                        
                  });

               


            
</script>
<!-- Display poll End Here ---->

<script src="https://frankly.me/js/franklywidgets.js"> </script>


<?php  
include_once('sidepane-widget.php');
include_once('embed-shortcode.php');
?>
