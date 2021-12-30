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
./vendor/bin/sail up -d
```

The command above will create the two Docker containers and start them. It might take a minute or two, as it has to create the images needed for the containers on the first run. But after that, starting up both containers should take very little time.

You should now be able to connect to the DB using your MySQL client of choice (host: 127.0.0.1, port: 3306, username: sail, password: password) and see that there is an empty schema already created called github_popular_php_repositories.

You can pass the Laravel Docker container commands using Sail. This method can be used for anything with Composer, Node, and any other Artisan commands. You can test this (to show a list of the available Artisan commands) using:

```
./vendor/bin/sail artisan list
```

We will utilize one of these commands to run a Migration that will create the repositories table (this migration is defined in /app/database/migrations/2021_12_21_000000_create_repositories_table.php). The command to run all Migrations is:

```
./vendor/bin/sail artisan migrate
```

The migration will create three new tables in the DB, including the one we care most about - repositories.

Finally, let's install Node, it's dependencies, and compile everything needed for the front end. I have committed all of the compiled assets just in case, but they will be overwritten with the compile and that's OK.

```
./vendor/bin/sail npm install
```

```
./vendor/bin/sail npm run dev
```

You are now free to hit http://localhost in your browser of choice.
