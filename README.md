# Giphy Integration

## Overview
Giphy Integration is a REST API that allows users to search for GIFs using the Giphy API, save their favorite GIFs, and manage user authentication via OAuth2.

## Table of Contents
1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Running the Application](#running-the-application)
4. [API Endpoints](#api-endpoints)
5. [Testing](#testing)
6. [Diagrams](#diagrams)

## Requirements
- Docker
- Docker Compose
- Git

## Installation
To build and run the application, follow these steps:

1. Clone the repository:
    ```sh
    git clone git@github.com:dleloutre/giphy-integration.git
    cd giphy-integration
    ```

2. Copy the environment configuration file:
    ```sh
    cp ./src/.env.example ./src/.env
    ```

3. Build and run the Docker containers:
    ```sh
    docker-compose up -d --build
    ```

4. Install the dependencies:
    ```sh
    docker exec -it app composer install
    ```

5. Set the appropriate permissions for the storage directory:
    ```sh
    docker exec -it app chmod -R 777 storage
    ```

6. Generate the application key:
    ```sh
    docker exec -it app php artisan key:generate
    ```

7. Run the database migrations:
    ```sh
    docker exec -it app php artisan migrate
    ```

8. Install Passport:
    ```sh
    docker exec -it app php artisan passport:install
    ```

9. Seed the database:
    ```sh
    docker exec -it app php artisan db:seed
    ```

## Running the Application
Once the installation is complete, the application should be running and accessible via the configured URL. Use the provided endpoints to interact with the API.

## API Endpoints
The following endpoints are available in the Giphy Integration API:

### 1. Login
- **Endpoint:** `/api/login`
- **Method:** POST
- **Description:** Authenticates the user and provides an OAuth2 token.

### 2. Search GIF by Keyword
- **Endpoint:** `/api/gifs/search`
- **Method:** GET
- **Description:** Searches for GIFs based on the provided keyword.
- **Authorization:** Required

### 3. Search GIF by ID
- **Endpoint:** `/api/gifs/{gifId}`
- **Method:** GET
- **Description:** Retrieves a GIF by its ID.
- **Authorization:** Required

### 4. Save Favorite GIF
- **Endpoint:** `/api/users/{userId}/favorite-gifs`
- **Method:** POST
- **Description:** Saves a GIF to the user's favorites.
- **Authorization:** Required

For detailed API documentation, refer to the Postman Collection.

## Testing
To run the tests, use the following command:
```sh
docker exec -it app php artisan test
```

## Diagrams
The following diagrams are included to illustrate the application flow and data relationships:

### 1. Sequence Diagram
![Sequence Diagram](path/to/sequence-diagram.png)

### 2. Entity-Relationship Diagram (ERD)
![ERD Diagram](path/to/erd-diagram.png)

### 3. Use Case Diagram
![Use Case Diagram](path/to/use-case-diagram.png)
