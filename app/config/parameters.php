<?php declare(strict_types=1);

if (!function_exists('envOrDefault')) {
    function envOrDefault (string $envName, $default = '')
    {
        if (isset($_ENV[$envName])) {
            return $_ENV[$envName];
        }

        return isset($_SERVER[$envName]) ? $_SERVER[$envName] : $default;
    }
}

$parameters = [
    'database_driver'   => envOrDefault('NFP_DB_DRIVER', 'pdo_sqlite'),
    'database_host'     => envOrDefault('NFP_DB_HOST', '127.0.0.1'),
    'database_port'     => envOrDefault('NFP_DB_PORT', '~'),
    'database_name'     => envOrDefault('NFP_DB_NAME', 'nfp'),
    'database_user'     => envOrDefault('NFP_DB_USER', 'root'),
    'database_password' => envOrDefault('NFP_DB_PASSWORD', ''),
    'database_path'     => envOrDefault('NFP_DB_PATH', '%kernel.root_dir%/nfp.sqlite3'),

    'mailer_transport'      => envOrDefault('NFP_MAILER_TRANSPORT', 'smtp'),
    'mailer_host'           => envOrDefault('NFP_MAILER_HOST', 'mail'),
    'mailer_port'           => envOrDefault('NFP_MAILER_PORT', 25),
    'mailer_user'           => envOrDefault('NFP_MAILER_USER', 'root@localhost'),
    'mailer_password'       => envOrDefault('NFP_MAILER_PASSWD', '~'),
    'mailer_encryption'     => envOrDefault('NFP_MAILER_ENCRYPTION', 'tls'),
    'mailer_sender_address' => envOrDefault('NFP_MAILER_SENDER_ADDRESS', 'anarchist-notifier@localhost'),

    'secret' => envOrDefault('NFP_SECRET', 'ThisTokenIsNotSoSecretChangeIt'),

    // Wolnościowiec File Repository integration (for cloud storage)
    'file_repository_url'        => envOrDefault('NFP_FILE_REPO_URL', 'https://example.org'),
    'file_repository_cache_name' => envOrDefault('NFP_FILE_REPO_CACHE_NAME', 'Void'), // Doctrine cache name, examples: Apcu, Void, PhpFile, Redis, Memcache, Memcached, Filesystem
    'file_repository_enabled'    => (bool) envOrDefault('NFP_FILE_REPO_ENABLED', false),
    'file_repository_tag'        => envOrDefault('NFP_FILE_REPO_TAG', 'news-feed-provider'),
    'file_repository_token'      => envOrDefault('NFP_REPO_TOKEN', 'xxx'),

    // health checks api key
    'monitoring_api_key' => envOrDefault('NFP_MONITORING_KEY', 'super-secret-api-key'),

    // Wolnościowiec WebProxy integration (for scrapping using a proxy)
    'webproxy_url'                => envOrDefault('NFP_WEB_PROXY_URL', ''),
    'webproxy_passphrase'         => envOrDefault('NFP_WEB_PROXY_PASSPHRASE', ''),
    'webproxy_process'            => (bool) envOrDefault('NFP_PROCESS_INTERNAL_LINKS', true),
    'webproxy_expiration_minutes' => (int) envOrDefault('NFP_EXPIRATION_TIME_MINUTES', 1),
    'webproxy_enabled_ssl'        => (bool) envOrDefault('NFP_ENABLED_SSL', false)

];

foreach ($parameters as $name => $value) {
    $container->setParameter($name, $value);
}
