<?php
/**
*Plugin Name: Brid TV Newsletter plugin
*Plugin URI: https://github.com/srkis
*Description: Newsletter plugin for Brid TV
*Author: Srdjan Stojanovic
*Version: 1.0
*Author URI: http://www.srdjan.icodes.rocks/
*/

include_once(ABSPATH . 'wp-includes/pluggable.php');
include_once dirname( __FILE__ ) . '/includes/admin_settings_page.php';
include_once dirname( __FILE__ ) . '/classes/BridTV_Handle_Post.php';
include_once dirname( __FILE__ ) . '/classes/BridTV_Render_Html.php';
include_once dirname( __FILE__ ) . '/classes/BridTV_Subscribers_Data.php';
require_once(ABSPATH . 'wp-load.php');


function brid_tv_newsletter_form_plugin()
{
  $render_html = new BridTV_Render_Html('');
  $html_content = $render_html->render_newsletter_form();

    return $html_content;
}

add_shortcode('brid_tv_newsletter', 'brid_tv_newsletter_form_plugin');


if(isset($_POST['action']) && $_POST['action'] == 'brid_tv_newsletter' ){
  $handlePost = new BridTV_Handle_Post($_POST);
}

if(isset($_POST['action']) && $_POST['action'] == 'send_campaign' ){
  $subscribersData = new BridTV_Subscribers_Data();
  $subscribersData->sendCampaign();
}


if(isset($_POST['action']) && $_POST['action'] == 'get_all_campaigns' ){
  header('Access-Control-Allow-Origin: *');
  $subscribersData = new BridTV_Subscribers_Data();
  $subscribersData->getAllCampaigns();
}


//Unsubscribe
if(isset($_POST['action']) && $_POST['action'] == 'unsubscribe' ){
  $subscribersData = new BridTV_Subscribers_Data();
  $subscribersData->unsubscribeFromNewsletter();
}


if(ExactBrowserName() === 'Firefox') {
  add_action( 'wp_ajax_nopriv_get_subscribers', 'getSubscribers' );
}else{

  add_action( 'wp_ajax_get_subscribers', 'getSubscribers' );
}


function getSubscribers()
{

  if(isset($_POST['action']) && $_POST['action'] == 'get_subscribers'){

    $subscribersData = new BridTV_Subscribers_Data();
    $subscribersData->getAllSubscribers();
  }

}


function ExactBrowserName()
{

$ExactBrowserNameUA=$_SERVER['HTTP_USER_AGENT'];

if (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")) {
    // OPERA
    $ExactBrowserNameBR="Opera";
} elseIf (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "chrome/")) {
    // CHROME
    $ExactBrowserNameBR="Chrome";
} elseIf (strpos(strtolower($ExactBrowserNameUA), "msie")) {
    // INTERNET EXPLORER
    $ExactBrowserNameBR="Internet Explorer";
} elseIf (strpos(strtolower($ExactBrowserNameUA), "firefox/")) {
    // FIREFOX
    $ExactBrowserNameBR="Firefox";
} elseIf (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")==false and strpos(strtolower($ExactBrowserNameUA), "chrome/")==false) {
    // SAFARI
    $ExactBrowserNameBR="Safari";
} else {
    // OUT OF DATA
    $ExactBrowserNameBR="OUT OF DATA";
}

return $ExactBrowserNameBR;
}

function bridtv_admin_js()
{
  wp_register_script('bridtv_admin_js', plugin_dir_url(__FILE__).'scripts/foradmin.js',  array('jquery'));
  wp_enqueue_script('bridtv_admin_js');
}


function bridtv_css() {

      wp_register_style( 'fontend-style', plugin_dir_url(__FILE__) .'css/fontend-styles.css', array());
      wp_enqueue_style( 'fontend-style' );

  }


function add_javascript(){
    wp_register_script('jquery-frontend', plugin_dir_url(__FILE__).'scripts/jquery-3.5.1.min.js',  array('jquery'));
    wp_enqueue_script('jquery-frontend');

    wp_register_script('frontendjs', plugin_dir_url(__FILE__).'scripts/frontend.js',  array('jquery'));
    wp_enqueue_script('frontendjs');
}


add_action('wp_enqueue_scripts', 'bridtv_css');
add_action('wp_footer', 'add_javascript');
add_action('admin_enqueue_scripts', 'bridtv_admin_js');
