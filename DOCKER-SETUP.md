# Docker Development Setup for PHPStorm

This guide explains how to configure PHPStorm to use Docker for PHP development, avoiding the need for a local PHP installation.

## Prerequisites

- Docker and Docker Compose installed on your system
- JetBrains PHPStorm (2023.x or later recommended)
- Docker plugin enabled in PHPStorm (usually enabled by default)

## Initial Setup

### 1. Build and Start the Docker Container

```bash
# Build the Docker image
docker compose build

# Start the container
docker compose up -d

# Verify the container is running
docker compose ps
```

### 2. Install Dependencies

```bash
# Install Composer dependencies inside the container
docker compose exec app composer install
```

## PHPStorm Configuration

### Step 1: Configure Docker Integration

1. Open **Settings/Preferences** (Ctrl+Alt+S / Cmd+,)
2. Navigate to **Build, Execution, Deployment > Docker**
3. Click the **+** button to add a Docker configuration
4. Select **Docker for Linux** (or your platform)
5. Keep the default settings (Docker socket: `unix:///var/run/docker.sock`)
6. Click **OK** and verify "Connection successful" appears

### Step 2: Configure PHP CLI Interpreter from Docker

1. Open **Settings/Preferences**
2. Navigate to **PHP**
3. Click the **...** button next to **CLI Interpreter**
4. In the CLI Interpreters dialog, click the **+** button
5. Select **From Docker, Vagrant, VM, WSL...**
6. Choose **Docker Compose**
7. Configure:
   - **Server**: Select your Docker connection (created in Step 1)
   - **Configuration files**: `./docker compose.yml`
   - **Service**: Select `app`
   - **Lifecycle**: Select **Connect to existing container ('docker compose exec')**
8. Click **OK**
9. PHPStorm will auto-detect the PHP version and Xdebug
10. Verify that **Debugger** shows "Xdebug 3.x.x"
11. Click **OK** to close the dialog

### Step 3: Configure Composer

1. In **Settings/Preferences > PHP > Composer**
2. Select **Remote Interpreter**
3. Choose the Docker interpreter you just configured
4. Click **OK**

Now PHPStorm will use Composer from the Docker container.

### Step 4: Configure PHPUnit

1. In **Settings/Preferences > PHP > Test Frameworks**
2. Click the **+** button and select **PHPUnit by Remote Interpreter**
3. Select your Docker interpreter
4. Configure PHPUnit:
   - **PHPUnit library**: Select **Path to phpunit.phar** or **Use Composer autoloader**
   - If using Composer autoloader, set path to: `/app/vendor/autoload.php`
   - **Default configuration file**: `/app/phpunit.xml.dist`
5. Click **OK**

### Step 5: Configure Xdebug

1. In **Settings/Preferences > PHP > Debug**
2. Configure Xdebug:
   - **Debug port**: `9003`
   - Check **Can accept external connections**
   - Uncheck **Force break at first line when no path mapping specified** (optional)
   - Uncheck **Force break at first line when a script is outside the project** (optional)
3. Click **OK**

4. Configure Path Mappings:
   - Go to **Settings/Preferences > PHP > Servers**
   - If no server exists, create one:
     - **Name**: `hexagonal-php` (must match PHP_IDE_CONFIG in docker compose.yml)
     - **Host**: `localhost`
     - **Port**: `80` (or any, doesn't matter for CLI)
     - Check **Use path mappings**
     - Map your project root to `/app` in the container:
       - **File/Directory**: `<your-project-path>`
       - **Absolute path on the server**: `/app`
   - Click **OK**

### Step 6: Verify PHP Interpreter

1. Open a PHP file in the project
2. Check the bottom-right corner of PHPStorm - you should see the PHP version with a Docker icon
3. Click on it to verify the interpreter is correctly configured

## Running Commands

### Running Composer Commands

You can now run Composer commands in PHPStorm's terminal or through the Composer UI:

```bash
# In PHPStorm terminal (will use the host Docker)
docker compose exec app composer install
docker compose exec app composer require ramsey/uuid

# Or use PHPStorm's Composer integration (Tools > Composer)
```

### Running PHPUnit Tests

#### Option 1: Using Run Configurations

1. Right-click on a test file or directory
2. Select **Run '<test-name>'**
3. PHPStorm will execute the test in the Docker container

#### Option 2: Using Command Line

```bash
docker compose exec app bin/phpunit
docker compose exec app bin/phpunit --testsuite=unit
docker compose exec app bin/phpunit --filter testAddServizio
```

### Running PHPStan

```bash
docker compose exec app composer run phpstan
```

### Running CLI Commands

```bash
docker compose exec app bin/console list
docker compose exec app bin/console app:helloworld "John Doe"
```

## Debugging with Xdebug

### Debugging CLI Scripts

1. Set breakpoints in your PHP code
2. Click the **Start Listening for PHP Debug Connections** button (phone icon) in the PHPStorm toolbar
3. Run your script with debug:

```bash
docker compose exec app php bin/console app:welcome "Test User"
```

4. PHPStorm should stop at your breakpoints

### Debugging Tests

1. Set breakpoints in your test code
2. Right-click on the test
3. Select **Debug '<test-name>'**
4. PHPStorm will stop at breakpoints during test execution

## Common Commands

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# View logs
docker compose logs -f app

# Execute commands in container
docker compose exec app <command>

# Rebuild container after Dockerfile changes
docker compose build --no-cache
docker compose up -d --force-recreate

# Access container shell
docker compose exec app bash
```

## Troubleshooting

### PHP Interpreter Not Found

- Ensure the Docker container is running: `docker compose ps`
- Restart the container: `docker compose restart app`
- Rebuild the container: `docker compose build`

### Xdebug Not Connecting

- Verify port 9003 is not in use by another application
- Check that **Start Listening for PHP Debug Connections** is enabled (phone icon)
- Verify path mappings in **Settings > PHP > Servers**
- Check Xdebug logs: `docker compose exec app cat /tmp/xdebug.log`

### Permission Issues with Vendor Directory

The Dockerfile creates a user with UID 1000 to match most Linux users. If you have permission issues:

```bash
# Fix permissions
docker compose exec app composer install
```

Or modify the Dockerfile to use your UID:

```dockerfile
RUN useradd -m -u $(id -u) developer
```

### Composer Dependencies Not Installing

- Ensure you're connected to the internet
- Clear Composer cache: `docker compose exec app composer clear-cache`
- Try: `docker compose exec app composer install --no-cache`

## Performance Optimization

### macOS Users

Docker on macOS can be slow with volumes. Consider using:

1. **Cached volumes** (already configured in docker compose.yml):
   ```yaml
   volumes:
     - .:/app:cached
   ```

2. **Mutagen** or **docker-sync** for better performance

### Composer Performance

The `docker compose.yml` includes a named volume for Composer cache to speed up dependency installation.

## Maintenance

### Updating PHP Version

The Dockerfile uses `php:fpm` which automatically uses the latest stable PHP version. To pin to a specific version, edit the `Dockerfile` and change:
```dockerfile
FROM php:fpm
# To pin to a specific version:
# FROM php:8.3-fpm
```

Then rebuild:
```bash
docker compose build --no-cache
docker compose up -d --force-recreate
```

### Adding PHP Extensions

Edit the `Dockerfile` and add to the `docker-php-ext-install` command:
```dockerfile
RUN docker-php-ext-install \
    mbstring \
    zip \
    pdo_mysql  # example
```

Then rebuild the container.
