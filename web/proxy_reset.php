<?php

require_once('../settings.php');
$country = isset($_GET['country']) ? $_GET['country'] : '';

$reply = array();

$reply['output'] = array();

if($country == ''){//for all countries
  $cmd = 'killall -w -v -uwww-data tor';
  $reply['message'] = 'Proxies reset successfully for all countries.';
}else{
  $port = $country_ports[$country];
  //$cmd = '/bin/fuser -k '.$port.'/tcp';
  //$cmd = "netstat -ap | grep :$port";
  //$cmd = "kill -9 $(fuser -n tcp $port 2> /dev/null)";

  //$cmd = "kill -9 12541";

  //edit permissions for www-data : http://unix.stackexchange.com/questions/115054/php-shell-exec-permission-on-linux-ubuntu
  //$visudo
  //then add to end of file
  //www-data ALL=NOPASSWD: /usr/bin/lsof
  //$cmd = "sudo /usr/bin/lsof -t -i:$port";
  $cmd = "kill -9 $(sudo /usr/bin/lsof -t -i:$port)";
  $reply['message'] = 'Proxies reset successfully for country: '.$country;
}
//echo $cmd;
//exit;
$output = shell_exec($cmd);
$reply['output'][$cmd] = $output;
//echo "<pre>$output</pre>";
//exit;



$handle = fopen(TOR_COMMAND_FILE, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
      $cmd = $line;
      //echo $cmd . PHP_EOL;
      if(($country == '')//for all countries
        ||(stringContains($cmd, '/'.$country.' &'))){
        $output = shell_exec($cmd);
        $reply['output'][$cmd] = $output;
      }
      //echo "<pre>$output</pre>";
    }

    fclose($handle);
    $reply['success'] = 1;
    $reply['error'] = '';
} else {
  $reply['success'] = 0;
  $reply['error'] = 'Cannot open file: '.TOR_COMMAND_FILE;
  $reply['message'] = 'Proxies reset failed please try again.';
}
header('Content-Type: application/json');
echo json_encode($reply);

?>
