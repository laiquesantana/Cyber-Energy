# Energy API with Docker

This project is an API developed for managing chat history with **OpenAI**, using **PostgreSQL** as the database, **Redis** for caching, and **Nginx** as the web server. The application is containerized with Docker and Docker Compose for ease of deployment and execution.

---

## Requirements

Before running the project, make sure you have installed:

- **Docker** (version 24.04 or higher)
- **Docker Compose** (version 1.29.0 or higher)
- **Make** (to run commands via `Makefile`)

---
## Swagger
- swagger documentation can be found at swagger.yaml file
## Installation

### 1. Clone the Repository

## Makefile Commands
The project includes a Makefile with convenient commands to streamline development. Below are the available commands:
```bash
Command	Description
make up	Starts all containers in detached mode
make stop	Stops all running containers
make php	Opens a bash shell inside the app container
make start-php	Starts only the app container if it is not running
make start-cache	Starts only the cache container if it is not running
make composer	Installs PHP dependencies, ignoring platform requirements
make test	Runs PHPUnit tests inside the app container
make cache	Opens the Redis CLI within the cache container
```

## 2. Configure the .env File
   Create a .env file in the project root by copying from .env.example, and fill in the required environment variables, such as database credentials and API keys:
```bash
cp .env.dist .env
```
update OPENAI_API_KEY with your openai api key


## Running the Project 3.1 Build the Docker Image
To build the Docker image, use:

```bash
make build
```
Or, if you prefer to use Docker Compose directly:
```bash
docker compose build
```

## docker compose build
3.2 Start the Containers
Bring up all services defined in the docker-compose.yml:

- make up
Or:

- docker compose up -d
- run make compooser or docker-compose exec app composer install
## 3.3 Run Database Migrations
After starting the containers, enter the app container and run the necessary migrations:

- make migrate
- or run make php and inside container run php artisan migrate

## 3.3 Npm 
After starting the containers, enter the app container and run the necessary npm install:

- make php
- npm install
- npm run dev

```bash

├── adapters/
│   ├── ConfigAdapter.php          # Adapter for environment configuration
│   └── Http/
│       └── ClientAdapter.php      # Adapter for making HTTP requests
├── core/
│   └── Modules/
│       └── Chat/
│           ├── Creation/          # Chat history creation module
│           ├── Find/              # Chat history retrieval module
│           ├── Update/            # Chat history update module
│           └── Delete/            # Chat history deletion module
│       └── Generics/              # Generic components and helpers
├── controllers/
│   └── ChatController.php         # Controller for chat history API endpoints
├── models/
│   └── ChatHistory.php            # Model for chat history
```

API Endpoints
Authentication
Parameters:

username (string) - Username
password (string) - Password
```bash
curl -X POST http://localhost:9000/login \
-H "Content-Type: application/json" \
-d '{"username": "exampleuser", "password": "examplepassword"}'
```
```bash
{
"status": 200,
"message": "Login successful",
"data": {
"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
"expires_in": 3600
},
"error": "",
"meta": []
}
```


Chat History Creation
Parameters:

user_input (string) - User's input text
Example Request:
```bash

curl -X POST http://localhost:9000/chat/create \
-H "Authorization: Bearer <token>" \
-H "Content-Type: application/json" \
-d '{"user_input": "Tell me about renewable energy."}'
Example Response:
```
```bash
{
"status": 201,
"message": "Chat history created successfully",
"data": {
"id": 1,
"user_input": "Tell me about renewable energy.",
"ai_response": "Renewable energy comes from natural sources...",
"created_at": "2024-11-05T12:34:56Z"
},
"error": "",
"meta": []
}
```

```bash
Chat History Retrieval (Single Record)
Example Request:


curl -X GET http://localhost:9000/chat/1 \
-H "Authorization: Bearer <token>"
Example Response:
```
```bash
{
"status": 200,
"message": "Chat history retrieved successfully",
"data": {
"id": 1,
"user_input": "Tell me about renewable energy.",
"ai_response": "Renewable energy comes from natural sources...",
"created_at": "2024-11-05T12:34:56Z"
},
"error": "",
"meta": []
}
```

```bash
Chat History Deletion
Example Request:


curl -X DELETE http://localhost:9000/chat/1 \
-H "Authorization: Bearer <token>"
Example Response:


{
"status": 200,
"message": "Chat history deleted successfully",
"data": null,
"error": "",
"meta": []
}
```
```bash
Retrieve All Chat Histories
Parameters:

limit (int) - Number of records per page
offset (int) - Number of records to skip for pagination
Example Request:


curl -X GET "http://localhost:9000/chat?limit=10&offset=0" \
-H "Authorization: Bearer <token>"
Example Response:

```bash
{
"status": 200,
"message": "Chat histories retrieved successfully",
"data": [
{
"id": 1,
"user_input": "Tell me about renewable energy.",
"ai_response": "Renewable energy comes from natural sources...",
"created_at": "2024-11-05T12:34:56Z"
}
],
"error": "",
"meta": {
"total": 1
}
}
```

Testing
To run the PHPUnit tests, use:

```bash

make test
```
