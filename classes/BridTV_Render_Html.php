<?php

class BridTV_Render_Html {

  public $url;
  function __construct($url)
  {
    $this->url = $url;

    $this->render_newsletter_form();
  }


  public function render_newsletter_form()
  {

      $alert = null;

      if(isset($_GET["bridtvSuccess"])){
          $alert .= '<div class="alert-success">'.$_GET["bridtvSuccess"].'</div>';
      }

      if(isset($_GET["bridtvError"])){
          $alert .= '<div class="alert-error">'.$_GET["bridtvError"].'</div>';
      }


    $newsletter_page  = '';
    $newsletter_page .= '

    <html lang="en">
    <head>
    

    </head>
    <body>
    <div class="container">

      <div class="row ">
        <div class="col">
          <div style="background-color:#fff;" class="jumbotron">

          <div class="text-left">

            '.$alert.'
          </div>

                <div class="page-wrapper ">
                    <div class="wrapper ">
                        <div class="card-6">
                            <div class="card-heading">
                            </div>
                            <div class="card-body">
                              <form id="parse_form"  method="post" action="'.$this->url.'" ">

                                  <input type="hidden" name="action" value="brid_tv_newsletter">

                                  <h4 class="title">Subscribe to BRID.TV newsletter</h4>
                                    <div class="form-row">
                                        <div class="name">Name <span style="color:red">*</span></div>
                                        <div class="value">
                                            <input class="input--style-6" type="text" id="name" name="name" placeholder="Enter your name">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="name">Last Name <span style="color:red">*</span></div>
                                        <div class="value">
                                            <input class="input--style-6" type="text" id="last_name" name="last_name" placeholder="Enter your last name">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="name">Email address <span style="color:red">*</span></div>
                                        <div class="value">
                                            <div class="input-group">
                                                <input class="input--style-6" type="email" name="email" placeholder="example@email.com">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer"><br>
                                    <div class="input-group">
                                    <input type="submit" class="button custom-class" value="Suscribe">

                                  </div>
                                  <br>
                                  <small>If you want to unsubscribe from our newsletter.</small><a href="javascript:;" id="show_unsubscribe_form">Click here</a>
                                 </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
      </div>


      <form id="unsubscribe_form" style="display:none;"  method="post" action="'.$this->url.'" ">

          <input type="hidden" name="action" value="unsubscribe_brid_tv_newsletter">

          <h4 class="title">Unsubscribe from BRID.TV newsletter</h4>

            <div class="form-row">
                <div class="name">Email address <span style="color:red">*</span></div>
                <div class="value">
                    <div class="input-group">
                        <input id="unsubscribe_email" class="input--style-6" type="email" name="unsubscribe_email" placeholder="example@email.com">
                    </div>
                </div>
            </div>
            <div class="card-footer"><br>
            <div class="input-group">
            <input type="submit" class="button custom-class" value="Unsubscribe">

          </div>
          <br>
          <small>If you want to subscribe for our newsletter.</small><a id="show_subscribe_form" href="javascript:;"> Click here</a>
         </div>

        </form>



        <div class="container">

          <div id="show_unsubscribe_msg" style="display:none;" class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

          </div>

        </div>
    </div>
    </body>';

		return $newsletter_page;

  }


  public function render_bridtv_admin_page()
  {

    $bridtv_admin_page = 

    '<!DOCTYPE html>
      <html lang="en">
      <head>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">

      <script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

      </head>

      <body>

    <div class="container">
      <h2>BridTV Subscribers list</h2>
      <p>Here you can see subscribers to BRID.TV newsletter</p>

      <table id="myTable" class="table row-border hover stripe order-column nowrap">
              <thead>
                <tr>
                  <th>Email</th>
                  <th>Status</th>
                  <th>First Name</th>
                  <th>Last Name</th>

                </tr>
              </thead>
            </table>
    </div>


    <div class="container">
      <h2>Available campaigns to send</h2>
      <p>Please choose campaign form the list for sending to subscribers</p>
      <form>
        <div class="form-group">
          <select class="form-control" id="campaign_select"> </select>

        </div>
        <button id="send_campaign" type="button" class="btn btn-info btn-md">Send Campaign</button>
      </form>
    </div>

<div class="container">

  <div id="show_success" style="display:none;" class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Success!</strong> Campaign successfully send.
  </div>

</div>

    </body>
    </html>';

return $bridtv_admin_page;

  }


}
