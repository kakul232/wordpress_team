<?php
ob_start();
/**
* Plugin Name: FranklyMe Poll
* Description: Embed Frankly.me social widgets and grow your audience on frankly.me. Official Frankly.me wordpress plugin.
* Author: Frankly.me
* Version: 2.0
* Author URI: http://frankly.me
* Plugin URI: https://wordpress.org/plugins/franklyme/
* Text Domain: frankly-me
* License: GPLv2
**/


/*  Copyright 2015  ABHISHEK GUPTA  (email : abhishekgupta@frankly.me)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( 'admin_menu', 'frankly_poll_page' );
add_action( 'wp_enqueue_scripts', 'my_scripts_main' );
add_action( 'admin_enqueue_scripts', 'my_adminscripts_main' ); 

function frankly_poll_page() {
    add_menu_page( 
        __( 'Frankly Widget', 'textdomain' ),
        'Frankly Poll',
        'manage_options',
        'Frankly',
        'create_poll',
        plugins_url( 'franklyme_poll/images/icon.png' ),
        6
    ); 
}


function my_scripts_main() {

    wp_enqueue_style( 'poll_style', plugins_url( '/css/style.css' , __FILE__ ) );


    wp_enqueue_script(
        'my_voter_script',
        plugins_url( '/js/main.js' , __FILE__ ),
        array( 'jquery' )
    );
    wp_localize_script( 'my_voter_script', 
        'myAjax',
         array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'action'=>'my_poll_srt')
    ); 
    wp_localize_script( 'my_voter_script', 
        'myAjaxwiget',
         array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'action'=>'my_poll')
    );           
}

function my_adminscripts_main() {

    wp_enqueue_style( 'poll_style', plugins_url( '/css/admin-style.css' , __FILE__ ) );
          
}

include_once('franklypoll.php');

include_once('embed-shortcode.php');

include_once('sidepane-widget.php');

ob_flush();



?>