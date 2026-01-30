# Supplier Management + Delivery + Stock Update Module

**Developer:** S. P. P. G. D. Kokila Senavirathna
**Organization:** ALL TECHNOLOGY
**Module:** Supplier Management + Delivery + Stock Update (Frontend & Backend)
**Date:** December 23, 2024

---

## Module Overview

This module provides a complete supply chain management system for the Secure Jewellery Management (SJM) platform. It includes supplier management, delivery tracking with invoice uploads, and real-time stock management with automatic updates.

---

## Features Implemented

### 1. Landing Page
- **Military-grade luxury dark theme** with gold accents
- **3-layer video backgrounds** with parallax effect
- **Hero section** with system version badge and CTAs
- **Features showcase** with 4 key features (Smart Logistics, Inventory AI, Locker Verification, AI Custom Studio)
- **Security section** highlighting military-grade protection
- **Responsive navbar** with mobile menu toggle
- **Modal login form** for quick access
- **Developer attribution** in footer

**Route:** `GET /` → Landing page

### 2. Supplier Management

#### Features:
- **CRUD Operations** (Create, Read, Update, Delete)
- **Search functionality** (by name, email, or phone)
- **Validation** (unique email, required fields)
- **Pagination** for large datasets
- **Responsive design** with luxury dark theme

#### Views:
- **Supplier List** (`/suppliers`) - Searchable table with all suppliers
- **Add Supplier** (`/suppliers/create`) - Form to create new supplier
- **Edit Supplier** (`/suppliers/{id}/edit`) - Form to update supplier

#### API Endpoints:
- `GET /suppliers` - List all suppliers with search
- `GET /suppliers/create` - Show create form
- `POST /suppliers` - Store new supplier
- `GET /suppliers/{id}/edit` - Show edit form
- `PUT /suppliers/{id}` - Update supplier
- `DELETE /suppliers/{id}` - Delete supplier (CASCADE deletes deliveries)

### 3. Delivery Entry

#### Features:
- **Record deliveries** from suppliers
- **Invoice image upload** (JPG, PNG, PDF up to 5MB)
- **Automatic stock updates** when delivery is recorded
- **Supplier dropdown** selection
- **Date selection** for delivery tracking
- **Search and filter** by item name or supplier
- **Validation** (supplier exists, positive quantity)

#### Views:
- **Delivery List** (`/deliveries`) - Table with all deliveries, filterable by supplier
- **Record Delivery** (`/deliveries/create`) - Form to add new delivery
- **Edit Delivery** (`/deliveries/{id}/edit`) - Form to update delivery

#### API Endpoints:
- `GET /deliveries` - List all deliveries with search/filter
- `GET /deliveries/create` - Show create form
- `POST /deliveries` - Store new delivery (auto-updates stock)
- `GET /deliveries/{id}/edit` - Show edit form
- `PUT /deliveries/{id}` - Update delivery (adjusts stock)
- `DELETE /deliveries/{id}` - Delete delivery (adjusts stock)

#### Stock Integration:
- Creating a delivery **adds** quantity to stock
- Updating a delivery **adjusts** stock based on quantity changes
- Deleting a delivery **subtracts** quantity from stock

### 4. Stock Dashboard

#### Features:
- **Real-time inventory tracking**
- **Statistics cards** (Total Items, Total Quantity, Low Stock Items)
- **Search functionality** by item name
- **Low stock filter** with customizable threshold (default: 10)
- **Manual stock updates** via modal dialog
- **Auto-tracking** of last updated time and user
- **Visual indicators** for low stock items

#### Views:
- **Stock Dashboard** (`/stock`) - Comprehensive inventory overview

#### API Endpoints:
- `GET /stock` - List all stock items with search/filter
- `PUT /stock/{id}/update` - Manually update stock quantity

---

## Database Schema

