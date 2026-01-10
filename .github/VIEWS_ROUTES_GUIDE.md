
# Views & Routes Guide

## Route Structure

All routes are protected by `auth` and `role:Admin` middleware. Update `routes/web.php` with the provided route definitions.

### Route Definitions
```php
Route::middleware(['auth', 'role:Admin'])->group(function () {
    // Customer Management
    Route::resource('customers', CustomerController::class);
    
    // Collector Management
    Route::resource('collectors', CollectorController::class);
    
    // Collection Records
    Route::resource('collections', CollectionController::class);
    
    // Cheque Management
    Route::resource('cheques', ChequeController::class);
    
    // Collection Plans
    Route::resource('collection-plans', CollectionPlanController::class);
    
    // Collection Plan Items
    Route::resource('collection-plan-items', CollectionPlanItemController::class);
    
    // Customer Accounts (Ledger) - Read Only
    Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer-accounts.index');
    Route::get('/customers/{customer}/ledger', [CustomerAccountController::class, 'customerLedger'])->name('customer-accounts.ledger');
    Route::get('/customer-accounts/{customerAccount}', [CustomerAccountController::class, 'show'])->name('customer-accounts.show');
});
```

---

## View Structure

All views use Blade templating with Tailwind CSS styling. Views are organized by resource in `resources/views/`.

### Directory Structure
```
resources/views/
├── layouts/
│   └── app.blade.php          (Base layout with navigation)
├── customers/
│   ├── index.blade.php        (List customers)
│   ├── create.blade.php       (Create form)
│   ├── edit.blade.php         (Edit form)
│   └── show.blade.php         (Customer details & summary)
├── collectors/
│   ├── index.blade.php        (List collectors)
│   ├── create.blade.php       (Create form)
│   ├── edit.blade.php         (Edit form)
│   └── show.blade.php         (Collector details & activity)
├── collections/
│   ├── index.blade.php        (List collections)
│   ├── create.blade.php       (Record collection)
│   ├── edit.blade.php         (Edit collection)
│   └── show.blade.php         (Collection details & ledger)
├── cheques/
│   ├── index.blade.php        (List cheques)
│   ├── create.blade.php       (Create form)
│   ├── edit.blade.php         (Edit form)
│   └── show.blade.php         (Cheque details & status)
├── collection-plans/
│   ├── index.blade.php        (List plans)
│   ├── create.blade.php       (Create form)
│   ├── edit.blade.php         (Edit form)
│   └── show.blade.php         (Plan details & items)
├── collection-plan-items/
│   ├── index.blade.php        (List items)
│   ├── create.blade.php       (Add item form)
│   ├── edit.blade.php         (Edit item)
│   └── show.blade.php         (Item details)
└── customer-accounts/
    ├── index.blade.php        (All ledger entries)
    ├── ledger.blade.php       (Customer's full ledger)
    └── show.blade.php         (Single entry details)
```

---

## Base Layout (`layouts/app.blade.php`)

The main layout provides:
- Navigation bar with links to all resources
- Responsive Tailwind CSS styling
- Blade `@yield('content')` for page content
- Footer

**Navigation Menu:**
- Customers
- Collectors
- Collections
- Cheques
- Plans
- Ledger

---

## Customer Views

### `customers/index.blade.php`
**Purpose:** List all customers with pagination

**Features:**
- Table with name, phone, opening balance, balance type
- Action buttons: View, Edit, Delete
- Create new customer button
- Success messages
- Pagination links (15 per page)

**Key Data:**
- `$customers` - Paginated collection of customers

**Routes Used:**
- `customers.create` - Create form
- `customers.show` - View customer
- `customers.edit` - Edit form
- `customers.destroy` - Delete

---

### `customers/create.blade.php`
**Purpose:** Form to create new customer

**Form Fields:**
- Name (required)
- Phone (required)
- Address (required, textarea)
- Opening Balance (required, numeric, min: 0)
- Balance Type (required, select: debit/credit)

**Validation:**
- Displays validation errors
- Sets `opening_balance` default to 0
- Balance type explains debit = "Owes Money", credit = "Credit Exists"

**Routes Used:**
- `customers.store` - Save
- `customers.index` - Cancel

---

### `customers/edit.blade.php`
**Purpose:** Form to edit existing customer

**Form Fields:** Same as create (with old values populated)

**Routes Used:**
- `customers.update` - Save
- `customers.show` - Cancel

---

### `customers/show.blade.php`
**Purpose:** Display full customer profile with related data

**Sections:**

1. **Customer Information Card**
   - Name, Phone, Address
   - Opening Balance, Balance Type

2. **Account Summary Card**
   - Current Balance (calculated)
   - Total Collections count
   - Total Cheques count
   - Ledger Entries count

3. **Recent Collections Table**
   - Last 5 collections
   - Columns: Receipt No, Amount, Type, Date, Collector
   - Link to full ledger if more than 5

