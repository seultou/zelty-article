# Installation
You have to set your environment variables, update info in your `.env` file or variables set in your server configuration.

Install all dependencies on your local machine by running the command `composer install`
For production environment you should run `composer install --no-dev` (so you will not install dev scripts, unit tests, etc)

To initialize the database of the project, run this command:
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

Just in case, you can check if mapping of your entities is doing the job with the command `php bin/console doctrine:mapping:info`


# Usage

First, check the folder `config/jwt` if the key-pair files are here. If the folder does not exist or the 2 files are missing, run this command: `php bin/console lexik:jwt:generate-keypair`. 

To quickly use the app, you can seed random data to have some stuff to play with by using the console command `php bin/console app:demo`. It will create few authors and for each one, few articles.
One has hardcoded info to use it quickly as a demo.

username: `admin`
password: `toto42`


Also, you will find a Postman collection at this place:
#### https://www.getpostman.com/collections/7a46418af36a93cb3823

So you can use it as a very simple client since no front exists at the moment…

First of all, use the API `Security &rarr; POST / Loginto` to get a token. It will be automatically added to your headers. 

# Tests

To run tests on your locale machine, you can use `phpspec` and/or `phpunit` (for tests inside the `/tests/` folder).
- Initialize the test database:
```
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:update --force --env=test
php bin/console app:demo --env=test

```

- Run the tests:
```
php vendor/bin/phpspec --config phpspec.yml run
php vendor/bin/phpunit
```

# Doc Swagger
You will find a Swagger inteface at this place `/api/doc` you can add areas as `/api/doc/article`
Don't forget to apply the JWT token by clicking the `[Authorize]` button (top right of the page) &rarr; Value will be `"Bearer <JWT_TOKEN>"`

# For Docker users 
Go inside the folder [`.docker`] and run the command `docker-compose up --build`

Then to run the `app:demo` command, you have to run `docker exec <CONTAINER_ID> php /app/bin/console app:demo`
(considering that you didn't modify the `/app` workdir inside docker files)

You will find some initialization commands runned in `.docker/php/entrypoint.sh` to set database, schema, etc

For the Postman collection, replacing the environment variable `base_host` value from `zelty-article.local` to `localhost:8080` (in current configuration) will do the job.
