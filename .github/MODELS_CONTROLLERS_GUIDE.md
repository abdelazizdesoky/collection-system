# Models & Controllers Guide

## Models Overview

All models implement Eloquent relationships for efficient data access and automatic eager loading to prevent N+1 query problems.

---

## Customer Model

**File:** `app/Models/Customer.php`

### Purpose
Represents a customer in the debt collection system.

### Attributes
| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Customer name |
| `phone` | string | Contact phone number |
| `address` | text | Customer address |
| `opening_balance` | decimal | Initial account balance |
| `balance_type` | enum | 'debit' or 'credit' |

### Relationships
| Method | Type | Returns | Description |
|--------|------|---------|-------------|
| `collections()` | HasMany | Collection | All payments received from this customer |
| `cheques()` | HasMany | Cheque | All cheques from this customer |
| `collectionPlanItems()` | HasMany | CollectionPlanItem | All plan items assigned to this customer |
| `accounts()` | HasMany | CustomerAccount | Complete ledger history |

### Methods
```php
// Get current balance (last ledger entry balance or opening balance)
$customer->getCurrentBalance(): string
```

### Usage Examples
```php
// Get a customer with all relationships
$customer = Customer::with('collections', 'accounts')->find($id);

// Get customer balance
$balance = $customer->getCurrentBalance();

// Get customer's recent collections
$recentCollections = $customer->collections()->latest()->take(5)->get();

// Access ledger
$ledger = $customer->accounts()->orderBy('date')->get();
```

---

## Collector Model

**File:** `app/Models/Collector.php`

### Purpose
Represents a payment collector who records collections and manages collection plans.

### Attributes
| Attribute | Type | Description |
|-----------|------|-------------|
| `name` | string | Collector name |
| `phone` | string | Contact phone |
| `area` | string | Service area/region |

### Relationships
| Method | Type | Returns | Description |
|--------|------|---------|-------------|
| `user()` | HasOne | User | Associated user account (nullable) |
| `collectionPlans()` | HasMany | CollectionPlan | All collection plans assigned to this collector |
| `collections()` | HasMany | Collection | All collections recorded by this collector |

### Usage Examples
```php
// Get collector with user and recent plans
$collector = Collector::with('user', 'collectionPlans')->find($id);

// Get collector's collections with customer info
$collections = $collector->collections()->with('customer')->get();

// Get total collected amount
$totalCollected = $collector->collections()->sum('amount');
```

---

## Collection Model

**File:** `app/Models/Collection.php`

### Purpose
Represents a payment transaction recorded by a collector.

### Attributes
| Attribute | Type | Description |
|-----------|------|-------------|
| `customer_id` | bigint | FK → customers |
| `collector_id` | bigint | FK → collectors |
| `amount` | decimal | Payment amount |
| `payment_type` | enum | 'cash' or 'cheque' |
| `collection_date` | date | When payment was made |
| `receipt_no` | varchar | Unique receipt number |
| `notes` | text | Optional notes |

### Relationships
| Method | Type | Returns | Description |
|--------|------|---------|-------------|
| `customer()` | BelongsTo | Customer | Customer who made payment |
| `collector()` | BelongsTo | Collector | Collector who recorded it |
| `accountEntry()` | BelongsTo | CustomerAccount | Corresponding ledger entry |

### Automatic Behavior
When a Collection is created:
1. Automatically creates a CustomerAccount ledger entry
2. Sets reference_type='Collection' and reference_id to collection ID
3. Records payment as 'credit' entry
4. Updates customer balance

### Usage Examples
```php
// Create a collection (automatically updates customer balance)
$collection = Collection::create([
    'customer_id' => 1,
    'collector_id' => 1,
    'amount' => 500.00,
    'payment_type' => 'cash',
    'collection_date' => now(),
    'receipt_no' => 'RCP-' . time(),
    'notes' => 'Payment for January',
]);

// Get collection with customer and ledger entry
$collection = Collection::with('customer', 'accountEntry')->find($id);

// Get cash collections only
$cashCollections = Collection::where('payment_type', 'cash')->get();
```

---

## Cheque Model

**File:** `app/Models/Cheque.php`

### Purpose
Represents a cheque payment record with status tracking.

### Attributes
| Attribute | Type | Description |
|-----------|------|-------------|
| `customer_id` | bigint | FK → customers |
| `cheque_no` | varchar | Cheque number |
| `bank_name` | varchar | Issuing bank |
| `amount` | decimal | Cheque amount |
| `due_date` | date | Expected clearance date |
| `status` | enum | 'pending', 'cleared', or 'bounced' |

### Relationships
| Method | Type | Returns | Description |
|--------|------|---------|-------------|
| `customer()` | BelongsTo | Customer | Customer who issued cheque |

