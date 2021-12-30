## PHP Popular GitHub Repositories

Hello, friends, and thanks again for your consideration of me for this position.

This is a Laravel application, built with the most recent version of Laravel (at this writing), PHP 8.1, and Vue 2.6 / Bootstrap 5 for the front end. This application was built using Docker containers (one for the DB, and one for the application), using Laravel Sail as a base starting point to build these containers.

## Installation

First, ensure that you either have Docker and Docker Compose or Docker Desktop (it comes with both) on your computer. I used Docker Desktop on an Intel Chip Mac. https://www.docker.com/products/docker-desktop

Then, clone this repository into a directory of choice.

Within the directory that you've cloned the repository in, spin up a helper Docker container that will install all of our Composer dependencies (including Laravel Sail), and then remove itself. The command to do such a thing (using PHP 8.1) is below:

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

After the helper Docker container is complete installing everything, starting both containers for the application should be as easy as running this command in the directory:

```
./vendor/bin/sail up
```

The command above will create the two Docker containers and start them. It might take a minute or two, as it has to create the images needed for the containers on the first run. But after that, starting up both containers should take very little time.
