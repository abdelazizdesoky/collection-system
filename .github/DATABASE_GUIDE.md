# Database Guide

## Core Domain Tables

### `customers`
Customer records with opening balance and balance type tracking.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `name` | varchar | Customer name |
| `phone` | varchar | Contact phone |
| `address` | text | Customer address |
| `opening_balance` | decimal | Initial balance amount |
| `balance_type` | enum | 'debit' or 'credit' (indicates if customer owes money or credit exists) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Relationships:** Referenced by Collections, Cheques, CollectionPlanItems, CustomerAccount

---

### `collectors`
Collector profiles who handle payments and collection plans.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `name` | varchar | Collector name |
| `phone` | varchar | Contact phone |
| `area` | varchar | Service area/region |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Relationships:** Linked to User via `user.collector_id`, referenced by CollectionPlan and Collections

---

### `collections`
Payment transactions recorded by collectors (cash or cheque payments).

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `customer_id` | bigint | FK → customers |
| `collector_id` | bigint | FK → collectors |
| `amount` | decimal | Payment amount |
| `payment_type` | enum | 'cash' or 'cheque' |
| `collection_date` | date | Date of collection |
| `receipt_no` | varchar | **UNIQUE** - receipt number for tracking |
| `notes` | text | Optional notes |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Key Design:** Creates corresponding CustomerAccount ledger entry when saved
**Relationships:** Links customer and collector; triggers balance updates

---

### `cheques`
Cheque payment records with due date and status tracking.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `customer_id` | bigint | FK → customers |
| `cheque_no` | varchar | Cheque number |
| `bank_name` | varchar | Issuing bank |
| `amount` | decimal | Cheque amount |
| `due_date` | date | Expected clearance date |
| `status` | enum | Cheque status (pending, cleared, bounced, etc.) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Relationships:** Related to customer; separate from Collections table

---

### `collection_plans`
Planning records for collectors assigning specific dates and collection types.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `collector_id` | bigint | FK → collectors |
| `date` | date | Planned collection date |
| `type` | enum | Collection type (e.g., 'cash', 'cheque', 'follow-up') |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Cascade Delete:** CollectionPlanItems deleted when plan is deleted
**Relationships:** Assigned to collector; contains many CollectionPlanItems

---

### `collection_plan_items`
Individual customer items assigned to a collection plan with priority.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `collection_plan_id` | bigint | FK → collection_plans (cascade delete) |
| `customer_id` | bigint | FK → customers |
| `expected_amount` | decimal | Expected payment from this customer |
| `priority` | int | Priority ranking (lower = higher priority) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Key Design:** Tracks individual customer expectations within a plan
**Relationships:** Belongs to plan and customer

---

### `customer_accounts`
Ledger entries tracking debit/credit balance changes for each customer (polymorphic).

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `customer_id` | bigint | FK → customers (cascade delete) |
| `date` | date | Transaction date |
| `description` | varchar | Transaction description |
| `debit` | decimal | Debit amount (money owed) |
| `credit` | decimal | Credit amount (payment received) |
| `balance` | decimal | Running balance after transaction |
| `reference_type` | varchar | Polymorphic type (e.g., 'Collection', 'Cheque') |
| `reference_id` | bigint | Polymorphic ID pointing to referenced record |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Key Design:** Polymorphic ledger pattern - `reference_type` and `reference_id` link to various transaction types
**Example:** Collection payment creates entry with `reference_type='Collection'`, `reference_id=<collection_id>`
**Cascade Delete:** All ledger entries deleted when customer is deleted

---

## Authentication & Authorization Tables

### `users`
System users with authentication and collector linking.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `name` | varchar | User name |
| `email` | varchar | **UNIQUE** - login email |
| `email_verified_at` | timestamp | Null if not verified |
| `password` | varchar | Hashed password |
| `remember_token` | varchar | Remember-me token |
| `collector_id` | bigint | FK → collectors (nullable) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Key Design:** One user can be one collector; nullable if user is admin only
**Relationships:** Can belong to a collector; has roles/permissions

---

### `roles` (Spatie Permission)
Role definitions for authorization.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `name` | varchar | Role name (e.g., 'Admin', 'Collector') |
| `guard_name` | varchar | Guard context (typically 'web') |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Unique Constraint:** `(name, guard_name)` - role name is unique per guard

---

### `permissions` (Spatie Permission)
Permission definitions.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `name` | varchar | Permission name (e.g., 'create collections', 'view reports') |
| `guard_name` | varchar | Guard context (typically 'web') |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Unique Constraint:** `(name, guard_name)` - permission name is unique per guard

---

### `model_has_roles` (Spatie Permission)
Assignment of roles to models (users).

| Column | Type | Notes |
|--------|------|-------|
| `role_id` | bigint | FK → roles (cascade delete) |
| `model_type` | varchar | Model class (typically 'App\Models\User') |
| `model_id` | bigint | User ID |

**Primary Key:** `(role_id, model_id, model_type)` - ensures one role per user
**Index:** On `(model_id, model_type)` for fast lookups

---

### `model_has_permissions` (Spatie Permission)
Assignment of permissions directly to models (users).

