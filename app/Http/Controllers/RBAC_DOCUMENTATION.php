<?php

/**
 * Role-Based Access Control Documentation
 *
 * This document outlines the role hierarchy and permissions in the Collection System.
 */

/**
 * ROLES DEFINED:
 *
 * 1. Admin (Full System Access)
 *    - Create, read, update, delete all resources
 *    - Manage customers, collectors, collections, cheques
 *    - Create and manage collection plans
 *    - View all ledger entries and reports
 *    - Access to admin dashboard with system-wide metrics
 *
 * 2. Collector (Limited Operational Access)
 *    - View customers assigned to their collection plans
 *    - Create and view collections for assigned customers
 *    - View and manage cheques for their collections
 *    - Access collector dashboard with personal metrics
 *    - Cannot: Create/delete customers, create collection plans, delete records
 *
 * 3. User (View-Only Access)
 *    - View system data (read-only)
 *    - Access user dashboard with limited information
 *    - Cannot: Create, update, or delete any resources
 */

namespace App\Http\Controllers;

return [
    'admin' => [
        'description' => 'Full system administrator with complete access',
        'permissions' => [
            'customers' => ['create', 'read', 'update', 'delete'],
            'collectors' => ['create', 'read', 'update', 'delete'],
            'collections' => ['create', 'read', 'update', 'delete'],
            'cheques' => ['create', 'read', 'update', 'delete'],
            'collection_plans' => ['create', 'read', 'update', 'delete'],
            'collection_plan_items' => ['create', 'read', 'update', 'delete'],
            'customer_accounts' => ['read'],
        ],
        'dashboard' => 'admin',
    ],

    'collector' => [
        'description' => 'Collection agent with operational access',
        'permissions' => [
            'customers' => ['read'],
            'collectors' => ['read'],
            'collections' => ['create', 'read'],
            'cheques' => ['read'],
            'collection_plans' => ['read'],
            'collection_plan_items' => ['read'],
            'customer_accounts' => ['read'],
        ],
        'dashboard' => 'collector',
        'restrictions' => [
            'Only view customers assigned to their collection plans',
            'Can only view collections they created',
            'Cannot delete or modify collection plans',
        ],
    ],

    'user' => [
        'description' => 'Regular user with view-only access',
        'permissions' => [
            'customers' => ['read'],
            'collectors' => ['read'],
            'collections' => ['read'],
            'cheques' => ['read'],
            'collection_plans' => ['read'],
            'collection_plan_items' => ['read'],
            'customer_accounts' => ['read'],
        ],
        'dashboard' => 'user',
        'restrictions' => [
            'View-only access to all resources',
            'Cannot create, update, or delete any records',
        ],
    ],
];
