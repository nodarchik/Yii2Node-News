# Yii2 Microservice Project

This project is built using the Yii2 framework and follows a microservice architecture. It is designed to provide a solid foundation for building scalable, maintainable, and testable APIs.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Directory Structure](#directory-structure)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

## Installation
1. Install dependencies using Composer:
    ```bash
    composer install
    ```

2. Set up the environment configuration by copying `.env.example` to `.env` and modifying the values as needed.

## Configuration

Configuration files are located in the `config/` directory. Update the `params.php` and `web.php` files as needed for your environment and application requirements.

## Directory Structure

- `controllers/` - Contains controller classes.
- `models/` - Contains model classes and form models.
- `views/` - Contains view files.
- `services/` - Contains service classes.
- `clients/` - Contains API client classes.
- `tests/` - Contains test classes.


### User Endpoints

- `GET /users` - List all users.
- `GET /users/<id>` - Retrieve a user by ID.
- `POST /users` - Create a new user.
- `PUT /users/<id>` - Update a user by ID.
- `DELETE /users/<id>` - Delete a user by ID.

### News Endpoints

- `GET /news` - List all news items.
- `GET /news/<id>` - Retrieve a news item by ID.
- `POST /news` - Create a new news item.
- `PUT /news/<id>` - Update a news item by ID.
- `DELETE /news/<id>` - Delete a news item by ID.

## Testing

To run the tests, execute the following command:

```bash
vendor/bin/codecept run
