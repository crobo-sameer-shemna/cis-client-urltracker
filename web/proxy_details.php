<?php

require_once('../settings.php');
$projectfolderpath = BASE_PATH;

$reply = array();

$country = isset($_GET['country']) ? $_GET['country'] : $default_country;
$device = isset($_GET['device']) ? $_GET['device'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$debug = isset($_GET['debug']) ? $_GET['debug'] : '0';

$proxy = '';

if($country !== $server_country){
  $port = $country_ports[$country];
  $proxy = ' --proxy=127.0.0.1:'.$port.' --proxy-type=socks5 ';
}

$scriptpath = $projectfolderpath.'/phantomjs/proxydetails.js';
$cmd = "phantomjs $proxy $scriptpath '$device' '$url'";
if($debug == 1){
	echo $cmd;
	exit;
}else{
	$output = shell_exec($cmd);
	//echo "<pre>$output</pre>";
  $reply['command'] = $cmd;
  $reply['output'] = $output;
  $jsonObject = json_decode($output);
  //var_dump($jsonObject->client);exit;
  $resultObject = $jsonObject->client;
  $result = 'IP Address: '.$resultObject->ip."\n";
  $result.= 'Location: ' . $resultObject->city . ', ' . $resultObject->region . ', ' . $resultObject->country . ', ' . $resultObject->zip_code."\n";
  $result.= 'TimeZone: ' . $resultObject->timezone . ' (' . $resultObject->latitude . ', ' . $resultObject->longitude . ')'."\n";
  $reply['latitude'] = $resultObject->latitude;
  $reply['longitude'] = $resultObject->longitude;

  $resultObject = $jsonObject->device;
  $result.= 'OS: '.$resultObject->OS."\n";
  $result.= 'Browser: '.$resultObject->BROWSER."\n";
  $result.= 'HTTP_USER_AGENT: '.$resultObject->HTTP_USER_AGENT."\n";
  $result.= 'REMOTE_ADDR: '.$resultObject->REMOTE_ADDR."\n";
  $result.= 'HTTP_CLIENT_IP: '.$resultObject->HTTP_CLIENT_IP."\n";
  $result.= 'HTTP_X_FORWARDED_FOR: '.$resultObject->HTTP_X_FORWARDED_FOR."\n";
  $result.= 'HTTP_X_REAL_IP: '.$resultObject->HTTP_X_REAL_IP."\n";
  $result.= 'IsTorExitPoint: '.$resultObject->IsTorExitPoint."\n";
  $reply['result'] = $result;
}
header('Content-Type: application/json');
echo json_encode($reply);

?>