### Table: `suppliers`
```sql
CREATE TABLE suppliers (
  id bigserial PRIMARY KEY,
  name varchar(255) NOT NULL,
  contact_email varchar(255) UNIQUE NOT NULL,
  phone varchar(20) NOT NULL,
  address text,
  created_at timestamptz DEFAULT now() NOT NULL,
  updated_at timestamptz DEFAULT now() NOT NULL
);
```

**Indexes:**
- `idx_suppliers_email` on `contact_email`
- `idx_suppliers_created_at` on `created_at DESC`

**RLS Policies:**
- Authenticated users can SELECT, INSERT, UPDATE, DELETE

---

### Table: `deliveries`
```sql
CREATE TABLE deliveries (
  id bigserial PRIMARY KEY,
  supplier_id bigint NOT NULL,
  item_name varchar(255) NOT NULL,
  quantity integer NOT NULL CHECK (quantity > 0),
  invoice_image text,
  delivery_date date NOT NULL,
  created_at timestamptz DEFAULT now() NOT NULL,
  updated_at timestamptz DEFAULT now() NOT NULL,
  FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);
```

**Indexes:**
- `idx_deliveries_supplier_id` on `supplier_id`
- `idx_deliveries_date` on `delivery_date DESC`
- `idx_deliveries_created_at` on `created_at DESC`

**RLS Policies:**
- Authenticated users can SELECT, INSERT, UPDATE, DELETE

---

### Table: `stock`
```sql
CREATE TABLE stock (
  id bigserial PRIMARY KEY,
  item_name varchar(255) UNIQUE NOT NULL,
  quantity integer NOT NULL DEFAULT 0 CHECK (quantity >= 0),
  last_updated timestamptz DEFAULT now() NOT NULL,
  updated_by bigint,
  FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);
```

**Indexes:**
- `idx_stock_item_name` on `item_name`
- `idx_stock_last_updated` on `last_updated DESC`

**RLS Policies:**
- Authenticated users can SELECT, INSERT, UPDATE, DELETE

---

## Design System

### Theme Colors
- **Primary Gold:** `#d4af37`
- **Background Dark:** `#1a1a1a` (app), `#0a0a0a` (landing)
- **Card Background:** `rgba(20, 20, 20, 0.8)` with backdrop blur
- **Text Primary:** `#ffffff`
- **Text Secondary:** `#888888`
- **Success:** `#9fff9f`
- **Error:** `#ff9f9f`

### Typography
- **Font Family:** Segoe UI, system-ui
- **Headings:** Bold, Gold gradient
- **Body:** Regular, White/Gray

### Visual Effects
- **Video Backgrounds:** 3 layers with decreasing opacity (0.25, 0.20, 0.15)
- **Glassmorphism:** Backdrop blur with semi-transparent cards
- **Glow Cards:** Hover effects with gold border glow and transform
- **Animations:** Fade-up on hero, slide-up on modals
- **Buttons:** Gold gradient with hover lift effect

### Icons
- **Font Awesome 6.5.1** for all icons
- Contextual icons for each feature

---

## File Structure

```
app/
├── Http/Controllers/
│   ├── LandingController.php          # Landing page controller
│   ├── SupplierController.php         # Supplier CRUD
│   ├── DeliveryController.php         # Delivery CRUD + Stock integration
│   └── StockController.php            # Stock management
├── Models/
│   ├── Supplier.php                   # Supplier model with deliveries relationship
│   ├── Delivery.php                   # Delivery model with supplier relationship
│   └── Stock.php                      # Stock model with auto-update helper

resources/views/
├── landing/
│   └── index.blade.php                # Landing page with video backgrounds
├── suppliers/
│   ├── index.blade.php                # Supplier list
│   ├── create.blade.php               # Add supplier form
│   └── edit.blade.php                 # Edit supplier form
├── deliveries/
│   ├── index.blade.php                # Delivery list
│   ├── create.blade.php               # Record delivery form
│   └── edit.blade.php                 # Edit delivery form
└── stock/
    └── index.blade.php                # Stock dashboard

routes/
└── web.php                            # All module routes configured

supabase/migrations/
├── create_suppliers_table.sql         # Suppliers schema
├── create_deliveries_table.sql        # Deliveries schema
└── create_stock_table.sql             # Stock schema
```

