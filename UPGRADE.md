# Upgrade instructions

## [v3.7]
Enabling default log email handlers requires modifying file `main.php`
This file is usually located in `config/project/default` and symlinked in directories of the form `config/project/<project-name>`

- In this file remove log handlers entries similar to the ones below :

```php
$logger->pushHandler(
    new FilterHandler(
        new DeduplicationHandler(
            new NativeMailerHandler("tech+divims@arawa.fr", "Warning : DiViM-S $project", "ne-pas-repondre@arawa.fr", Logger::WARNING),
            "/app/tmp/${project}_email_warning.log", Logger::WARNING, 28800
        ),
        Logger::WARNING, Logger::WARNING
    )
);
$logger->pushHandler(
    new DeduplicationHandler(
        new NativeMailerHandler(["tech+divims@arawa.fr", "sup.visio-agents@ac-toulouse.fr"], "Error : DiViM-S $project", "ne-pas-repondre@arawa.fr", Logger::ERROR),
        "/app/tmp/${project}_email_error.log", Logger::ERROR, 3600
    )
);
```

- Replace this entries with a function call to `setDefaultLogEmailHandlers` located below `$config` definition.
```php
// Create config
$config = new Config($project, $logger);

setDefaultLogEmailHandlers($config, $logger, $project);
```

- Add the following statement to the "use" list :
```php
use \Monolog\Level;
```

- And replace deprecated constants of the form `Logger::INFO` with the new ones like `Level::Info`

- Add the following configuration parameters to `config/project/<project_name>/config.php`

```php
  //Mail adresses for sending logs
  'log_mail_to' => '', // A single address as a string or an array of addresses
  'log_mail_from' => '', // A single address
```