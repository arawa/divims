<?php

//composer
include_once '/app/lib/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    include '/app/lib/' . str_replace('\\', '/', $class_name) . '.php';
});

// https://github.com/Seldaek/monolog
// composer require monolog/monolog
use Monolog\Logger;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\FilterHandler;

function setDefaultLogEmailHandlers(Logger $logger, string $mail_to, string $mail_from, string $project) {

    // Mail only warning logs every day
    $logger->pushHandler(
        new FilterHandler(
            new DeduplicationHandler(
                new NativeMailerHandler($mail_to, "Warning : DiViM-S $project", $mail_from, \Monolog\Level::Warning),
                "/app/tmp/$project" . "_email_warning.log", \Monolog\Level::Warning, 24*3600
            ),
            \Monolog\Level::Warning, \Monolog\Level::Warning
        )
    );
    // Mail error logs every 8 hours
    $logger->pushHandler(
        new FilterHandler(
            new DeduplicationHandler(
                new NativeMailerHandler($mail_to, "Error : DiViM-S $project", $mail_from, \Monolog\Level::Error),
                "/app/tmp/$project" . "_email_error.log", \Monolog\Level::Error, 8*3600
            ),
            \Monolog\Level::Error, \Monolog\Level::Error
        )
    );

    // Mail critical and alerts logs every hour
    $logger->pushHandler(
        new DeduplicationHandler(
            new NativeMailerHandler($mail_to, "Critical : DiViM-S $project", $mail_from, \Monolog\Level::Critical),
            "/app/tmp/$project" . "_email_critical.log", \Monolog\Level::Critical, 3600
        )
    );
}

?>