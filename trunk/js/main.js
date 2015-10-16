jQuery(document).ready(function(){

                           jQuery('.tell_us').hide();


                            jQuery("input[name=answer_srt]:radio").change(function (e) {
                                e.preventDefault();
                             //   submit();
                               //  $(this).closest("form").submit(function() {
                                   // alert('Hello');
                                    var poll_answer=jQuery(this).val();
                                    var poll_id=jQuery(this).closest("form").find('#poll_id_srt').val();
                                    var vi=jQuery(this);



                                 jQuery.ajax({
                                    url: myAjax.ajaxurl,
                                    type: 'POST',
                                    data: {
                                        action: myAjax.action,
                                        answer:poll_answer,
                                        poll_id:poll_id
                                    },
                                   // dataType: 'html',
                                    success: function(response) {
                                        var obj = JSON.parse(response);
                                         console.log(obj.html);
                                     
					var closest=jQuery(vi).closest('#poll_sht');
                                       	closest.find('form').html(obj.html);
					closest.find('.tell_us').show();

                                   
                                    }
                              //  });

                                 });
                            }); 
                         });




jQuery(document).ready(function(){
                            jQuery("input[name=answer]:radio").change(function (e) {
                                e.preventDefault();
                             //   submit();
                               //  $(this).closest("form").submit(function() {
                                   // alert('Hello');
                                    var poll_answer=jQuery(this).val();
                                    var poll_id=jQuery(this).closest("form").find('#poll_id').val();
                                    var vi=jQuery(this);


                                 jQuery.ajax({
                                    url: myAjaxwiget.ajaxurl,
                                    type: 'POST',
                                    data: {
                                        action: myAjaxwiget.action,
                                        answer:poll_answer,
                                        poll_id:poll_id
                                    },
                                   // dataType: 'html',
                                    success: function(response) {
                                        var obj = JSON.parse(response);
                                       console.log(vi);
					var closest=jQuery(vi).closest('#poll_div');
                                       	closest.find('form').html(obj.html);
					closest.find('.tell_us').show();

                                   
                                    }
                              //  });

                                 });
                            }); 
                         });




/*****************************************************************************
	Essential Method
*****************************************************************************/
// PopUp

function popupwindow(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}


// Count text And validate

function countChar(){



}



/*****************************************************************************
frankly Widget 
*****************************************************************************/

var loc = "https://frankly.me/widgets/";
var franklywidget = function () {
  var elem = document.getElementsByClassName('franklywidget');
  for(var i = 0; i < elem.length; i++) {
    var height = elem[i].getAttribute('data-height');
    var width = elem[i].getAttribute('data-width');
    var user = elem[i].getAttribute('data-user');
    var widget = elem[i].getAttribute('data-widget');
    var query = elem[i].getAttribute('data-query');
    var flag=(typeof elem[i].getAttribute('data-flag-redirect')==='undefined'||elem[i].getAttribute('data-flag-redirect')==='')?false:elem[i].getAttribute('data-flag-redirect');
    var content = "<iframe frameborder=0 height='100%' width='100%' src='";
    var contentEnd = "'></iframe>";
    var parentUrl = document.location.href;
    console.log("js", parentUrl);
    elem[i].style.height=height+'px';
    elem[i].style.width=width+'px';
    elem[i].setAttribute('style', 'width: '+width+'px; height: '+height+'px; margin:auto;');
    elem[i].innerHTML = content + loc + widget + (user == '' ? '' : '/' + user) + (query == '' ? '' : '/'+query) +'?flagRedirect=' + flag + '&url=' + parentUrl + contentEnd;
  }
};
(function () {
  setTimeout(franklywidget(), 5000);
})();