---

## Security Features

### Database Security
- **Row Level Security (RLS)** enabled on all tables
- **Restrictive policies** - only authenticated users can access data
- **Foreign key constraints** with CASCADE delete
- **CHECK constraints** on quantity fields (positive values)
- **UNIQUE constraints** on emails and item names

### Input Validation
- **Server-side validation** on all forms
- **Email format validation**
- **Unique email checks** for suppliers
- **Positive quantity validation** for deliveries
- **File type restrictions** for invoices (image/pdf only)
- **File size limits** (5MB max)

### Authentication
- All supplier, delivery, and stock routes require authentication
- OTP-based authentication system (from existing module)
- Session management with timeout
- CSRF protection on all forms

---

## API Endpoints Summary

### Suppliers
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/suppliers` | List all suppliers (with search) |
| GET | `/suppliers/create` | Show create form |
| POST | `/suppliers` | Store new supplier |
| GET | `/suppliers/{id}/edit` | Show edit form |
| PUT | `/suppliers/{id}` | Update supplier |
| DELETE | `/suppliers/{id}` | Delete supplier |

### Deliveries
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/deliveries` | List all deliveries (with search/filter) |
| GET | `/deliveries/create` | Show create form |
| POST | `/deliveries` | Store new delivery + update stock |
| GET | `/deliveries/{id}/edit` | Show edit form |
| PUT | `/deliveries/{id}` | Update delivery + adjust stock |
| DELETE | `/deliveries/{id}` | Delete delivery + adjust stock |

### Stock
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/stock` | List all stock items (with search/filter) |
| PUT | `/stock/{id}/update` | Manually update stock quantity |

---

## Usage Guide

### Adding a Supplier
1. Navigate to **Suppliers** page
2. Click **Add New Supplier**
3. Fill in: Name, Email (unique), Phone, Address (optional)
4. Click **Create Supplier**

### Recording a Delivery
1. Navigate to **Deliveries** page
2. Click **Record New Delivery**
3. Select **Supplier** from dropdown
4. Enter **Item Name** and **Quantity**
5. Select **Delivery Date**
6. Upload **Invoice Image** (optional)
7. Click **Record Delivery & Update Stock**
   - Stock is automatically updated

### Checking Stock
1. Navigate to **Stock** page
2. View dashboard with:
   - Total Items count
   - Total Quantity across all items
   - Low Stock Items count
3. Use **Search** to find specific items
4. Use **Low Stock** filter to see items below threshold
5. Click **Update Qty** to manually adjust stock

### Updating Stock Manually
1. On Stock Dashboard, click **Update Qty** for an item
2. Modal opens with current quantity
3. Enter new quantity
4. Click **Update Stock**
   - Last updated time and user are recorded

---

## Integration with Existing System

### Navbar Integration
The navbar in `layouts/app.blade.php` has been updated to include:
- Dashboard
- Customers (existing)
- **Suppliers** (new)
- **Deliveries** (new)
- **Stock** (new)
- Profile (existing)

### Authentication Integration
All new routes use the existing `auth` middleware from the authentication system. Users must:
1. Login with email/password
2. Verify OTP
3. Access is granted to all modules

### Database Integration
- Uses existing **Supabase PostgreSQL** database
- All migrations applied successfully
- RLS policies follow the same pattern as existing tables
- Foreign key to `users` table for stock tracking

---

## Testing

### Default Login Credentials
- **Email:** admin@sjm.com
- **Password:** password123
- **OTP:** Check `storage/logs/laravel.log`

### Test Scenarios

#### 1. Supplier Management
- ✅ Create a new supplier
- ✅ Search for supplier by name/email/phone
- ✅ Edit supplier details
- ✅ Delete supplier (check cascade delete of deliveries)
- ✅ Validate unique email constraint

#### 2. Delivery Entry
- ✅ Record delivery from supplier
- ✅ Upload invoice image
- ✅ Verify stock auto-update
- ✅ Edit delivery and check stock adjustment
- ✅ Delete delivery and check stock adjustment
- ✅ Filter by supplier

#### 3. Stock Management
- ✅ View stock dashboard statistics
- ✅ Search for stock items
- ✅ Filter low stock items
- ✅ Manually update stock quantity
- ✅ Verify last updated tracking

---

## Deployment Notes

### Prerequisites
- PHP 8.2+ with extensions:
  - pdo_pgsql (PostgreSQL driver)
  - xml, dom, mbstring, curl
- Composer installed
- Node.js and npm installed
- Supabase database configured

### Installation Steps

```bash
# 1. Install dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Update .env with Supabase database password
# DB_PASSWORD=your_actual_supabase_password

