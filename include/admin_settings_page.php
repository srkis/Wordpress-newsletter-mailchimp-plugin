<?php
require_once( ABSPATH . 'wp-admin/includes/template.php' );
include_once( WP_PLUGIN_DIR . '/bridtv_newsletter/classes/BridTV_Render_Html.php' );

/**
 *
 */
class BridTV_Newsletter {

  public $html_render, $url;

  function __construct()
  {

    $this->url = home_url() . "/" . "wp-admin/admin-post.php";
    $this->html_render = new BridTV_Render_Html($this->url);
    $this->init_bridtv_admin_page();
  }


public function init_bridtv_admin_page()
{
  add_action('admin_menu', array($this, 'bridtv_admin_page' ) );

}

public function bridtv_admin_page(){

    add_menu_page('BridTV', 'BridTV newsletter ', 'manage_options','bridtv-newsletter-options', array($this, 'bridtv_settings_page'), 'dashicons-megaphone' );
}

public function bridtv_settings_page() {

    echo $this->html_render->render_bridtv_admin_page();
}

}
$bridTv = new BridTV_Newsletter();


 ?>
