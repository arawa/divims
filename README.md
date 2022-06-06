# DiViM-S
Opensource orchestrator for [BigBlueButton](https://docs.bigbluebutton.org/)

Allows scaling your BBB infrastructure according to the observed load or a forecasted schedule and simultaneously reducing your hosting costs.

Compatible with [Scaleway](https://www.scaleway.com) hosting.

## Requirements

You should install these dependancies :

- docker-ce

## First run : compile docker container

```bash
docker build --tag php:parallel --build-arg PUID=$(id -u) --build-arg PGID=$(id -g) --build-arg USER=$(id -un) .
```

## Add a project

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
