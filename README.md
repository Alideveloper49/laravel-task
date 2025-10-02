# Docker Deployment Guide

This guide explains how to launch the Laravel application using Docker.

## Prerequisites

- [Docker](https://www.docker.com/get-started) installed on your system
- [Docker Compose](https://docs.docker.com/compose/install/) installed

## Quick Start

### 1. Setup Environment

Copy the Docker environment configuration:

```bash
cp .env.docker .env
```

### 2. Build and Start Containers

Build the Docker images and start all containers:

```bash
docker-compose up -d
```

This will start:
- **app** - PHP 8.2 FPM application container
- **webserver** - Nginx web server
- **db** - MySQL 8.0 database
- **queue** - Laravel queue worker

### 3. Install Dependencies

Install PHP dependencies:

```bash
docker-compose exec app composer install
```

### 4. Run Migrations

Create database tables:

```bash
docker-compose exec app php artisan migrate
```

### 5. Access the Application

Open your browser and navigate to:

```
http://localhost:8000
```

## Common Commands

### Container Management

```bash
# Stop all containers
docker-compose down

# Restart containers
docker-compose restart

# View container logs
docker-compose logs -f

# View specific service logs
docker-compose logs -f app
docker-compose logs -f webserver
docker-compose logs -f db
docker-compose logs -f queue
```

### Laravel Artisan Commands

```bash
# Run any artisan command
docker-compose exec app php artisan [command]

# Examples:
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:list
```

### Database Access

```bash
# Access MySQL CLI
docker-compose exec db mysql -u root -p
# Password: root

# Create database backup
docker-compose exec db mysqldump -u root -proot task > backup.sql

# Restore database
docker-compose exec -T db mysql -u root -proot task < backup.sql
```

### Container Shell Access

```bash
# Access app container shell
docker-compose exec app bash

# Access database container shell
docker-compose exec db bash
```

### Running Tests

```bash
docker-compose exec app php artisan test

```
### Rebuild Containers

If you need to rebuild the containers from scratch:

```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

## Configuration

### Database Connection

The application is configured to connect to the MySQL container with these credentials:

- **Host:** db
- **Port:** 3306
- **Database:** task
- **Username:** root
- **Password:** root

### Ports

- **8000** - Web application (Nginx)
- **3306** - MySQL database



