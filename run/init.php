<?php

//composer
include_once '/app/lib/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    include '/app/lib/' . str_replace('\\', '/', $class_name) . '.php';
});

use DiViMS\Config;

// https://github.com/Seldaek/monolog
// composer require monolog/monolog
use Monolog\Logger;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\FilterHandler;

function setDefaultLogEmailHandlers(Config $config, Logger $logger, string $project) {

    $mail_to = $config->get('log_mail_to');
    $mail_from = $config->get('log_mail_from');

    // Mail only warning logs every day
    $logger->pushHandler(
        new FilterHandler(
            new DeduplicationHandler(
                new NativeMailerHandler($mail_to, "Warning : DiViM-S $project", $mail_from, \Monolog\Level::Warning),
                "/app/tmp/$project" . "_email_warning.log", \Monolog\Level::Warning, $config->get('log_mail_warning_frequency_minutes') * 60
            ),
            \Monolog\Level::Warning, \Monolog\Level::Warning
        )
    );
    // Mail error logs every 8 hours
    $logger->pushHandler(
        new FilterHandler(
            new DeduplicationHandler(
                new NativeMailerHandler($mail_to, "Error : DiViM-S $project", $mail_from, \Monolog\Level::Error),
                "/app/tmp/$project" . "_email_error.log", \Monolog\Level::Error, $config->get('log_mail_error_frequency_minutes') * 60
            ),
            \Monolog\Level::Error, \Monolog\Level::Error
        )
    );

    // Mail critical and alerts logs every hour
    $logger->pushHandler(
        new DeduplicationHandler(
            new NativeMailerHandler($mail_to, "Critical : DiViM-S $project", $mail_from, \Monolog\Level::Critical),
            "/app/tmp/$project" . "_email_critical.log", \Monolog\Level::Critical, $config->get('log_mail_critical_frequency_minutes') * 60
        )
    );
}

?>