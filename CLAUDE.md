# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository Overview

This is a PHP educational project demonstrating **Hexagonal Architecture (Ports and Adapters)**, **Domain-Driven Design (DDD)**, and related design principles. It's a CLI application built with Symfony Console components, focusing on a clean separation of concerns across architectural layers.

## Docker Development

This project supports Docker-based development for PHPStorm users who prefer not to install PHP locally. See `DOCKER-SETUP.md` for complete PHPStorm configuration instructions.

### Quick Docker Setup

```bash
# Build and start the container
docker compose up -d

# Install dependencies
docker compose exec app composer install

# Run commands through Docker
docker compose exec app bin/phpunit
docker compose exec app bin/console list
```

## Key Commands

### Development Commands

**Using local PHP:**
```bash
# Install dependencies
composer install

# Run all tests
composer run test
# Alternative: bin/phpunit

# Run specific test suites
bin/phpunit --testsuite=unit
bin/phpunit --testsuite=functional

# Run single test file
bin/phpunit tests/Unit/Domain/CatalogoServiziTest.php

# Run single test method
bin/phpunit --filter testAddServizio tests/Unit/Domain/CatalogoServiziTest.php

# Static analysis with PHPStan
composer run phpstan
# Alternative: phpstan analyse --memory-limit=2048M

# List available CLI commands
php bin/console list

# Example CLI commands
composer run app:helloworld
composer run app:welcome
php bin/console app:catalogoServizi
php bin/console app:subscriptions
```

**Using Docker:**
```bash
# Install dependencies
docker compose exec app composer install

# Run all tests
docker compose exec app composer run test
docker compose exec app bin/phpunit

# Run specific test suites
docker compose exec app bin/phpunit --testsuite=unit
docker compose exec app bin/phpunit --testsuite=functional

# Run single test file
docker compose exec app bin/phpunit tests/Unit/Domain/CatalogoServiziTest.php

# Run single test method
docker compose exec app bin/phpunit --filter testAddServizio

# Static analysis with PHPStan
docker compose exec app composer run phpstan

# List available CLI commands
docker compose exec app bin/console list

# Example CLI commands
docker compose exec app bin/console app:helloworld "John Doe"
docker compose exec app bin/console app:catalogoServizi
```

## Architecture Overview

### Hexagonal Architecture Layers

The codebase strictly follows hexagonal architecture with clear separation:

1. **Domain** (`src/Domain/`) - Pure business logic, no external dependencies
   - Aggregate Roots (e.g., `CatalogoServizi`, `Users`)
   - Entities (e.g., `Servizio`)
   - Value Objects (e.g., `TitoloServizio`, `Username`, `ServiceStatus`)
   - Domain IDs (e.g., `IdServizio`, `UsersId`)
   - Result objects for domain operations (e.g., `ServizioAdded`, `UserNotFound`)

2. **Application** (`src/Application/`) - Application services, orchestrates domain operations
   - Application Services (e.g., `CatalogoServiziService`, `WelcomeService`)
   - Use case coordinators
   - No business logic, only orchestration

3. **Infrastructure** (`src/Infrastructure/`) - External dependencies and implementations
   - Repository implementations (e.g., `JsonServizioRepository`, `FileUsersRepository`)
   - JSON-based persistence
   - External service adapters

4. **UserInterface** (`src/UserInterface/Cli/Command/`) - CLI commands
   - Symfony Console Command implementations
   - Input/output handling
   - Commands auto-register via dependency injection

5. **Shared** (`src/Shared/`) - Cross-cutting concerns
   - Base classes for Domain concepts (`ValueObject`, `AggregateRoot`, `DomainId`)
   - Interfaces (`RepositoryInterface`, `AggregateInterface`, `UuidGenerator`)
   - Shared infrastructure (e.g., `RamseyUuidGenerator`)

### Key Architectural Patterns

**Aggregate Pattern**: Aggregate roots (like `CatalogoServizi`) are the entry point for all modifications to their aggregates. They enforce domain invariants and return `ResultInterface` implementations to communicate operation outcomes.

**Value Objects**: Immutable objects that encapsulate domain concepts with validation (e.g., `TitoloServizio` enforces kebab-case format and max 25 chars).

**Repository Pattern**: One repository per aggregate root. Repositories handle persistence via `getById()` and `save()` methods. Currently implements both read and write models together (a noted simplification).

**Result Pattern**: Domain operations return result objects (like `ServizioAdded`, `ServizioAlreadyPresent`) instead of throwing exceptions, making outcomes explicit and type-safe.

**Dependency Injection**: Full autowiring via Symfony DI container. Commands automatically register via `instanceof(Command::class)->tag('console.command')` in `config/services.php`.

### Domain Model

**CatalogoServizi Aggregate**:
- Root: `CatalogoServizi` - manages a catalog of services
- Entity: `Servizio` - a service with ID, title, description, and status
- Value Objects: `TitoloServizio` (kebab-case, max 25 chars), `DescrizioneServizio` (max 300 chars), `ServiceStatus`
- Results: `ServizioAdded`, `ServizioAlreadyPresent`

**Users Aggregate**:
- Root: `Users` - manages user collections
- Value Objects: `Username`, `SubscriptionDate`
- Results: `UserAdded`, `UserAlreadyPresent`, `UserNotFound`, `UserRemoved`, `UserList`

### Dependency Injection Setup

The DI container is configured in `config/bootstrap.php` and `config/services.php`:
- All classes in `src/` are auto-wired and auto-configured
- Commands extending `Symfony\Component\Console\Command\Command` auto-register
- Container is compiled and cached for performance

## Code Conventions

### File Structure
- Each layer has its own namespace: `App\Domain\`, `App\Application\`, `App\Infrastructure\`, `App\UserInterface\`
- Test structure mirrors source structure: `tests/Unit/`, `tests/Functional/`
- Aggregates organized by subdomain: `Domain/Services/`, `Domain/Users/`

### Testing Structure
- PHPUnit 9.5 with two test suites: `unit` and `functional`
- Test bootstrap in `tests/bootstrap.php`
- Tests follow DDD structure, organized by layer and domain concept
- Example: `tests/Unit/Domain/CatalogoServiziTest.php` tests the aggregate root

### Creating New Commands
1. Create a class in `src/UserInterface/Cli/Command/`
2. Extend `Symfony\Component\Console\Command\Command`
3. Command auto-registers via DI (no manual configuration needed)
4. Run `php bin/console list` to verify

### Repository Implementation Notes
- Current repositories use JSON file persistence
- Files named by aggregate ID: `{aggregateId}.json`
- Repository reads/writes entire aggregates, not individual entities
- Repositories currently combine read and write models (noted technical debt)

## Environment Requirements

- **PHP** >= 8.1.0
- **ext-mbstring** extension required
- **Composer** for dependency management
- Dependencies: Symfony Console/DI/Config 6.0, Ramsey UUID 4.x
- Dev dependencies: PHPUnit 9.5, PHPStan 1.11

## Project Resources

- DeepWiki: https://deepwiki.com/jesuswasrasta/hexagonal-architecture-php/
- Miro board (private): https://miro.com/app/board/uXjVK5myd74=/

## Notes on DDD Implementation

This project uses Italian domain language in some places (e.g., `CatalogoServizi`, `Servizio`) as the domain is in Italian. This is intentional per DDD's "Ubiquitous Language" principle.

The implementation demonstrates:
- Aggregate boundaries and invariant enforcement
- Value object validation at construction
- Explicit result types instead of exception-based control flow
- Repository per aggregate pattern
- Dependency inversion (domain defines `RepositoryInterface`, infrastructure implements it)
