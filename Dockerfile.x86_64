FROM wolnosciowiec/docker-php-app

ENV APP_ENV="prod" \
    NFP_DB_DRIVER="pdo_sqlite" \
    NFP_DB_HOST="db" \
    NFP_DB_PORT="~" \
    NFP_DB_NAME="notification" \
    NFP_DB_USER="root" \
    NFP_DB_PASSWORD="" \
    NFP_MAILER_TRANSPORT="smtp" \
    NFP_MAILER_HOST="mail" \
    NFP_MAILER_PORT="25" \
    NFP_MAILER_USER="root@nfp" \
    NFP_MAILER_PASSWD="" \
    NFP_MAILER_ENCRYPTION="tls" \
    NFP_MAILER_SENDER_ADDRESS="" \
    NFP_SECRET="app-secret-here" \
    NFP_DOCTRINE_CACHE="array" \
    NFP_FILE_REPO_ENABLED=0 \
    NFP_FILE_REPO_URL="https://example.org" \
    NFP_FILE_REPO_CACHE_NAME="Void" \
    NFP_FILE_REPO_TAG="news-feed-provider" \
    NFP_REPO_TOKEN="xxx" \
    NFP_MONITORING_KEY="super-secret-api-key" \
    NFP_WEB_PROXY_URL="" \
    NFP_WEB_PROXY_PASSPHRASE="" \
    NFP_PROCESS_INTERNAL_LINKS=1 \
    NFP_EXPIRATION_TIME_MINUTES=1 \
    NFP_ENABLED_SSL=1

ADD ./docker-entrypoint.sh /docker-entrypoint.sh
ADD . /var/www/html

RUN cd /var/www/html && make build_docker_image

ENTRYPOINT ["/docker-entrypoint.sh"]