### Helper Methods
```php
$cheque->isPending(): bool    // Check if status is 'pending'
$cheque->isCleared(): bool    // Check if status is 'cleared'
$cheque->isBounced(): bool    // Check if status is 'bounced'
```

### Usage Examples
```php
// Create a cheque
$cheque = Cheque::create([
    'customer_id' => 1,
    'cheque_no' => 'CHQ-12345',
    'bank_name' => 'State Bank',
    'amount' => 1000.00,
    'due_date' => '2026-01-20',
    'status' => 'pending',
]);

// Check cheque status
if ($cheque->isPending()) {
    // Follow up with customer
}

// Get pending cheques
$pending = Cheque::where('status', 'pending')->get();

// Mark cheque as cleared
$cheque->update(['status' => 'cleared']);
```

---

## CollectionPlan Model

**File:** `app/Models/CollectionPlan.php`

### Purpose
Represents a planned collection schedule for a specific collector on a specific date.

### Attributes
| Attribute | Type | Description |
|-----------|------|-------------|
| `collector_id` | bigint | FK → collectors |
| `date` | date | Planned collection date |
| `type` | enum | Collection type (e.g., 'cash', 'cheque', 'follow-up') |

### Relationships
| Method | Type | Returns | Description |
|--------|------|---------|-------------|
| `collector()` | BelongsTo | Collector | Collector assigned to plan |
| `items()` | HasMany | CollectionPlanItem | Individual customers in plan |

### Methods
```php
// Get total expected amount from all items
$plan->getTotalExpectedAmount(): string
```

### Usage Examples
```php
// Create a collection plan
$plan = CollectionPlan::create([
    'collector_id' => 1,
    'date' => '2026-01-15',
    'type' => 'cash',
]);

// Get plan with all items and customers
$plan = CollectionPlan::with('items.customer')->find($id);

// Add items to plan (see CollectionPlanItem)
$plan->items()->create([
    'customer_id' => 1,
    'expected_amount' => 500.00,
    'priority' => 1,
]);

// Get total expected collections
$total = $plan->getTotalExpectedAmount();
```

---

## CollectionPlanItem Model

**File:** `app/Models/CollectionPlanItem.php`

### Purpose
Represents an individual customer assignment within a collection plan.

### Attributes
| Attribute | Type | Description |
|-----------|------|-------------|
| `collection_plan_id` | bigint | FK → collection_plans (cascade delete) |
| `customer_id` | bigint | FK → customers |
| `expected_amount` | decimal | Expected payment from this customer |
| `priority` | int | Priority ranking (lower = higher) |

### Relationships
| Method | Type | Returns | Description |
|--------|------|---------|-------------|
| `collectionPlan()` | BelongsTo | CollectionPlan | Parent collection plan |
| `customer()` | BelongsTo | Customer | Assigned customer |

### Usage Examples
```php
// Create plan item
$item = CollectionPlanItem::create([
    'collection_plan_id' => 1,
    'customer_id' => 5,
    'expected_amount' => 750.00,
    'priority' => 2,
]);

// Get items sorted by priority
$items = $plan->items()->orderBy('priority')->get();

// Get all customers in a plan with their expected amounts
$customers = $plan->items()->with('customer')->orderBy('priority')->get();
```

---

## CustomerAccount Model

**File:** `app/Models/CustomerAccount.php`

### Purpose
Represents a ledger entry tracking debit/credit balance changes (polymorphic).

### Attributes
| Attribute | Type | Description |
|-----------|------|-------------|
| `customer_id` | bigint | FK → customers (cascade delete) |
| `date` | date | Transaction date |
| `description` | varchar | Transaction description |
| `debit` | decimal | Debit amount (owed) |
| `credit` | decimal | Credit amount (payment) |
| `balance` | decimal | Running balance |
| `reference_type` | varchar | Polymorphic type ('Collection', 'Cheque', etc.) |
| `reference_id` | bigint | Polymorphic ID |

### Relationships
| Method | Type | Returns | Description |
|--------|------|---------|-------------|
| `customer()` | BelongsTo | Customer | Customer this entry belongs to |

### Methods
```php
// Get referenced transaction (polymorphic)
$entry->referenceable()     // Returns Collection or Cheque based on reference_type

// Check entry type
$entry->isDebit(): bool     // True if debit amount > 0
$entry->isCredit(): bool    // True if credit amount > 0
```

### Usage Examples
```php
// Get customer ledger sorted by date
$ledger = $customer->accounts()->orderBy('date')->get();

// Calculate balance at specific date
$balance = $customer->accounts()
    ->where('date', '<=', '2026-01-15')
    ->latest('date')
    ->first()
    ->balance;

// Get only credit entries (payments)
$payments = $customer->accounts()
    ->where('credit', '>', 0)
    ->get();

// Access original transaction
$account = CustomerAccount::where('reference_type', 'Collection')->first();
$collection = $account->referenceable(); // Returns Collection
```

