#!/bin/bash

action=$1

if [ "$action" == "install" ] || [ "$action" == "" ]; then
	composer install
	npm install
fi

if [ "$action" == "start" ] || [ "$action" == "" ]; then
	php -S localhost:3001 -t web ../api/index.php &
	npm start &
fi

wait
