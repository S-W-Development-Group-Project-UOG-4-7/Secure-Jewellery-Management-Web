# Secure Jewellery Management System - Setup Guide

**Developer:** K. M. Nethmi Sanjalee
**Organization:** ALL TECHNOLOGY
**Email:** kokiladulshan021@gmail.com

## Project Overview

This is a luxury dark-themed Secure Jewellery Management System built with Laravel. The application features:

- OTP-based authentication system
- Customer management (CRUD operations)
- Profile management with password change functionality
- Premium luxury dark theme with gold accents
- Multiple video backgrounds for immersive experience
- Responsive design

## Technology Stack

- **Backend:** Laravel 12.x (PHP 8.2+)
- **Frontend:** Blade Templates, Vanilla JavaScript
- **Database:** SQLite
- **Styling:** Custom CSS with luxury dark theme
- **Security:** Bcrypt password hashing, OTP verification, CSRF protection

## Installation Instructions

### Prerequisites

Ensure you have the following installed on your system:

1. PHP 8.2 or higher with the following extensions:
   - PDO
   - SQLite
   - XML
   - DOM
   - cURL
   - mbstring
   - tokenizer
   - OpenSSL
   - JSON

2. Composer (PHP dependency manager)
3. Node.js and npm (for frontend assets)

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 2: Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database file
touch database/database.sqlite
```

### Step 3: Database Setup

```bash
# Run migrations and seed the database
php artisan migrate:fresh --seed
```

This will create:
- Users table (with admin user)
- Customers table
- OTP logs table
- Session tables
- Job tables

**Default Admin Credentials:**
- Email: `admin@sjm.com`
- Password: `password123`

### Step 4: Build Frontend Assets

```bash
# Build assets for production
npm run build

# OR for development with hot reload
npm run dev
```

### Step 5: Start the Application

```bash
# Start Laravel development server
php artisan serve
```

The application will be available at `http://localhost:8000`

## Project Structure

### Database Migrations

1. **users table** - Stores user authentication data
2. **customers table** - Stores customer information
   - id, name, email, phone, address, status, timestamps
3. **otp_logs table** - Stores OTP verification data
   - id, user_id, otp_code, expires_at, status, timestamps

### Controllers

1. **AuthController** - Handles authentication and OTP verification
   - Login
   - OTP generation and verification
   - Logout

2. **CustomerController** - Manages customer CRUD operations
   - List with search and filter
   - Create new customer
   - Edit customer
   - Delete customer

3. **ProfileController** - Manages user profile
   - View profile
   - Edit profile
   - Change password

4. **DashboardController** - Dashboard with statistics

### Views Structure

```
resources/views/
├── layouts/
│   └── app.blade.php           # Main layout with luxury theme
├── auth/
│   ├── login.blade.php         # Login page
│   └── otp.blade.php           # OTP verification page
├── customers/
│   ├── index.blade.php         # Customer list
│   ├── create.blade.php        # Add customer form
│   └── edit.blade.php          # Edit customer form
├── profile/
│   ├── show.blade.php          # View profile
│   ├── edit.blade.php          # Edit profile form
│   └── change-password.blade.php # Change password form
└── dashboard.blade.php         # Dashboard page
```

## Features

### Authentication System

1. **Login Page**
   - Email and password validation
   - Redirects to OTP verification

2. **OTP Verification**
   - 6-digit OTP code
   - 5-minute expiry
   - Countdown timer
   - Resend OTP functionality
   - OTP logged in database

3. **Session Management**
   - Secure session handling
   - Automatic logout on session expiry
   - CSRF protection

### Customer Management

1. **Customer List**
   - Paginated table view
   - Search by name, email, or phone
   - Filter by status (active/inactive)
   - Edit and delete actions

2. **Add Customer**
   - Form validation
   - Required fields: name, email, phone, status
   - Optional: address
   - Unique email validation

3. **Edit Customer**
   - Pre-filled form
   - Update validation
   - Email uniqueness check (excluding current record)

4. **Delete Customer**
   - Confirmation modal
   - Soft delete support

### Profile Management

1. **View Profile**
   - Display user information
   - Member since date

2. **Edit Profile**
   - Update name and email
   - Email uniqueness validation

3. **Change Password**
   - Current password verification
   - New password strength validation (min 8 characters)
   - Password confirmation match
   - Real-time password match indicator
   - Bcrypt hashing

