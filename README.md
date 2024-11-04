# ![DiViMS logo](https://www.arawa.fr/wp-content/uploads/2022/11/logo-divims-1-90x90.png.webp) DiViMS - a BigBlueButton orchestrator

DiViMS (or divims) is an opensource orchestrator for [BigBlueButton](https://docs.bigbluebutton.org/) and [Scalelite](https://github.com/blindsidenetworks/scalelite)

It allows auto-scaling your BBB infrastructure according to the observed load or a forecasted schedule and simultaneously reducing your hosting costs.

Currently compatible with [Scaleway](https://www.scaleway.com) hosting.

<p align="center">
<img src="https://www.arawa.fr/wp-content/uploads/2023/06/presentation-arawa-divims.png.webp" alt="Divims visual explanation">
</p>

## How it works
A CRON job launches a Docker container every 5 minutes (recommended). This container runs a PHP7 app that connects to your Scalelite's pool :
- Queries Scalelite to retrieve load information (number of participants, meetings and load)
- Queries each BBB server for system and recordings processing information
- Makes decision on whether BBB servers should be halted or started
- Acts on hosting infrastructure (currently only Scaleway) to start (clone) or delete virtual machines
- Acts on Scalelite to enable, drain or disable BBB servers
- Sends warnings and alerts to an email address

You'll find a presentation of DiViMS at BBB World 2022 on Youtube : https://www.youtube.com/watch?v=S35ZNiOtaek

## Requirements

DiViM-s requires a pool of BigBlueButton servers managed by a [Scalelite](https://github.com/blindsidenetworks/scalelite) server.
DiViM-S code can reside on the same server as Scalelite : independence is guaranteed by Docker.

To run DiViM-S you should install these dependencies :

- docker-ce

## How to run
### Build the required docker image

```bash
docker build --tag php:parallel --build-arg PUID=$(id -u) --build-arg PGID=$(id -g) --build-arg USER=$(id -un) .
```

### Add a BBB pool

```
mkdir -p config/project/<pool-name>
cp config/config.default.php config/project/<pool-name>/config.php
```

Modify your pool's `config.php` to your needs

### Run
Modify `main.php` to your needs and start app :

```bash
$ docker container run --rm -v $(pwd):/app/ php:parallel php /app/main.php
```

## Troubleshooting

### Logger

You can use the `logger` class to print debug message on the docker console.

To enable debug mode, use `Logger::DEBUG` as second parameter of `pushHandler` method.

Example :

```php
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
```

## Additional Help

If you need assistance, require a further development (such as develop compatibility with your prefered cloud), you can contact Arawa to learn about professional services to support your project.

Please visit [project page on Arawa's website](https://www.arawa.fr/solutions/produits-arawa/arawa-divims-for-bigbluebutton/) (French content) or [contact us](https://www.arawa.fr/contact/).

## Sponsors

| <img src="https://www.education.gouv.fr/sites/default/files/site_logo/2024-09/MIN_Education_Nationale_et_Jeunesse_RVB.jpg" width="100"> | <a href="https://www.education.gouv.fr/direction-du-numerique-pour-l-education-dne-9983" alt="Site de la Direction du Numérique pour l'Éducation">Ministère de l'Éducation Nationale française (Direction du Numérique pour l'Éducation)</a> |
