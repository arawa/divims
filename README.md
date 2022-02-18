# DiViM-S
Dynamic VM Scheduler

## Requirements

You should install these dependancies :

- docker-ce

Project makes use of PHP/parallel library
- Documentation : https://www.php.net/parallel
- Development Repository : https://github.com/krakjoe/parallel/tree/release
- Installation : https://pecl.php.net/package/parallel

## First run : compile docker container

```bash
docker build --tag php:parallel .
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