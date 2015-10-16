<!-- /***********************************************************************************
@
@ user Not login With frankly Me  
@
*************************************************************************************/
 -->


<style>
  #wpwrap { background: #fff}
  .dasboardwrap { padding: 30px 15px;  background: #fff;}
  .dasboardwrap input { width:350px; padding: 5px 10px; font-size:16px; color: #333; border:1px solid #ccc; box-shadow:none; margin-bottom:10px; }
  .dash-logintable { padding: 10px 15px;}
  

    

</style>


<div class="dasboardwrap">

    <h1>Enter your Frankly.me username to get started,</h1>
    
        <table class="form-table dash-logintable">
        <tr>
       

            <td>
                <input type="text" id="frankly_uname" placeholder="Frankly Username" name="frankly_uname" /> <span id="user-result"></span><br>
                <input type="password" id="frankly_pass" placeholder="Frankly Password" name="frankly_pass" /><br>
              
               <button style="" class="button button-primary" id ="proceed"  >Submit</button>
                
                <br>
              
              
              <br><br>
                <b style="decorator:underlined">How to get frankly user name?</b><br>
                    <div style="margin-left:20px">
                    <ul style="list-style-type:circle">
                        
                        <li> Download the frankly.me app from
                            <a target="_blank" href="https://play.google.com/store/apps/details?id=me.frankly">
                            <img style="height:30px; vertical-align: middle;" src="<?=plugins_url( 'images/playstore.png' , __FILE__ );?>" class="MainGetAppLinks"></a> 
                            <a target="_blank" href="https://itunes.apple.com/in/app/frankly.me-talk-to-celebrities/id929968427&amp;mt=8">
                            <img style="height:30px; vertical-align: middle;" src="<?= plugins_url( 'images/appstore.png' , __FILE__ );?>" class="MainGetAppLinks"></a> or Register at <a href="http://frankly.me/" target="_blank">frankly.me</a>.
                        </li>

                        <li> Create an account</li>
                        <li> Open your profile to get your user name.</li>
                    </ul>
                    <div>
            </td>
        </tr>
        </table>
       
          
    </div>      
          
<script type="text/javascript">


	
            (function($){
              
                var delay = (function(){
                  var timer = 0;
                  return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                  };
                })();

                var keyup_callback = function(){

                    $('#frankly_uname').val($('#frankly_uname').val().replace(/\s/g, ''));
                    var username = $('#frankly_uname').val();
                    var url = 'http://api.frankly.me/user/profile/' + username;
                 // var url='https://frankly.me/auth/local';
                    
                    if(username.length < 4){
                        $('#user-result').html('');
                        return;
                    }
                    
                    if(username.length >= 4){
                    
                        $('#user-result').html('<img src="'+ '<?php echo plugins_url( 'images/ajax-loader.gif' , __FILE__ );?>' +'" />');
                      
                        $.ajax({
                            method: 'GET',
                            crossDomain: true,
                          //  data:'username='+username,
                            url: url,
                            
                            error: function()
                            {
                                $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/not-available.png' , __FILE__ );?>' + '" /> Username Not Available !');
                               // $('#proceed').attr('disabled','disabled');
                            },
                            success: function()
                            {
                                $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/available.png' , __FILE__ );?>' + '"  /> Username Available !');
                              //  $('#proceed').removeAttr('disabled');

                            }
                          

                        });
                        
                    }
                        
                };



                
                $('#frankly_uname').keyup( function(){
                    delay(  keyup_callback, 1000 );
                    });
                

/**********************************************************************************
@ Proceed Button Call
***********************************************************************************/

                $('#proceed').click(function(){

                    var uname = $('#frankly_uname').val();
                    var pass = $('#frankly_pass').val();

                    $.ajax({
                            method: 'POST',
                            url: ajaxurl,
                            data: {
                                    
                                    'action': 'my_login',
                                    'frankly_uname': uname,
                                    'frankly_pass': pass,

                                 },
                            success: function(response)
                            {
                            	 var obj = JSON.parse(response);
                            	 console.log(obj);
                                if(obj.flag=='1')
                                {
                                     window.location.assign('admin.php?page=Frankly');
                                }else{
                                     $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/not-available.png' , __FILE__ );?>' + '" /> Password Doesnt match !');
                                }
                              console.log(response);
                            }
                        });

                });

 })(jQuery);






</script>