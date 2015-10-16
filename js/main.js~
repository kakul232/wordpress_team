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

/*****************************************************************************
@@@@ Edit Action 
*****************************************************************************/


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
    console.log(full_name);

}


// Active Inactive Delete

if( select ==='0' || select ==='1' || select ==='del')
{
          $.ajax({
            method:POST,
            url:myAjax.ajaxurl,
            data:{
              user_select:select,
              action:update_team
            }
          })

               

})(jQuery);
            