---

## Controllers Overview

All controllers follow Laravel resource conventions and return JSON or views based on route definition.

---

## CustomerController

**File:** `app/Http/Controllers/CustomerController.php`

### Routes (Resource)
| Method | Route | Action | Description |
|--------|-------|--------|-------------|
| GET | `/customers` | index | List all customers |
| GET | `/customers/create` | create | Show create form |
| POST | `/customers` | store | Save new customer |
| GET | `/customers/{customer}` | show | Display customer details |
| GET | `/customers/{customer}/edit` | edit | Show edit form |
| PUT/PATCH | `/customers/{customer}` | update | Update customer |
| DELETE | `/customers/{customer}` | destroy | Delete customer |

### Validation Rules
**store/update:**
```
name: required|string|max:255
phone: required|string|max:20
address: required|string
opening_balance: required|numeric|min:0
balance_type: required|in:debit,credit
```

### Usage
```php
// In routes/web.php
Route::resource('customers', CustomerController::class);

// Access routes
/customers              → index
/customers/create       → create
/customers/1            → show
/customers/1/edit       → edit
```

---

## CollectorController

**File:** `app/Http/Controllers/CollectorController.php`

### Routes (Resource)
| Method | Route | Action | Description |
|--------|-------|--------|-------------|
| GET | `/collectors` | index | List all collectors |
| GET | `/collectors/create` | create | Show create form |
| POST | `/collectors` | store | Save new collector |
| GET | `/collectors/{collector}` | show | Display collector details |
| GET | `/collectors/{collector}/edit` | edit | Show edit form |
| PUT/PATCH | `/collectors/{collector}` | update | Update collector |
| DELETE | `/collectors/{collector}` | destroy | Delete collector |

### Validation Rules
**store/update:**
```
name: required|string|max:255
phone: required|string|max:20
area: required|string|max:255
```

---

## CollectionController

**File:** `app/Http/Controllers/CollectionController.php`

### Routes (Resource)
| Method | Route | Action | Description |
|--------|-------|--------|-------------|
| GET | `/collections` | index | List all collections |
| GET | `/collections/create` | create | Show create form |
| POST | `/collections` | store | Save new collection |
| GET | `/collections/{collection}` | show | Display collection details |
| GET | `/collections/{collection}/edit` | edit | Show edit form |
| PUT/PATCH | `/collections/{collection}` | update | Update collection |
| DELETE | `/collections/{collection}` | destroy | Delete collection |

### Validation Rules
**store/update:**
```
customer_id: required|exists:customers,id
collector_id: required|exists:collectors,id
amount: required|numeric|min:0.01
payment_type: required|in:cash,cheque
collection_date: required|date
receipt_no: required|string|unique:collections,receipt_no
notes: nullable|string
```

### Important Notes
- Receipt number must be unique across all collections
- Collection automatically creates CustomerAccount ledger entry
- Customer balance is updated when collection is created

---

## ChequeController

**File:** `app/Http/Controllers/ChequeController.php`

### Routes (Resource)
| Method | Route | Action | Description |
|--------|-------|--------|-------------|
| GET | `/cheques` | index | List all cheques |
| GET | `/cheques/create` | create | Show create form |
| POST | `/cheques` | store | Save new cheque |
| GET | `/cheques/{cheque}` | show | Display cheque details |
| GET | `/cheques/{cheque}/edit` | edit | Show edit form |
| PUT/PATCH | `/cheques/{cheque}` | update | Update cheque |
| DELETE | `/cheques/{cheque}` | destroy | Delete cheque |

### Validation Rules
**store/update:**
```
customer_id: required|exists:customers,id
cheque_no: required|string|max:50
bank_name: required|string|max:255
amount: required|numeric|min:0.01
due_date: required|date
status: required|in:pending,cleared,bounced
```

---

## CollectionPlanController

**File:** `app/Http/Controllers/CollectionPlanController.php`

### Routes (Resource)
| Method | Route | Action | Description |
|--------|-------|--------|-------------|
| GET | `/collection-plans` | index | List all plans |
| GET | `/collection-plans/create` | create | Show create form |
| POST | `/collection-plans` | store | Save new plan |
| GET | `/collection-plans/{collectionPlan}` | show | Display plan details |
| GET | `/collection-plans/{collectionPlan}/edit` | edit | Show edit form |
| PUT/PATCH | `/collection-plans/{collectionPlan}` | update | Update plan |
| DELETE | `/collection-plans/{collectionPlan}` | destroy | Delete plan |

### Validation Rules
**store/update:**
```
collector_id: required|exists:collectors,id
date: required|date
type: required|string|max:255
```