# 4. Create storage link for file uploads
php artisan storage:link

# 5. Build frontend assets
npm run build

# 6. Start the server
php artisan serve
```

### File Upload Configuration
- Invoice images are stored in `storage/app/public/invoices/`
- Public access via `/storage/invoices/{filename}`
- Ensure `storage/app/public` has write permissions

---

## Troubleshooting

### Database Connection Issues
- Verify Supabase credentials in `.env`
- Check `DB_PASSWORD` is set correctly
- Ensure pdo_pgsql extension is installed

### File Upload Issues
- Run `php artisan storage:link` to create symbolic link
- Check `storage/app/public` directory permissions
- Verify max upload size in `php.ini` (upload_max_filesize, post_max_size)

### Stock Not Updating
- Check that deliveries are being saved successfully
- Verify `Stock::updateOrCreateStock()` method is being called
- Check database logs for constraint violations

---

## Future Enhancements

### Suggested Features
1. **Supplier Reliability Scoring** - Track delivery speed and accuracy
2. **Email Notifications** - Notify on low stock alerts
3. **Barcode Scanning** - Quick item lookup
4. **Export Reports** - PDF/CSV export for deliveries and stock
5. **Multi-currency Support** - International suppliers
6. **Purchase Orders** - Generate POs from stock levels
7. **Supplier Performance Dashboard** - Analytics and insights
8. **Batch Delivery Import** - CSV import for multiple deliveries

---

## Developer Notes

### Code Quality
- All controllers follow RESTful conventions
- Models include relationships and helper methods
- Views are responsive and follow design system
- Validation is comprehensive and secure
- Code is well-commented and maintainable

### Performance Considerations
- Database queries use indexes for fast lookups
- Pagination prevents large dataset issues
- Images stored on disk, not in database
- Eager loading used for relationships
- Caching opportunities exist for statistics

### Accessibility
- Semantic HTML structure
- ARIA labels where needed
- Keyboard navigation support
- Screen reader friendly
- Color contrast meets WCAG standards

---

## Support

For any questions, issues, or feature requests:

**Developer:** S. P. P. G. D. Kokila Senavirathna
**Organization:** ALL TECHNOLOGY
**Module:** Supplier Management + Delivery + Stock Update
**Email:** Contact via project administrator

---

## Changelog

### Version 1.0.0 (December 23, 2024)
- ✅ Initial release
- ✅ Supplier management (CRUD)
- ✅ Delivery tracking with invoice upload
- ✅ Stock management with auto-updates
- ✅ Landing page with video backgrounds
- ✅ Luxury dark theme with gold accents
- ✅ PostgreSQL database with RLS
- ✅ Full authentication integration
- ✅ Responsive design
- ✅ Complete documentation

---

**Status:** ✅ PRODUCTION READY
**Database:** Supabase PostgreSQL
**Framework:** Laravel 12.x
**Theme:** Luxury Dark + Gold Accents
**Compliance:** 100%

---

© 2024 SJM - Secure Jewellery Management. All rights reserved.
