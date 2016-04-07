<?php
require_once('../settings.php');
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
            .a_btn{cursor:pointer;}
            iframe.frame{
              width:100%;
              border: none;
            }
            div.container_centre{
              text-align: center;
            }
            #img_flag{
              margin: auto;
              max-width: 200px;
            }
            #map_canvas {
              width: 100%;
              height: 400px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand"  href="#" id="a_resetproxies">Reset All Proxies</a>
          <a class="navbar-brand"  href="#" id="a_resetcountry">Reset Country Proxy</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <form id="url_track">
          <div class="form-group">
            <label for="url">Country</label>
            <select class="form-control" id="country" name="country">
              <option value="">Select Country</option>
              <?php
                foreach($countries as $key => $value){
                  $sel = ($key==$default_country)?'selected="selected"':'';
                  echo '<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
                }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="url">Device</label>
            <select class="form-control" id="device" name="device">
              <option value="">Select Device</option>
              <?php
                foreach($devices as $device){
                  echo '<option value="'.$device.'">'.str_replace('_', ' ', $device).'</option>';
                }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="url">URL</label>
            <input type="text" class="form-control" id="url" placeholder="Enter URL" name="url">
          </div>
          <button type="submit" class="btn btn-default">Submit</button>

        </form>
      </div>
    </div>

    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <h3>URL Track result <i id="url_loading" class="fa fa-spinner fa-pulse" style="display:none;"></i></h3>
          <p id="result"></p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <h3>Proxy details <a id="a_proxyrefresh" class="a_btn" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh"></i></a><i id="proxy_details_loading" class="fa fa-spinner fa-pulse" style="display:none;"></i></h3>
          <div class="container_centre">
            <img id="img_flag" src="http://www.geonames.org/flags/x/us.gif"/>
          </div>
          <p id="proxy_details"></p>
          <div id="map_canvas"></div>
        </div>
      </div>

      <hr>

      <footer>
        <!--<p>&copy; 2015</p>-->
      </footer>
    </div> <!-- /container -->

<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="modal_b" style="display:none;">Open Modal</button>
    <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal_h"></h4>
      </div>
      <div class="modal-body">
        <p id="modal_p"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

				<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <script>
					$(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
						$('form#url_track').submit(function(e){
	            e.preventDefault();
	            urlSubmit();
	          });
						$('#a_resetproxies').click(function(e){
							e.preventDefault();
							resetProxy('');
						});
            $('#a_resetcountry').click(function(e){
							e.preventDefault();
							resetProxy($('#country').val());
						});
            $('#country').change(function(e){
							e.preventDefault();
							getProxyDetails();
						});
            $('#a_proxyrefresh').click(function(e){
							e.preventDefault();
							getProxyDetails();
						});
					});
          $(window).load(function(){
            getProxyDetails();
          });

          /**
           * Returns true if the user hit Esc or navigated away from the
           * current page before an AJAX call was done. (The response
           * headers will be null or empty, depending on the browser.)
           *
           * NOTE: this function is only meaningful when called from
           * inside an AJAX "error" callback!
           *
           * The 'xhr' param is an XMLHttpRequest instance.
           */
          function userAborted(error) {
            return error === 'abort';
          }

          function urlSubmit(){
            //getProxy();
            getBrowse();
          }
          function getProxy(){
            var url = './proxy.php';
            $.get(url, $('form#url_track').serialize(), function(data){
              getBrowse();
            });
          }
          function getBrowse(){
            $('p#result').html('');
            $('#url_loading').show();
            var url = './serve.php';
            $.get(url, $('form#url_track').serialize(), function(data){
              $('p#result').html('<pre>'+data.output+'</pre>');
              $('#url_loading').hide();
            });
          }
          var xhr_proxydetails = false;
          function userAbort(xhr){
            if(xhr){
              xhr.abort();
              xhr = false;
            }
          }
					function resetProxy(countrycode){
            userAbort(xhr_proxydetails);
            var url = './proxy_reset.php';
            $.get(url, {country: countrycode}, function(data){
              getProxyDetails();
              openModal('Proxy Reset', data.message);
            });
          }
          function getProxyDetails(){
            //$('p#result').html('');
            userAbort(xhr_proxydetails);
            $('p#proxy_details').html('');
            $('#proxy_details_loading').show();
            $('#map_canvas').html('');
            var url = './proxy_details.php';
            xhr_proxydetails = $.get(url, $('form#url_track').serialize(), function(data){
              $('p#proxy_details').html('<pre>'+data.result+'</pre>');
              setMap(data.latitude, data.longitude);
            }).done(function(data, status, error) {
              if(!userAborted(error))
                console.log('Proxy set for country: '+$('#country').val());
            })
            .fail(function(data, status, error) {
              if(!userAborted(error))
                openModal('Proxy Failed', 'Please try resetting the proxy for country: '+$('#country').val());
            })
            .always(function() {
              $('#proxy_details_loading').hide();
            });
            setFlag();
          }
          function openModal(header, message){
            $('#modal_h').html(header);
            $('#modal_p').html(message);
            $('#modal_b').click();
          }
          function setFlag(){
            var code = $('#country').val();
            $('#img_flag').attr('src', 'http://www.geonames.org/flags/x/'+code.toLowerCase()+'.gif');
          }
          function setMap(lat, lon){
            //$('#ifr_map').attr('src', 'https://google.com/maps/place/'+lat+','+lon+'/@'+lat+','+lon+',12z');
            initialize(lat, lon);
          }
        </script>

        <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script>
          function initialize(lat, lon) {
            var map_canvas = document.getElementById('map_canvas');
            var map_options = {
              center: new google.maps.LatLng(lat, lon),
              zoom:10,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(map_canvas, map_options)
          }
          //google.maps.event.addDomListener(window, 'load', initialize);
        </script>
    </body>
</html>