### Important Notes
- Deleting a plan cascades and deletes all associated CollectionPlanItems
- Load items with `with('items.customer')` to display

---

## CollectionPlanItemController

**File:** `app/Http/Controllers/CollectionPlanItemController.php`

### Routes (Resource)
| Method | Route | Action | Description |
|--------|-------|--------|-------------|
| GET | `/collection-plan-items` | index | List all items |
| GET | `/collection-plan-items/create` | create | Show create form |
| POST | `/collection-plan-items` | store | Save new item |
| GET | `/collection-plan-items/{collectionPlanItem}` | show | Display item details |
| GET | `/collection-plan-items/{collectionPlanItem}/edit` | edit | Show edit form |
| PUT/PATCH | `/collection-plan-items/{collectionPlanItem}` | update | Update item |
| DELETE | `/collection-plan-items/{collectionPlanItem}` | destroy | Delete item |

### Validation Rules
**store/update:**
```
collection_plan_id: required|exists:collection_plans,id
customer_id: required|exists:customers,id
expected_amount: required|numeric|min:0.01
priority: required|integer|min:1
```

---

## CustomerAccountController

**File:** `app/Http/Controllers/CustomerAccountController.php`

### Routes (Custom)
| Method | Route | Action | Description |
|--------|-------|--------|-------------|
| GET | `/customer-accounts` | index | List all ledger entries |
| GET | `/customers/{customer}/ledger` | customerLedger | Show customer's full ledger |
| GET | `/customer-accounts/{customerAccount}` | show | Display entry details |

### Read-Only Controller
- No create, store, update, or destroy methods
- Ledger entries are created automatically by Collection/Cheque models
- Manual entry creation should happen through Collection creation

### Usage
```php
// In routes/web.php
Route::get('/customer-accounts', [CustomerAccountController::class, 'index']);
Route::get('/customers/{customer}/ledger', [CustomerAccountController::class, 'customerLedger']);
Route::get('/customer-accounts/{customerAccount}', [CustomerAccountController::class, 'show']);

// Access routes
/customer-accounts                    → Full ledger listing
/customers/1/ledger                  → Customer 1's complete ledger
/customer-accounts/5                 → Specific ledger entry
```

---

## Setup Instructions

### 1. Register Routes
Add to `routes/web.php`:
```php
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('collectors', CollectorController::class);
    Route::resource('collections', CollectionController::class);
    Route::resource('cheques', ChequeController::class);
    Route::resource('collection-plans', CollectionPlanController::class);
    Route::resource('collection-plan-items', CollectionPlanItemController::class);
    
    Route::get('/customer-accounts', [CustomerAccountController::class, 'index']);
    Route::get('/customers/{customer}/ledger', [CustomerAccountController::class, 'customerLedger']);
    Route::get('/customer-accounts/{customerAccount}', [CustomerAccountController::class, 'show']);
});
```

### 2. Create Views
Create view directories and templates in `resources/views/`:
- `customers/` (index, create, edit, show)
- `collectors/` (index, create, edit, show)
- `collections/` (index, create, edit, show)
- `cheques/` (index, create, edit, show)
- `collection-plans/` (index, create, edit, show)
- `collection-plan-items/` (index, create, edit, show)
- `customer-accounts/` (index, ledger, show)

### 3. Create Factories (for Testing)
Generate factories for models:
```bash
php artisan make:factory CustomerFactory --model=Customer
php artisan make:factory CollectorFactory --model=Collector
php artisan make:factory CollectionFactory --model=Collection
php artisan make:factory ChequeFactory --model=Cheque
php artisan make:factory CollectionPlanFactory --model=CollectionPlan
php artisan make:factory CollectionPlanItemFactory --model=CollectionPlanItem
php artisan make:factory CustomerAccountFactory --model=CustomerAccount
```

### 4. Run Migrations
```bash
php artisan migrate
```

---

## Query Examples

### Get Customer with Full Data
```php
$customer = Customer::with([
    'collections.collector',
    'cheques',
    'accounts',
])->find($id);
```

### Get Collector's Daily Collections
```php
$collections = Collection::where('collector_id', $id)
    ->whereDate('collection_date', today())
    ->with('customer')
    ->get();
```

### Get Customer Balance on Specific Date
```php
$balance = $customer->accounts()
    ->where('date', '<=', $date)
    ->orderByDesc('date')
    ->value('balance');
```

### Get Pending Cheques Overdue
```php
$overdue = Cheque::where('status', 'pending')
    ->where('due_date', '<', today())
    ->with('customer')
    ->get();
```

### Generate Daily Collection Report
```php
$collections = Collection::whereDate('collection_date', today())
    ->with(['customer', 'collector'])
    ->groupBy('collector_id')
    ->selectRaw('collector_id, SUM(amount) as total')
    ->get();
```
