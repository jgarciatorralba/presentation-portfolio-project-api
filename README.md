# Portfolio Project: API

API for a presentational project to showcase my portfolio.

---

## Content

This repository contains a **Symfony** application aimed to provide an **API** service that delivers information about various **GitHub** projects. The development environment is configured using **Docker** for easy setup and deployment.

### Key Features

- RESTful API: Efficiently fetch and manage GitHub project data.
- Symfony Framework: Leveraging the robustness and flexibility of Symfony.
- Dockerized Environment: Simplified setup and consistent development environment using Docker.
- Domain-Driven Design: Implementing DDD principles for a clear and maintainable codebase.
- Layered Architecture: Structured into Application, Domain, and Infrastructure layers for better separation of concerns and scalability.

---

## Installation

- Clone this repo: `git clone git@github.com:jgarciatorralba/presentation-portfolio-project-api.git`
- Navigate to the `/.docker` folder, then run `docker compose up -d` to download images and set up containers.
  - **Important**: the configuration is prepared to expose the server container's port on host's port 8000 and the database container's port on host's 6432, so make sure they are available before running the above command.
- Once completed, open with VisualStudio and in the command palette (*"View > Command Palette"*) select the option *"Dev Containers: Reopen in Container"*.
- Inside the development container, install packages with `composer install`.
- Even though an empty database named **app_db** should have been created with the installation, you can still run `sf doctrine:database:create` for good measure.
- With the database created and the connection to the app successfully established, execute the existing migrations in folder `/etc/migrations` using the command `sf doctrine:migrations:migrate`.

---

## Tests

- Run the test suites by executing the command: `php bin/phpunit`
  - Make sure to clear Symfony's test environment cache by running `sf cache:clear --env=test` before executing them.
  - **Important**: Create the **test** database by running the command `sf doctrine:database:create --env=test` and execute the corresponding migrations with `sf doctrine:migrations:migrate --env=test`.
- Run the code coverage report with the command: `XDEBUG_MODE=coverage php bin/phpunit [--coverage-text]`

---

## Scripts

- Run *PHPUnit* tests: `php bin/phpunit`
- Run *CodeSniffer* analysis: `php vendor/bin/phpcs [<filename|foldername>]`
  - Correct detected coding standard violations: `php vendor/bin/phpcbf [<filename|foldername>]`
- Run *PHPStan* analysis: `php vendor/bin/phpstan analyse [<foldernames>]`
- ~~Run *PHP Mess Detector* analysis: `php vendor/bin/phpmd <foldername> xml codesize --reportfile 'phpmd.results.xml'`~~ (PHPMD latest version 2.15.0 only supports up to PHP 8.3)
- Run *Rector* code refactoring: `php vendor/bin/rector process [--dry-run]`
- Delete existing database: `sf doctrine:database:drop --force`

---

## Author

- **Jorge García Torralba** &#8594; [jorge-garcia](https://github.com/jgarciatorralba)