4. **Recent Cheques Table**
   - Last 5 cheques
   - Columns: Cheque No, Amount, Bank, Due Date, Status

**Routes Used:**
- `customers.edit` - Edit button
- `customers.index` - Back
- `customer-accounts.ledger` - View full ledger link
- `collections.show` - Collection details
- `cheques.show` - Cheque details

---

## Collector Views

### `collectors/index.blade.php`
**Purpose:** List all collectors

**Table Columns:**
- Name
- Phone
- Area
- User (assigned user or "Not assigned")
- Actions: View, Edit, Delete

**Routes Used:**
- `collectors.create` - Create button
- `collectors.show` - View
- `collectors.edit` - Edit
- `collectors.destroy` - Delete

---

### `collectors/create.blade.php` & `collectors/edit.blade.php`
**Form Fields:**
- Name (required)
- Phone (required)
- Area (required)

---

### `collectors/show.blade.php`
**Purpose:** Display collector profile with activity

**Sections:**

1. **Collector Information**
   - Name, Phone, Area, Assigned User

2. **Activity Summary**
   - Total Collections (count)
   - Total Collected (sum of amounts)
   - Collection Plans (count)

3. **Recent Collections Table**
   - Columns: Receipt No, Customer, Amount, Type, Date
   - Last 5 entries

4. **Collection Plans Table**
   - Columns: Date, Type, Items, Expected Amount
   - Last 5 plans

---

## Collection Views

### `collections/index.blade.php`
**Purpose:** List all payment collections

**Table Columns:**
- Receipt No
- Customer
- Collector
- Amount (bold)
- Type (cash/cheque with badge)
- Date
- Actions

---

### `collections/create.blade.php` & `collections/edit.blade.php`
**Form Fields:**
- Customer (required, select)
- Collector (required, select)
- Amount (required, numeric, min: 0.01)
- Payment Type (required, select: cash/cheque)
- Collection Date (required, date picker, default: today)
- Receipt No (required, unique string, e.g., "RCP-2026010001")
- Notes (optional, textarea)

**Important:**
- When creating, automatically creates CustomerAccount ledger entry
- Receipt number must be unique

---

### `collections/show.blade.php`
**Purpose:** Display collection details and ledger entry

**Sections:**

1. **Collection Details**
   - Receipt No
   - Customer (linked)
   - Collector (linked)
   - Collection Date

2. **Payment Information**
   - Amount (large, green)
   - Payment Type (badge)
   - Status (Recorded)

3. **Notes** (if present)

4. **Ledger Entry**
   - Entry Date
   - Description
   - Credit Amount
   - Balance After

---

## Cheque Views

### `cheques/index.blade.php`
**Purpose:** List all cheques

**Table Columns:**
- Cheque No (bold)
- Customer
- Bank
- Amount
- Due Date
- Status (badge: pending=yellow, cleared=green, bounced=red)
- Actions

---

### `cheques/create.blade.php` & `cheques/edit.blade.php`
**Form Fields:**
- Customer (required, select)
- Cheque No (required, string, max 50)
- Bank Name (required)
- Amount (required, numeric, min: 0.01)
- Due Date (required, date picker)
- Status (required, select: pending/cleared/bounced)

---

### `cheques/show.blade.php`
**Purpose:** Display cheque details

**Sections:**

1. **Cheque Details**
   - Cheque No
   - Customer (linked)
   - Bank
   - Due Date

2. **Payment Information**
   - Amount (large, blue)
   - Status (badge)
   - Overdue warning (if pending and due date < today)

---

## Collection Plan Views

### `collection-plans/index.blade.php`
**Purpose:** List all collection plans

**Table Columns:**
- Date
- Collector
- Type
- Items (count)
- Expected Amount (total)
- Actions

---

### `collection-plans/create.blade.php` & `collection-plans/edit.blade.php`
**Form Fields:**
- Collector (required, select)
- Plan Date (required, date picker)
- Type (required, text, e.g., "Cash", "Cheque", "Follow-up")

---

### `collection-plans/show.blade.php`
**Purpose:** Display plan details and assigned items

**Sections:**

1. **Plan Information (3 cards)**
   - Date, Type, Collector

2. **Summary (3 cards)**
   - Total Items (count)
   - Expected Amount (sum)
   - Status

3. **Plan Items Table**
   - Columns: Priority, Customer, Expected Amount, Actions
   - Sorted by priority (ascending)
   - Add/Edit/Delete options

---

## Collection Plan Item Views

### `collection-plan-items/index.blade.php`
**Purpose:** List all plan items

**Table Columns:**
- Plan Date
- Collector
- Customer
- Priority
- Expected Amount
- Actions

---

### `collection-plan-items/create.blade.php` & `collection-plan-items/edit.blade.php`
**Form Fields:**
- Collection Plan (required, select, formatted as "Date - Collector")
- Customer (required, select)
- Expected Amount (required, numeric, min: 0.01)
- Priority (required, integer, min: 1, note: "Lower numbers = higher priority")

---

