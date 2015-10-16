<?php
ob_start();
/**
* Plugin Name: Frankly Team
* Description: FranklyMe Poll plugin allows you to easily manage the polls and cross-synchronization of Open Question from within your FranklyMe account, wherein users would be able to answer those question via FranklyMe app and website.
* Author: Frankly.me
* Version: 1.0
* Author URI: http://frankly.me
* Plugin URI: https://wordpress.org/plugins/Frankly-Video-Poll/
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

add_action( 'admin_menu', 'frankly_team_page' );
add_action( 'wp_enqueue_scripts', 'my_teamscripts_main' );
add_action( 'admin_enqueue_scripts', 'my_teamadminscripts_main' ); 

function frankly_team_page() {
    add_menu_page( 
        __( 'Frankly Team Dashboard', 'textdomain' ),
        'Frankly Team',
        'manage_options',
        'Team_Deshboard',
        'create_team',
        plugins_url( 'franklyme_poll/images/icon.png' ),
        6
    ); 
}


//  wp_enqueue_script for user 

function my_teamscripts_main() {

    wp_enqueue_style( 'poll_style', plugins_url( '/css/style.css' , __FILE__ ) );


    wp_enqueue_script(
        'my_team_script',
        plugins_url( '/js/main.js' , __FILE__ ),
        array( 'jquery' )
    );

     wp_enqueue_script(
        'my_font',
        plugins_url( '/js/eam4uba.js' , __FILE__ ),
        array( 'jquery' )
    );

    wp_localize_script( 'my_team_script', 
        'myAjax',
         array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'action'=>'my_poll_srt')
    );           
}


//  wp_enqueue_script for Admin

function my_teamadminscripts_main() {

    wp_enqueue_style( 'poll_style', plugins_url( '/css/admin-style.css' , __FILE__ ) );
    wp_enqueue_style( 'lightbox', plugins_url( '/css/bootstrap-lightbox.min.css' , __FILE__ ) );
    wp_enqueue_script( 'jquery' );
     wp_enqueue_script(
        'bootstrap',
        plugins_url( '/js/bootstrap.min.js' , __FILE__ ),
        array( 'jquery' )
    );
          
}


//  Create hook for admin bootstrap

add_action('bootsrtap_hook', 'add_bootstrap_team_admin');

function add_bootstrap_team_admin()
{
    echo '<link rel="stylesheet" href="'. plugins_url( '/css/bootstrap.min.css' , __FILE__ ).'">';
}



// Incude some action File 

include_once('frankly_team.php');

include_once('embed-shortcode.php');


ob_flush();



?>