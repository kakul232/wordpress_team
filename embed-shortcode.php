<?php

add_shortcode('Team','team_content');
function team_content(){

global $wpdb;
// Team Html
?>



<link rel="stylesheet" href="https://s3-ap-southeast-1.amazonaws.com/asset.frankly.me/css/newsite.css">


<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="<?php echo plugins_url( '/js/jquery.carousel.js' , __FILE__ );?>"></script>
<link rel="stylesheet" href="<?php echo plugins_url( '/css/_generic.scss' , __FILE__ );?>">
<style>
video { 
  -webkit-transform: scaleX(2); 
  -moz-transform: scaleX(2);
}
</style>

<section class="carousel-slider">
  <div class="carousel">
    <!-- BEGIN CONTAINER -->
    <ul class="slides video-tabs"> <!-- BEGIN CAROUSEL -->

    <?php

    $sql="SELECT * FROM wp_frankly_team ORDER BY id DESC";
    $result=$wpdb->get_results($sql, OBJECT);
    foreach ($result as $team) {
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

            $rs=json_decode($response['body'],true);
           // print_r($rs);

                ?>

    <!-- Tab 1  li start-->
      <li class="slideItem tab" data-tab="<?php echo $team->username; ?>"> 
        <div class="row valign-wrapper">
          <div class=" videoContainer circle-container link">
            <div class="circle videoHolder">
              <video class="introVideo" style="width: 150px; height: 150px;">
                        <source src="<?php echo $rs['user']['profile_videos'][200];?>" type="video/mp4">
                        <source src="<?php echo $rs['user']['profile_videos']['webm'];?>" >
                        Your browser does not support the video.
              </video>
            </div>
          </div>
        </div>
      </li>

      
       <!-- Tab 1  li End-->
     <?php } ?>

    </ul>
  </div>
</section>
<section>
  <div class="container center-align">
    <div class="row">

     

    <?php
    foreach ($result as $team) {

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

              $rs=json_decode($response['body'],true);

      ?>
    <!-- Tab  Div start-->
      <div id="<?php echo $team->username; ?>" class="tab-content" id="tabcontent">
        <div class="col s6 offset-s3">
          <h4><?php echo $rs['user']['first_name'];?></h4>
          <p><?php echo $rs['user']['bio']; ?></p>
          <iframe frameborder="0" height="100%" width="100%" src="https://frankly.me/widgets/askButtonLg/<?php echo $team->username; ?>?flagRedirect=false&amp;url=http://embed.frankly.me/v2/"></iframe>
        </div>
        <br><br>
        <div class="card-center"><!--card-center class is used for if one card is there then it adjust in center or more card then adjust automatic !-->


        <!-- Answer Card Embadeb Start Here -->
        <?php 
            $qus_url='https://api.frankly.me/timeline/user/'.$rs['user']['id'].'/multitype';
              $qus_response = wp_remote_post( $qus_url, array(
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
             // echo $qus_response['body'];
              $qus_rs=json_decode($qus_response['body'],true);
              for($i=0;$i<count($qus_rs) ; $i++){


            //  print_r($qus_rs["stream"][$i]["post"]["slug"]);

                if(isset($qus_rs["stream"][$i]["post"]["slug"]))
                {
                  $slug=$qus_rs["stream"][$i]["post"]["slug"];
                
              
              echo   '<div class="franklywidget" data-user="'.$team->username.'" data-widget="" data-flag-redirect="" data-query="'.$slug.'" data-height="520" data-width="300" style="margin: auto" flag-redirect="false"><a href="https://frankly.me">Frankly.me</a></div> ';

        } } ?>
        </div>
      </div>

   <!-- Tab   Div End-->

      <?php } ?>
      
    </div>
  </div>
</section>

   
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/js/materialize.min.js"></script>
    
  
    <script src="https://s3-ap-southeast-1.amazonaws.com/asset.frankly.me/js/newsite.js?v=1.2"></script>
   
    
      <script src="https://d1dvu9su9k3ray.cloudfront.net/js/nprogress.js"></script>
      <script src="https://frankly.me/js/franklywidgets.js"> </script>

    <script type="text/javascript">
(function($){
      $( document ).ready(function(){

        $('.tab-content:first').addClass('current');

        //$(".button-collapse").sideNav();
        $('.modal-trigger').leanModal();
        // $('.masonry').masonry();
        $('.carousel').carousel({
          carouselWidth: 230,
          carouselHeight: 250,
          directionNav: true,
          reflection: false,
          shadow: true
        });
        $('ul.video-tabs li').click(function () {
          var tab_id = $(this).attr('data-tab');
          $('ul.tabs li').removeClass('current');
          $('.tab-content').removeClass('current');
          $(this).addClass('current');
          $("#" + tab_id).addClass('current');
        })
      });
      NProgress.configure({ easing: 'ease', speed: 500 });
      NProgress.start();
      NProgress.done();

       $('.introVideo').click(function(e){

            $('video').each(function () {
                this.pause();
                this.currentTime = 0;
            });


          this.paused?this.play():this.pause();

       //   console.log(x);

    });




  })(jQuery);
    </script>



<?php } ?>
