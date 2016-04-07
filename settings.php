<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', __DIR__);
define('TOR_FOLDER', BASE_PATH.DIRECTORY_SEPARATOR.'tor');
define('TOR_CONFIG_FOLDER', TOR_FOLDER.DIRECTORY_SEPARATOR.'config');
define('TOR_LOG_FOLDER', TOR_FOLDER.DIRECTORY_SEPARATOR.'log');
define('TOR_DATA_FOLDER', TOR_FOLDER.DIRECTORY_SEPARATOR.'data');
define('TOR_COMMAND_FILE', TOR_FOLDER.DIRECTORY_SEPARATOR.'tor_command.txt');


if(PHP_OS == 'Linux'){
  define('TOR_PATH', '/usr/sbin/tor');//Ubuntu Linux
}else{
  define('TOR_PATH', '/opt/local/bin/tor');//Mac
}

$enabled_countries = ['US', 'DE', 'IN','AU', 'FR', 'HK', 'TW', 'RU', 'GB', 'MY', 'SG', 'ZA', 'IE', 'IN', 'CA', 'CH', 'NO', 'NZ',
                      'SA', 'ES', 'RO', 'JP', 'BR', 'KR'
                    ];
$default_country = 'DE';
$server_country = 'DE';

require_once(BASE_PATH.'/countries.php');
require_once(BASE_PATH.'/common.php');

$devices = [
  'Apple_iPhone_5',
  'Apple_iPad',
  'Google_Nexus_5',
  'Google_Nexus_7',
  'Samsung_Galaxy_S_3',
  'Samsung_Galaxy_Note_2',
  'MacOS_Chrome',
  'MacOS_Firefox',
  'Linux_Chrome'
];

?>
