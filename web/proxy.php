<?php
require_once('../settings.php');
$projectfolderpath = BASE_PATH;

$country = isset($_GET['country']) ? $_GET['country'] : $default_country;
$device = isset($_GET['device']) ? $_GET['device'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
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

$cmd = 'killall tor';
$output = shell_exec($cmd);
//echo "<pre>$output</pre>";
//exit;

if($country !== 'DE'){
  $configpath = $projectfolderpath.'/web/tor/'.$country;
  $cmd = "/opt/local/bin/tor -f $configpath";
  //echo $cmd; exit;
  $output = shell_exec($cmd);
  //echo "<pre>$output</pre>";
  echo '1';
}else{
  echo '0';
}




// /opt/local/bin/tor -f /Users/sameer/www/test/phantom/scraper/urltracker/web/tor/US --DataDirectory /Users/sameer/www/test/phantom/scraper/urltracker/web/tor/data/US
// /opt/local/bin/tor -f /Users/sameer/www/test/phantom/scraper/urltracker/web/tor/AU --DataDirectory /Users/sameer/www/test/phantom/scraper/urltracker/web/tor/data/AU

?>
