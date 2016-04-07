<?php

require_once('../settings.php');
$projectfolderpath = BASE_PATH;

$reply = array();

$country = isset($_GET['country']) ? $_GET['country'] : $default_country;
$device = isset($_GET['device']) ? $_GET['device'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$debug = isset($_GET['debug']) ? $_GET['debug'] : '0';

if($device == ''){
  echo '<p class="text-error">Please select Device</p>';
  exit;
}
if($url == ''){
  echo '<p class="text-error">Please enter URL</p>';
  exit;
}
//phantomjs urltracker.js 'Apple_iPad' 'http://tracking.blindferretmedia.com/aff_c?offer_id=12453&aff_id=1202'

$proxy = '';

if($country !== $server_country){
  $port = $country_ports[$country];
  $proxy = ' --proxy=127.0.0.1:'.$port.' --proxy-type=socks5 ';
}

$scriptpath = $projectfolderpath.'/phantomjs/urltracker.js';
$cmd = "phantomjs $proxy $scriptpath '$device' '$url'";
if($debug == 1){
	echo $cmd;
	exit;
}else{
	$output = shell_exec($cmd);
	//echo "<pre>$output</pre>";
  $reply['command'] = $cmd;
  $reply['output'] = $output;
}
header('Content-Type: application/json');
echo json_encode($reply);

?>
