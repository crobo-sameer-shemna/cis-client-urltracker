# cis-client-urltracker




show proxy details on country select


debug options

file explorer




Documentation:

install:
install tor
$ apt-get install tor

install phantomjs from source or copy ubuntu binary in /usr/bin

edit permissions for www-data : http://unix.stackexchange.com/questions/115054/php-shell-exec-permission-on-linux-ubuntu
$visudo
then add to end of file
www-data ALL=NOPASSWD: /usr/bin/lsof

copy all files to /opt/cis-client-urltracker

copy nginx conf file and restart nginx

web folder contains the web interface

tor folder contains the tor related files
  tor/config  to store generated config files for TOR (must be writable)
  tor/data    for tor to store its data for different ports (must be writable)
  tor/log     for tor to store its log files for different ports (must be writable)

phatomjs folder contains javascript files that will be executed by phantomjs headless Browser
  phantomjs/log  for tor to store its log files when debugging (must be writable)

we use multiple instances of tor proxy running on seperate ports for different countries


execute:

change settings.php

cd tor
php generate_config.php

this will generate all config files for tor in tor/config folder
and running commands in tor/tor_command.txt file


browsing the tool click on 'Reset all proxies' to start all tor proxy instances for countries