| Column | Type | Notes |
|--------|------|-------|
| `permission_id` | bigint | FK → permissions (cascade delete) |
| `model_type` | varchar | Model class (typically 'App\Models\User') |
| `model_id` | bigint | User ID |

**Primary Key:** `(permission_id, model_id, model_type)` - ensures one permission per user
**Index:** On `(model_id, model_type)` for fast lookups

---

### `role_has_permissions` (Spatie Permission)
Assignment of permissions to roles.

| Column | Type | Notes |
|--------|------|-------|
| `permission_id` | bigint | FK → permissions (cascade delete) |
| `role_id` | bigint | FK → roles (cascade delete) |

**Primary Key:** `(permission_id, role_id)` - ensures one permission per role

---

## Framework Tables

### `migrations`
Tracks applied database migrations.

| Column | Type | Notes |
|--------|------|-------|
| `id` | int | Primary key |
| `migration` | varchar | Migration filename |
| `batch` | int | Batch number for tracking |

---

### `jobs` & `job_batches`
Background job queue storage.

**`jobs`**
| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `queue` | varchar | Queue name |
| `payload` | longtext | Serialized job data |
| `attempts` | tinyint | Retry count |
| `reserved_at` | int | Reserved timestamp |
| `available_at` | int | Available timestamp |
| `created_at` | int | Creation timestamp |

**`job_batches`**
| Column | Type | Notes |
|--------|------|-------|
| `id` | varchar | Unique batch ID |
| `name` | varchar | Batch name |
| `total_jobs` | int | Total jobs in batch |
| `pending_jobs` | int | Remaining jobs |
| `failed_jobs` | int | Failed count |
| `failed_job_ids` | longtext | JSON of failed IDs |
| `options` | mediumtext | Batch options |
| `cancelled_at` | int | Cancellation timestamp |
| `created_at` | int | Creation timestamp |
| `finished_at` | int | Completion timestamp |

---

### `cache` & `cache_locks`
Cache storage and locking mechanism.

**`cache`**
| Column | Type | Notes |
|--------|------|-------|
| `key` | varchar | **PRIMARY KEY** - cache key |
| `value` | mediumtext | Cached value |
| `expiration` | int | Unix timestamp for expiry |

**`cache_locks`**
| Column | Type | Notes |
|--------|------|-------|
| `key` | varchar | **PRIMARY KEY** - lock key |
| `owner` | varchar | Lock owner ID |
| `expiration` | int | Unix timestamp for expiry |

---

### `sessions`
User session storage.

| Column | Type | Notes |
|--------|------|-------|
| `id` | varchar | **PRIMARY KEY** - session ID |
| `user_id` | bigint | Logged-in user (nullable) |
| `ip_address` | varchar | Client IP |
| `user_agent` | text | Browser/client info |
| `payload` | longtext | Session data |
| `last_activity` | int | Last activity timestamp |

**Indexes:** On `user_id` and `last_activity` for cleanup

---

### `password_reset_tokens`
Password reset token storage.

| Column | Type | Notes |
|--------|------|-------|
| `email` | varchar | **PRIMARY KEY** - user email |
| `token` | varchar | Reset token |
| `created_at` | timestamp | Token creation time |

---

### `failed_jobs`
Failed background job records.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `uuid` | varchar | **UNIQUE** - job UUID |
| `connection` | text | Queue connection name |
| `queue` | text | Queue name |
| `payload` | longtext | Job data |
| `exception` | longtext | Error message |
| `failed_at` | timestamp | Failure time |

---

## Data Flow Examples

### Example 1: Recording a Cash Payment
1. Collector records a Collection (payment_type='cash')
2. System creates CustomerAccount ledger entry:
   - `reference_type='Collection'`
   - `reference_id=<collection_id>`
   - `credit=<amount>` (payment received)
   - `balance` updated
3. Customer balance reduced by payment amount

### Example 2: Cheque Payment
1. Collector receives cheque from customer
2. Cheque record created with `status='pending'`
3. When cheque clears, Cheque `status` updated to 'cleared'
4. Separate CustomerAccount entry created with `reference_type='Cheque'`

### Example 3: Collection Plan Workflow
1. Admin creates CollectionPlan for a Collector on specific date
2. Multiple CollectionPlanItems added, each with a customer and expected_amount
3. Collector views assigned items, prioritized by `priority` field
4. As collector records Collections, system updates balances via CustomerAccount
5. Plan deleted → cascade deletes all related CollectionPlanItems

---

## Key Design Patterns

### Polymorphic Ledger Pattern
The `customer_accounts` table uses polymorphic references to track balance changes from multiple sources:
- `reference_type` = Class name (e.g., 'Collection', 'Cheque')
- `reference_id` = ID of referenced record
- Allows flexible balance tracking without multiple ledger tables

### Cascade Deletes
- Delete Customer → deletes all CustomerAccount entries
- Delete CollectionPlan → deletes all CollectionPlanItems
- Delete Role/Permission → removes all model associations

### Unique Constraints
- `collections.receipt_no` - ensures unique receipt tracking
- `users.email` - ensures unique login
- `roles.name + guard_name` - ensures role names don't conflict
- `permissions.name + guard_name` - ensures permission names don't conflict

### Foreign Key Constraints
All domain relationships use foreign keys to maintain referential integrity. No cascade delete on Collections/Cheques to preserve historical records.
