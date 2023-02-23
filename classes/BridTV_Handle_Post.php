<?php

class BridTV_Handle_Post {

  private $name, $last_name, $email, $apikey, $listId, $server;

  public function __construct(){

    $this->name = $this->bridtv_sanitize_field($_POST['name']);
    $this->last_name = $this->bridtv_sanitize_field($_POST['last_name']);
    $this->email = $this->bridtv_sanitize_field($_POST['email']);
    $this->apikey = '';
    $this->listId = '';
    $this->server = substr($this->apikey,strpos($this->apikey,'-')+1); // us5, us6, us8...

    $this->_addNewSubscriberToMailchimp();

  }

  //adding subscriber to a list mailchimp
  private function _addNewSubscriberToMailchimp()
  {

    if (! is_email( $this->email ) ) {
        $this->bridtv_safe_redirect('bridtvError', 'Incorrect email address');
    }

    if( $this->name == '' || $this->last_name == '' ) {
        $this->bridtv_safe_redirect('bridtvError', 'First and last name are mandatory fields!');
    }

    $auth = base64_encode( 'user:'.$this->apikey );

    $data = array(
        'apikey'        => $this->apikey,
        'email_address' => $this->email,
        'status'        => 'subscribed',
        'merge_fields'  => array(
            'FNAME' => $this->name,
            'LNAME' => $this->last_name
        )
    );
    $json_data = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://'.$this->server.'.api.mailchimp.com/3.0/lists/'.$this->listId.'/members/');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$auth));
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    switch ( $httpCode ) {
      case 200:
        $this->bridtv_safe_redirect('bridtvSuccess', 'You are successfully subscribed to our newsletter');
        break;
        case 214:
          $this->bridtv_safe_redirect('bridtvError', 'You are already subscribed.');
          break;
          case 400:
            $this->bridtv_safe_redirect('bridtvError', 'Something went wrong. Please try again later');
            break;
      }

  }


  public function bridtv_safe_redirect($type, $msg)
  {
      $location = '';

      switch ($type) {
        case 'bridtvSuccess':
          $location = $_SERVER['HTTP_REFERER']."?bridtvSuccess={$msg}";
          break;
        case 'bridtvError':
         $location = $_SERVER['HTTP_REFERER']."?bridtvError={$msg}";
          break;
        default:
        $location = $_SERVER['HTTP_REFERER'];
      }

      wp_safe_redirect($location);
      exit(0);

  }



  // Form Sanitize
  public function bridtv_sanitize_field($input)
  {
      return trim(stripslashes(sanitize_text_field($input)));
  }



}
