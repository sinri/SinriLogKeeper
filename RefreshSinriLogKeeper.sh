#!/bin/bash

if [ `sudo echo ROOT_OK` != 'ROOT_OK' ] 
then
	echo "You are not in sudo list. Exit."
	exit 
fi

if [ ! -d "/var/www/SinriLogKeeper/.git" ]; then
	echo 'Install SinriLogKeeper...'
	sudo mkdir -p /var/www/
	cd /var/www/
	sudo git clone https://github.com/sinri/SinriLogKeeper.git
else
	echo 'Update SinriLogKeeper...'
	cd /var/www/SinriLogKeeper
	sudo git pull
fi

if [ -f '/var/www/SinriLogKeeper/slk.config' ] 
then
	echo 'Fix the security issue'
	sudo mv /var/www/SinriLogKeeper/slk.config /var/www/SinriLogKeeper/slk.config.php 
fi

echo 'Script ending!'