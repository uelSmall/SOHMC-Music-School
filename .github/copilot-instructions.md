<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3.4
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/socialite (SOCIALITE) - v5
- livewire/livewire (LIVEWIRE) - v3
- laravel/breeze (BREEZE) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11
- prettier (PRETTIER) - v3
- tailwindcss (TAILWINDCSS) - v3


## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.


=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.


=== herd rules ===

## Laravel Herd

- The application is served by Laravel Herd and will be available at: https?://[kebab-case-project-dir].test. Use the `get-absolute-url` tool to generate URLs for the user to ensure valid URLs.
- You must not run any commands to make the site available via HTTP(s). It is _always_ available through Laravel Herd.


=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
## Copilot / AI Agent Quick Guide — Laravel Starter (concise)

This file gives the must-know facts for an AI coding agent to be productive in this repository.

- **Big picture**: Laravel 12 app with modular features under the `Modules/` folder, Livewire v3 UI components in `app/Livewire`, traditional MVC resources in `app/Models`, `resources/views` and routes in `routes/`.
- **Key files**: see [bootstrap/app.php](bootstrap/app.php), [bootstrap/providers.php](bootstrap/providers.php), [Modules](Modules), [app/Livewire](app/Livewire), [app/Models](app/Models), [resources/views](resources/views), [routes/web.php](routes/web.php), [composer.json](composer.json), [boost.json](boost.json).

- **Why structure is this way**: the app uses a modular approach (Modules/*) to separate domain areas (Category, Post, Tag, Menu). Livewire components keep UI state server-side and are central to interactive pages.

- **Developer workflows / commands**:
  - Install/deps: `composer install` and `npm install`
  - Build assets (dev): `npm run dev` or `composer run dev`
  - Build for production: `npm run build`
  - Run tests (targeted): `php artisan test --filter=Name` or run a file: `php artisan test tests/Feature/SomeTest.php`
  - Format: `vendor/bin/pint --dirty` before PRs

- **Project-specific conventions**:
  - Use Form Request classes for validation (see examples in `app/Http/Controllers` siblings).
  - Prefer Eloquent via model query builders (`Model::query()`) and eager load relations to avoid N+1.
  - Model casts prefer a `casts()` method pattern (follow existing model examples in `app/Models`).
  - Livewire v3: components live in `app/Livewire`; prefer `wire:model.live` for real-time bindings and single-root elements.
  - When adding features, check for an existing `Modules/<Domain>` before creating new top-level folders.

- **Testing notes**:
  - Tests use PHPUnit v11 and are located in `tests/Feature` and `tests/Unit`.
  - Use factories for model creation in tests; inspect `database/factories` for patterns and states.

- **Common pitfalls & fixes**:
  - Vite manifest errors: run `npm run build` or `npm run dev` (same repo pattern used previously).
  - Asset/UX issues often indicate the frontend build wasn't run — confirm with the user and run dev/build.

- **Integration points / external deps**:
  - Uses Livewire (server-driven UI), many Spatie and other packages in `composer.json` — consult `vendor/` for implementations.
  - Boost/Boost-generated guides: `boost.json` and files like `AGENTS.md` may be regenerated by `boost:install`.

- **How to ask the user for context** (when changes are non-trivial): request the intended module, whether to run `npm`/`composer` commands, whether to run specific tests, and if a migration/seeder is expected.

If any part of this is unclear or you want more examples (eg. sample Livewire component + test, or module scaffold steps), tell me which area and I will expand the relevant section.