### `collection-plan-items/show.blade.php`
**Purpose:** Display item details

**Sections:**

1. **Item Details**
   - Plan (linked)
   - Collector (linked)
   - Customer (linked)
   - Priority (badge)

2. **Amount Information**
   - Expected Amount (large, green)
   - Customer's Current Balance

---

## Customer Account Views

### `customer-accounts/index.blade.php`
**Purpose:** Display all ledger entries system-wide

**Table Columns:**
- Customer (linked)
- Date
- Description
- Debit (red, or "-")
- Credit (green, or "-")
- Balance (bold)
- Reference Type

**Pagination:** 15 per page

---

### `customer-accounts/ledger.blade.php`
**Purpose:** Display full ledger for a specific customer

**Sections:**

1. **Summary Cards (3 columns)**
   - Customer Name & Phone
   - Balance Type (badge)
   - Current Balance (large, blue)

2. **Ledger Table**
   - Columns: Date, Description, Reference, Debit, Credit, Balance
   - Sorted by date (descending/latest first)
   - All entries for customer

---

### `customer-accounts/show.blade.php`
**Purpose:** Display single ledger entry details

**Sections:**

1. **Entry Information**
   - Customer (linked)
   - Date
   - Description
   - Reference Type & ID

2. **Amount Details**
   - Debit (red, large)
   - Credit (green, large)
   - Running Balance (blue, largest)

3. **Link to Full Ledger**

---

## Styling & Design

### Color Scheme
- **Primary Blue:** Navigation, buttons, important links
- **Green:** Success, credit entries, positive amounts
- **Red:** Danger, debit entries, negative amounts
- **Yellow:** Warning, pending status
- **Gray:** Neutral, secondary information

### Component Patterns
- **Tables:** Responsive with horizontal scroll
- **Forms:** Centered max-width containers
- **Cards:** White background, shadow, padding
- **Buttons:** Primary (blue), secondary (gray), danger (red)
- **Badges:** Inline status indicators
- **Pagination:** Links at bottom

### Typography
- **Headings:** H1 (3xl, bold), H2 (lg, bold)
- **Form Labels:** Small, bold, gray
- **Error Messages:** Red background with red text
- **Success Messages:** Green background with green text

---

## Common Blade Patterns

### Conditional Display
```blade
@if ($message = Session::get('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ $message }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif
```

### Status Badges
```blade
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
    {{ $status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
    {{ ucfirst($status) }}
</span>
```

### Form Field Pattern
```blade
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Field Label *</label>
    <input type="text" name="field_name" value="{{ old('field_name', $default) }}" 
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
        required>
</div>
```

### Links & Navigation
```blade
<a href="{{ route('resource.show', $item) }}" class="text-blue-600 hover:text-blue-900">View</a>
<a href="{{ route('resource.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
```

---

## Getting Started

### 1. Verify Routes
Ensure `routes/web.php` has all resource routes configured.

### 2. Create Views Directory
```bash
mkdir -p resources/views/customers
mkdir -p resources/views/collectors
mkdir -p resources/views/collections
mkdir -p resources/views/cheques
mkdir -p resources/views/collection-plans
mkdir -p resources/views/collection-plan-items
mkdir -p resources/views/customer-accounts
mkdir -p resources/views/layouts
```

### 3. Add Layout
Create `resources/views/layouts/app.blade.php` with base navigation.

### 4. Access Views
Navigate to:
- `/customers` - Customer list
- `/collectors` - Collector list
- `/collections` - Collection list
- `/cheques` - Cheque list
- `/collection-plans` - Plans list
- `/collection-plan-items` - Items list
- `/customer-accounts` - Ledger list
- `/customers/{id}/ledger` - Customer's ledger

### 5. Styling
Uses **Tailwind CSS v3** via CDN. For production, configure Tailwind in `tailwind.config.js` and compile with `npm run build`.

---

## Form Submission Flow

### Create/Store Flow
1. Click "New" button → `resource.create` view (form)
2. Fill form fields
3. Submit → `resource.store` POST route
4. Controller validates (Form Request)
5. Model created → Redirect to index with success message

### Edit/Update Flow
1. Click "Edit" button → `resource.edit` view (form with old values)
2. Modify fields
3. Submit → `resource.update` PUT route
4. Controller validates
5. Model updated → Redirect to show with success message

### Delete Flow
1. Click "Delete" → Inline form POST with DELETE method
2. JavaScript confirm dialog
3. If confirmed → `resource.destroy` DELETE route
4. Model deleted → Redirect to index with success message

---

## Responsive Design Notes

All views use **Tailwind CSS responsive utilities**:
- `grid-cols-1 md:grid-cols-2 lg:grid-cols-3` - Responsive columns
- `overflow-x-auto` - Horizontal scroll on mobile for tables
- `px-4 md:px-6` - Responsive padding
- Navigation menu may need mobile hamburger (not included)

For mobile optimization, consider adding a hamburger menu in `layouts/app.blade.php`.
