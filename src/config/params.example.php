<?php

declare(strict_types=1);

return [
    'app' => [
        'cookieValidationKey' => '${APP_COOKIE_VALIDATION_KEY}',
    ],
    'MySQL' => [
        'database' => '${APP_MYSQL_DB}',
        'host' => '${APP_MYSQL_HOST}',
        'username' => '${APP_MYSQL_USER}',
        'password' => '${APP_MYSQL_PASS}',
        'port' => (int) '${APP_MYSQL_PORT}',
    ],
    'reCaptcha' => [
        'siteKey' => '${APP_RECAPTCHA_SITE_KEY}',
        'secretKey' => '${APP_RECAPTCHA_SECRET_KEY}',
    ],
];
