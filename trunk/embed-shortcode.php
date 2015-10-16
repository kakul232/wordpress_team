<?php
add_shortcode('franklyPoll', 'frankly_widget_poll');
/*******************************
@ Widget Frankly Poll
@ Type Sm , lg
********************************/
function frankly_widget_poll($atts)
{
  $a = shortcode_atts(array(
    'id' => '1',
    'poll' => '1',
    'height' => '20px',
    'width' => '52px',
    'style' => 'float:right',
  ) , $atts);
  if (!empty($a['id'])) {
    $uid = get_user_meta($a['id'], 'frankly', true);
    $poll = $a['poll'];
    if ($uid != NULL) {
      global $wpdb;
      $sql = "SELECT * FROM wp_frankly_poll WHERE username='$uid' AND id='$poll' ORDER BY id DESC LIMIT 1";
      $result = $wpdb->get_results($sql, OBJECT);
      foreach($result as $poll) { ?>


                    <script src="//use.typekit.net/eam4uba.js"></script>
                    <script>try{Typekit.load({ async: true });}catch(e){}</script>

<div id="Poll-frankly">

                    <div class="frankly-pollwrap">
                      <h3> What's your say? </h3>

                      <!---Open Quetion Widget Start-->
                      <h4> <?php
        echo $poll->question; ?></h4>
                      <div id="poll_sht">

                      

                        <form class="poll-form">

                          <input type="hidden" id="poll_id_srt" name="poll_id_srt" value="<?php echo $poll->id ?>">

                          <label class="yes-btn">
                            <input type="radio" style="margin:2px;" name="answer_srt" class="answer" value="Yes"><img width="48" src="<?php echo plugins_url('franklyme_poll/images/yes_icon.png'); ?>" alt="img"><span>YES</span></label> <span>or</span>

                          <label class="no-btn">
                            <input type="radio" style="margin:2px;" name="answer_srt" class="answer" value="No"><img width="48" src="<?php echo plugins_url('franklyme_poll/images/no_icon.png'); ?>" alt="img"><span>NO</span></label>

                        </form>

                        <div onclick='popupwindow("https://frankly.me/recorder/recorder?type=question&resourceId=<?php
        echo $poll->question_id; ?>&widget=true","Tell Us Why", 300, 500)' class="tell_us">Tell us why?</div>
												
												<div class="clear"></div>
                         
                      </div>

                      <div class="cst-row">

                      
                      <div class="responsewrap">

                      <?php
        /*****************************************************************
        * @ Collect Answer card
        *******************************************************************/
        $url = 'http://api.frankly.me/question/list/answers?question_id=' . $poll->question_id;
        $response = wp_remote_post($url, array(
          'method' => 'GET',
          'timeout' => 45,
          'redirection' => 5,
          'httpversion' => '1.0',
          'blocking' => true,
          'headers' => array() ,
          'body' => array() ,
          'cookies' => array()
        ));
        // print_r(json_decode($response['body']));
        $rs = json_decode($response['body']);
        $count_answer = count($rs->stream) - 1;
        if($count_answer !=0)
        {
          
          
          $username_dis = $rs->stream[0]->post->answer_author->username;
        
?>



                              <div class="total"><span><?php echo $username_dis; ?></span>  <?php 
                              if($count_answer == 1)
                              {
                                echo 'Responded</div>';
                              }else
                              {
                                echo '& ' .$count_answer - 1 . 'others Responded</div>';
                                } ?>
                              
                                <ul>

                                <?php
        for ($i = 0; $i < $count_answer; $i++) {
          $username = $rs->stream[$i]->post->answer_author->username;
          $Thumb_img = $rs->stream[$i]->post->answer->media_urls->thumb;
?>
                                      <li>
                                      <a onclick='window.open("https://frankly.me/widgets/popup/plugin/<?php
          echo $username . '/' . $poll->slug; ?>",500,400)';  class="greyscale">
                                      <!-- keep the div's height and width in 16:9 ratio -->
                                      <div class="answerVideo" poster="" data-uuid="video-openQuestionWidget-Tensports-is-juventus-the-only-team-which-can-beat-barcelona-shzszd9answera1f3f3b289974fc69915b99b9e9f88cf29455" style=" background: url('<?php
          echo $Thumb_img; ?>');background-size: cover;background-repeat: no-repeat;height:80px;width:45px" data-url="https://frankly.me/widgets/popup/plugin/PrernaSinha/is-juventus-the-only-team-which-can-beat-barcelona-shzszd9"></div>
                                      </a>
                                      </li>    

                                <?php
        } 

        } // if count end?>
                                </ul>
                            </div>
                          </div>
                      
                      
                      
                      <div class="clear"></div>
                      
                      <!---Open Quetion Widget End-->
                    </div>
	
</div>
                                     
                    <?php
      } ?>
                                     </br>
                                     

                                     
                                    

                                              <script type="text/javascript"></script>
                                                                 

                                            <?php
      return false;
    }
  }
}
/***********************************************************************
* @ Collect poll result Through Wp Ajax
**********************************************************************/
add_action('wp_ajax_nopriv_my_poll_srt', 'my_poll_srt_callbck');
add_action('wp_ajax_my_poll_srt', 'my_poll_srt_callbck');
function my_poll_srt_callbck()
{
  global $wpdb;
  $poll_id = $_POST['poll_id'];
  $answer = $_POST['answer'];
  $d = mktime(11, 14, 54, 8, 12, 2014);
  $created = date("Y-m-d h:i:sa", $d);
  $Insert = $wpdb->insert('wp_frankly_ans', array(
    'poll_id' => $poll_id,
    'vote' => $answer,
    'created' => $created
  ));
  /*******************  Count vote **************/
  $sql = "SELECT count(*) FROM wp_frankly_ans WHERE poll_id='$poll_id'";
  $count_total = $wpdb->get_var($sql);
  $sql = "SELECT * FROM wp_frankly_option WHERE poll_id='$poll_id'ORDER BY id DESC";
  $rs = $wpdb->get_results($sql, OBJECT);
  // count yes
  $sql = "SELECT count(*) FROM wp_frankly_ans WHERE vote='Yes' AND poll_id='$poll_id'";
  $count_yes = $wpdb->get_var($sql);
  $count_per_yes = sprintf("%.2f", ($count_yes / $count_total) * 100);
  // count No
  $sql = "SELECT count(*) FROM wp_frankly_ans WHERE vote='No' AND poll_id='$poll_id'";
  $count_no = $wpdb->get_var($sql);
  $count_per_no = sprintf("%.2f", ($count_no / $count_total) * 100);
  /*  foreach ($rs as $op) {
  $sql="SELECT count(*) FROM wp_frankly_ans WHERE vote='$op->option' AND poll_id='$poll_id'";
  $count=$wpdb->get_var($sql);
  $count_per=sprintf ("%.2f", ($count/$count_total) * 100);
  $html[]=$op->option.':<progress value="'.$count_per.'" max="100"></progress> '.$count_per.'%</br>';
  }*/
  $html = '<div class="scorewrap"> <img width="19" src="' . plugins_url('franklyme_poll/images/yes.png') . '"> <span>Yes</span> <div class="pogresswrap"><span style="width:' . $count_per_yes . '%; background:#62c934;"></span></div><b>' . $count_per_yes . '%</b> <div>
      <div class="scorewrap"><img width="19" src="' . plugins_url('franklyme_poll/images/no.png') . '"> <span>No&nbsp;</span> <div class="pogresswrap"><span style="width:' . $count_per_no . '%; background:#db4a51;"></span></div><b>' . $count_per_no . '%</b></div>';
  echo json_encode(array(
    'html' => $html
  ));
  wp_die();
}
?>