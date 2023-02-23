<?php

class BridTV_Subscribers_Data  {


  public $apikey, $listId, $server, $campaignId;

  public function __construct()
    {
      $this->apikey = 'da306ae74ac92a4397a5327d4f6dc90c-us6';
      $this->listId = '8871e5e6b5';
      $this->server = substr($this->apikey,strpos($this->apikey,'-')+1); // us5, us6, us8...
      $this->campaignId = "30a6a6a2ea";
    }


  public function getAllSubscribers()
  {

    $args = array(
     	'headers' => array(
    		'Authorization' => 'Basic ' . base64_encode( 'user:'. $this->apikey )
    	)
    );

    // connect
    $response = wp_remote_get( 'https://'.$this->server.'.api.mailchimp.com/3.0/lists/'.$this->listId, $args );

    // decode the response
    $body = json_decode( $response['body'] );

    if ( $response['response']['code'] == 200 ) {

    	// subscribers count
    	$member_count = $body->stats->member_count;
    	$emails = array();
      $members = array(
        'email' => '',
        'status' => '',
        'first_name' => '',
        'last_name' =>''
      );

      $subscribers = array();

    	for( $offset = 0; $offset < $member_count; $offset += 50 ) {

    		$response = wp_remote_get( 'https://'.$this->server.'.api.mailchimp.com/3.0/lists/'.$this->listId.'/members?offset=' . $offset . '&count=50', $args );
    		// decode the result
    		$body = json_decode( $response['body'] );

    		if ( $response['response']['code'] == 200 ) {
    			foreach ( $body->members as $member ) {

            $members['email'] = $member->email_address;
            $members['status'] = $member->status;
            $members['first_name'] = $member->merge_fields->FNAME;
            $members['last_name'] = $member->merge_fields->LNAME;

            array_push($subscribers, $members);

    				//$emails[] = $member->email_address;
    			}
    		}
    	}
    }

    header( "Content-Type: application/json" );
    echo json_encode($subscribers);
    wp_die();

  }


  public function sendCampaign()
  {

    if(isset($_POST['campaignId'])){
        $this->campaignId = $_POST['campaignId'];
    }

    $apiKey = $this->apikey;

    $auth = base64_encode( 'user:'.$this->apikey );
    $url = 'https://'. $this->server . '.api.mailchimp.com/3.0/campaigns/' . $this->campaignId.'/actions/send' ;

    $jsonEmail = '{"test_emails":["the mail you want to send thing sat"],"send_type":"html"}';


    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$auth));
    curl_setopt($ch, CURLOPT_USERPWD, 'apikey:'.$apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonEmail);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        $msg = array("msg" => $error_msg);
      }else{
        $msg = array("msg" => "You are successfully unsubscribed from BRID.TV Newsletter");
      }

    header( "Content-Type: application/json" );
    echo json_encode($msg);
    wp_die();

  }


  public function getAllCampaigns()
  {
        // Get ALl campaigns data
        $args = array(
          'headers' => array(
            'Authorization' => 'Basic ' . base64_encode( 'user:'. $this->apikey )
          )
        );

        // connect
        $response = wp_remote_get( 'https://'.$this->server.'.api.mailchimp.com/3.0/campaigns/', $args );

        // decode the response
        $campaigns = json_decode( $response['body'] );

        $campaignArr = [
          'campaign_id' => '',
          'campaignTitle' => ''
        ];

        $campaignData = [];

        foreach ($campaigns as $key => $campaign) {
            if(is_array($campaign) ){
              foreach ( $campaign as $obj ) {
                if(isset($obj->status) && $obj->status == 'save'){

                    $campaignArr['campaign_id'] = $obj->id;
                    $campaignArr['campaignTitle'] = $obj->settings->title;
                //  var_dump("<pre>", $obj->settings->title);
                //  var_dump("<pre>", $obj->id);

                  array_push($campaignData, $campaignArr);
                }
              }
            }
        }

        header( "Content-Type: application/json" );
        echo json_encode($campaignData);
        wp_die();
  //  var_dump("<pre>", $campaignData);die("OPAAA!");
}


//Umsubscribe user from mailchip newsletter
public function unsubscribeFromNewsletter()
{

  $subscirberEmail = $_POST['subscirberEmail'];

  if (! is_email( $subscirberEmail ) ) {
      return array(
        "msg" => "Not valid email address!"
      );
  }

  $apiKey = $this->apikey;

  $subscriber_hash = md5($subscirberEmail);

  $auth = base64_encode( 'user:'.$this->apikey );
  $url = 'https://'. $this->server . '.api.mailchimp.com/3.0/lists/' . $this->listId.'/members/'.$subscriber_hash; 
  //lists/{list_id}/members/{subscriber_hash}/actions/delete-permanent za brisanje membera

  $postFields = array();

  $data = array(
             'apikey'        => $apiKey,
             'email_address' => $subscirberEmail,
             'status'        => 'unsubscribed',
             'email_address' => $subscirberEmail
         );
  $json_data = json_encode($data);

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$auth));
  curl_setopt($ch, CURLOPT_USERPWD, 'apikey:'.$apiKey);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");  // Ako koristimo za update membera - Za unsubscribe. Ne treba za brisanje.

  $result = curl_exec($ch);

  if (curl_errno($ch)) {
      $error_msg = curl_error($ch);
        $msg = array("msg" => $error_msg);
  }else{
    $msg = array("msg" => "You are successfully unsubscribed from BRID.TV Newsletter");
  }

    header( "Content-Type: application/json" );
    echo json_encode($msg);
    wp_die();

}


}
