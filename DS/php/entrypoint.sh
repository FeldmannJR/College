#!/bin/sh
chmod -R 777 /sites/he/storage/
cron -f & docker-php-entrypoint php-fpm
