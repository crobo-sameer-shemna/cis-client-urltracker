<?php

require_once('../settings.php');

function writeFile($filename, $txt){
  $myfile = fopen($filename, "w") or die("Unable to open file!");
  fwrite($myfile, $txt);
  fclose($myfile);
}

$exclude_nodes = [];
foreach($all_countries as $key => $value){
  $exclude_nodes[$key] = '{'.$key.'}';
}

$torcmd = '';
foreach($country_ports as $key => $value){
  $port = $value;
  $exclude_nodes_arr = $exclude_nodes;
  unset($exclude_nodes_arr[$key]);
  $exclude_nodes_string = implode(',',$exclude_nodes_arr);
  $text =
"SOCKSPort $port # what port to open for local application connections
SocksListenAddress 127.0.0.1 # accept connections only from localhost
Log notice file ".TOR_LOG_FOLDER."/".$key.".log
DataDirectory ".TOR_DATA_FOLDER."/".$key."
RunAsDaemon 1
#ExcludeNodes ".$exclude_nodes_string."
#EntryNodes {".$key."}
#ExcludeExitNodes ".$exclude_nodes_string."
ExitNodes {".$key."}
StrictNodes 1
CookieAuthentication 1";
  $config_file = TOR_CONFIG_FOLDER.DIRECTORY_SEPARATOR.$key;
  writeFile($config_file, $text);
  $cmd = TOR_PATH.' -f '.$config_file.' &'. PHP_EOL;
  $torcmd.= $cmd;
}

writeFile(TOR_COMMAND_FILE, $torcmd);
echo 'TOR Commands written to file: '.TOR_COMMAND_FILE;

?>
