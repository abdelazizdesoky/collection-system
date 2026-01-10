# Copilot Instructions for Collection System

## Project Overview
This is a Laravel 12 **debt/collection management system** for tracking customers, collectors, payment collections, and cheques. The system manages customer accounts with debit/credit transactions, collection plans, and payment workflows using roles and permissions.

**Key Stack:** PHP 8.4, Laravel 12, MySQL, Spatie Laravel Permission

## Architecture & Domain Knowledge

### Core Domain Model
- **Customers** → Customer records with opening balance (debit/credit tracking)
- **Collectors** → Users who collect payments, linked to user accounts
- **Collections** → Payment transactions (cash/cheque), with receipt numbers
- **Cheques** → Cheque payment records with due dates and status tracking
- **Collection Plans** → Planning dates/type assigned to collectors with prioritized customer items
- **Customer Accounts** → Ledger entries (debit/credit) with balance tracking, polymorphic references

**Key Design Pattern:** Collection → Customer ledger entry (reference_type/reference_id polymorphism)

### Critical Relationships
```
User → Collector (one collector per user, via collector_id)
Collector ← CollectionPlan, Collections
Customer ← Collections, Cheques, CollectionPlanItems, CustomerAccount
CollectionPlan → CollectionPlanItems (cascade delete on plan)
CustomerAccount → Customers (cascade delete on customer)
```

### Permissions Architecture
Uses **Spatie Laravel Permission** (v6.24) with roles/permissions tables. Models use `HasRoles` trait. Middleware `['role:Admin']` protects routes. See `config/permission.php` for configuration.

## Key Developer Workflows

### Setup & Running
- **Development:** `composer run dev` - runs server, queue listener, logs, and Vite concurrently
- **Tests:** `php artisan test --compact` (all), `php artisan test --compact tests/Feature/ExampleTest.php` (single file), `php artisan test --compact --filter=testName` (specific test)
- **Migrations:** `php artisan migrate` (run migrations, test fixtures auto-use factories)

### Code Generation
Use `php artisan make:*` commands (list with `php artisan list`):
- Models: `php artisan make:model --no-interaction -m -f` (creates factory & migration)
- Controllers: `php artisan make:controller --no-interaction --resource --model=ModelName`
- Migrations: `php artisan make:migration --no-interaction --create=table_name`

### Database & ORM
- **Query Patterns:** Use Eloquent, eager-load relationships (`with()`) to prevent N+1
- **Ledger Logic:** CustomerAccount uses `reference_type`/`reference_id` polymorphism (inspect migrations `2026_01_10_134847_create_customer_accounts_table.php`)
- **Balance Tracking:** Decimal fields used for amounts; always use `decimal` type in migrations
- **Transactions:** Collections link to customers AND collectors; collections create customer ledger entries

## Code Conventions & Patterns

### PHP & Laravel
- **Return Types:** Always declare explicit return types; use type hints on parameters
- **Constructor Promotion:** Use PHP 8 `public function __construct(public Model $model) {}`
- **Type Hints:** Array shapes with PHPDoc when appropriate
- **Comments:** Prefer PHPDoc blocks; avoid inline comments unless complex logic
- **Control Structures:** Always use braces, even single-line blocks

### Validation & Forms
- Create **Form Request classes** in `app/Http/Requests/` for validation (NOT inline in controllers)
- Check sibling requests for array vs string-based rule convention
- Include custom error messages in Form Requests

### Testing
- Tests in `tests/Feature/` (default) or `tests/Unit/` (with `--unit` flag)
- Create with `php artisan make:test --no-interaction TestName`
- **Use factories** for model creation: `User::factory()->create()`, check factory states
- **No verification scripts:** Use PHPUnit tests instead of tinker for functionality proof
- Test happy paths, failure paths, and edge cases
- Run minimal tests after changes: `php artisan test --compact --filter=changedFeature`

### Existing Code Patterns
- Check **sibling files** (models, controllers, requests) for naming/structure conventions before creating new ones
- Reuse existing components before writing new ones
- Database schema in `database/migrations/` — respect foreign key cascades/constraints

## Integration Points & External Packages

### Spatie Permission (v6.24)
- Models use `HasRoles`, `HasPermissions` traits
- Guard name: 'web' (from config)
- Permissions checked via middleware: `middleware(['role:RoleName'])`
- Roles/permissions created in seeders; see `RolesAndPermissionsSeeder.php`

### Frontend & Assets
- **Vite:** `npm run dev` (development watch), `npm run build` (production)
- If UI changes not visible, run `npm run build` or `npm run dev`
- CSS in `resources/css/`, JS in `resources/js/`

### Debugging Tools
- **Tinker:** Use for PHP execution/model queries (not for verification)
- **Database Queries:** Use `database-query` tool for read-only SQL
- **Browser Logs:** Check `browser-logs` tool for frontend errors
- **Logs:** `storage/logs/` for application logs

## Critical Files to Reference
- **Domain:** `app/Models/` (Customer, Collector, Collection, Cheque, CollectionPlan, CustomerAccount, User)
- **Database:** `database/migrations/2026_01_10_*.php` (see all entity migrations)
- **Routes:** `routes/web.php` (protected by role middleware)
- **Permissions:** `config/permission.php`, `database/seeders/RolesAndPermissionsSeeder.php`
- **Bootstrap:** `bootstrap/app.php` (middleware, routing, exception config)
- **Testing:** `tests/TestCase.php` (test base class, check for helper methods)

## Important Notes
- **Laravel 12 Structure:** New streamlined structure; middleware in `bootstrap/app.php`, not `app/Http/Kernel.php`
- **No empty constructors:** Don't create `__construct()` with zero parameters unless private
- **Format code:** Run `vendor/bin/pint --dirty` before finalizing changes
- **Preserve migrations:** Never modify existing migrations; create new ones to alter columns
- **Documentation:** Only create docs if explicitly requested by user
