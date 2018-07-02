#!/usr/bin/env bash

cd /var/www/html
make migrate

exec /entrypoint.sh
