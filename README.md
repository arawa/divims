# DiViM-S
Opensource orchestrator for [BigBlueButton](https://docs.bigbluebutton.org/) and [Scalelite](https://github.com/blindsidenetworks/scalelite)

Allows scaling your BBB infrastructure according to the observed load or a forecasted schedule and simultaneously reducing your hosting costs.

Currently compatible with [Scaleway](https://www.scaleway.com) hosting.


## How it works
A CRON job launches a Docker container every 5 minutes (recommended). This container runs a PHP7 app that connects to your Scalelite's pool :
- Queries Scalelite to retrieve load information (number of participants, meetings and load)
- Queries each BBB server for system and recordings processing information
- Makes decision on whether BBB servers should be halted or started
- Acts on hosting infrastructure (currently only Scaleway) to start (clone) or delete virtual machines
- Acts on Scalelite to enable, drain or disable BBB servers
- Sends warnings and alerts to an email address

You'll find a presentation of DiViM-S at BBB World 2022 on Youtube : https://www.youtube.com/watch?v=S35ZNiOtaek

## Requirements

You should install these dependancies :

- docker-ce

## First run : compile docker container

```bash
docker build --tag php:parallel --build-arg PUID=$(id -u) --build-arg PGID=$(id -g) --build-arg USER=$(id -un) .
```

## Add a project and configure

```
mkdir -p config/project/<project-name>
cp config/config.default.php config/project/<project-name>/config.php
```

Modify your project's `config.php` to your needs


# Execute this project

Modify `main.php` to your needs and start app :

```bash
$ docker container run --rm -v $(pwd):/app/ php:parallel php /app/main.php
```

# Troubleshooting

## Logger

The `logger` is a class that allows to log messages.

You can enable the debug mode with `Logger::INFO` in sencond parameter of the `pushHandler` method.

For example :

```php
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
```

# Sponsors

Ministère de l'Éducation Nationale française ([Direction du Numérique pour l'Éducation](https://www.education.gouv.fr/direction-du-numerique-pour-l-education-dne-9983)) 