## Design System

### Color Palette

- **Primary Gold:** #d4af37
- **Background Dark:** #1a1a1a
- **Card Background:** rgba(42, 42, 42, 0.85)
- **Text Primary:** #ffffff
- **Success:** #9fff9f
- **Error:** #ff9f9f

### Typography

- **Font Family:** Segoe UI
- **Headings:** Bold, Gold accent color
- **Body:** Regular, White color

### Video Backgrounds

The application uses three layered video backgrounds:
1. Luxury jewellery showcase (opacity: 0.25)
2. Gold texture/sparkle animation (opacity: 0.20)
3. Secure vault visual (opacity: 0.15)

### UI Components

1. **Buttons**
   - Gold gradient button (primary actions)
   - Outline gold button (secondary actions)
   - Hover animations and shadows

2. **Input Fields**
   - Dark background with gold borders
   - Focus glow effect
   - Validation error messages

3. **Cards**
   - Semi-transparent dark background
   - Backdrop blur effect
   - Gold border accents
   - Shadow effects

4. **Tables**
   - Gold header with dark background
   - Hover row effects
   - Status badges (active/inactive)

5. **Alerts**
   - Success alerts (green)
   - Error alerts (red)
   - Left border accent

## Security Features

1. **Password Security**
   - Bcrypt hashing (12 rounds)
   - Minimum 8 characters
   - Password confirmation

2. **OTP Security**
   - Single-use OTP
   - 5-minute expiry
   - Time-based verification
   - Status tracking (pending/verified/expired)

3. **Session Security**
   - CSRF protection
   - Session timeout
   - Secure logout

4. **Input Validation**
   - Server-side validation
   - XSS protection
   - SQL injection prevention
   - Email format validation
   - Phone number validation

## API Endpoints

### Authentication
- `GET /login` - Show login page
- `POST /login` - Process login
- `GET /otp` - Show OTP verification page
- `POST /otp/verify` - Verify OTP
- `POST /otp/resend` - Resend OTP
- `POST /logout` - Logout user

### Dashboard
- `GET /dashboard` - Dashboard with statistics

### Customers
- `GET /customers` - List all customers
- `GET /customers/create` - Show create form
- `POST /customers` - Store new customer
- `GET /customers/{id}/edit` - Show edit form
- `PUT /customers/{id}` - Update customer
- `DELETE /customers/{id}` - Delete customer

### Profile
- `GET /profile` - View profile
- `GET /profile/edit` - Show edit form
- `PUT /profile` - Update profile
- `GET /profile/change-password` - Show change password form
- `POST /profile/change-password` - Update password

## Testing

### Login Testing

1. Navigate to `http://localhost:8000`
2. Use credentials: `admin@sjm.com` / `password123`
3. Check console logs for OTP code
4. Enter OTP on verification page
5. Successfully logged in to dashboard

### OTP Testing

- OTP codes are logged to Laravel logs (`storage/logs/laravel.log`)
- Check the log file to see the generated OTP
- OTP expires after 5 minutes
- Can resend OTP if expired

## Development Notes

### Customization

1. **Change Theme Colors**
   - Edit the CSS in `resources/views/layouts/app.blade.php`
   - Update primary gold color: `#d4af37`
   - Update background color: `#1a1a1a`

2. **Change Video Backgrounds**
   - Update video sources in `resources/views/layouts/app.blade.php`
   - Adjust opacity values for each layer

3. **Modify OTP Expiry**
   - Edit `AuthController.php`, line with `addMinutes(5)`
   - Change the number to desired minutes

4. **Email Integration**
   - Configure mail settings in `.env`
   - Update `AuthController::generateOtp()` to send email
   - Use Laravel's Mail facade

### Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Build assets: `npm run build`
7. Set proper file permissions
8. Configure web server (Apache/Nginx)

## Support

For any questions or issues, contact:
- **Developer:** K. M. Nethmi Sanjalee
- **Organization:** ALL TECHNOLOGY
- **Email:** kokiladulshan021@gmail.com

## License

This project is proprietary software developed for ALL TECHNOLOGY.

---

**Note:** This application is a complete implementation of the Secure Jewellery Management System as specified in the project requirements. All features have been implemented including authentication with OTP, customer management, profile management, and the luxury dark theme with video backgrounds.
