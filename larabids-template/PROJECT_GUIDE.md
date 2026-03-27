# LaraBids Project Documentation: The Ultimate Guide 🚀

Welcome to the **LaraBids** project guide! This document is designed to give you a deep, feature-by-feature understanding of how your application is built, how it works, and how to maintain it.

---

## 1. Project Overview 🌟
**LaraBids** is a professional-grade online auction platform where users can post items for auction and manage their bidding activities. It features a robust administration system to ensure platform integrity and a sleek, premium design.

### Core Philosophy
- **Clean Code**: Business logic is separated into Services.
- **Premium UI**: Black, Gold, and White theme using Bootstrap 5.
- **Security**: Role-based access control (RBAC).

---

## 2. Technical Stack 🛠️
Your project is built with modern, industry-standard technologies:
- **Framework**: [Laravel 11](https://laravel.com/) (PHP)
- **Database**: MySQL/PostgreSQL (structured via Eloquent ORM)
- **Frontend**: Blade Templating, **Bootstrap 5**, FontAwesome 6, AOS (Animate on Scroll).
- **Security**: [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission) for Roles & Permissions.
- **Assets**: Vite (Asset Bundling).

---

## 3. Architecture & Design Patterns 🏗️
To keep the project maintainable, we use the following patterns:

### A. MVC (Model-View-Controller)
- **Models**: Standard data structures (`User`, `Auction`, `Category`).
- **Views**: Blade templates organized by feature (`admin/`, `website/`).
- **Controllers**: Thin controllers that delegate logic to Services.

### B. Service Layer (The "Brain")
Logic for complex operations is kept in `app/Services/AuctionService.php`. 
- **Why?** This prevents "Fat Controllers" and makes code reusable (e.g., both Admin and Website can use the same logic to update an auction).

### C. Form Requests (The "Gatekeeper")
Validation is handled in `app/Http/Requests`.
- Example: `StoreAuctionRequest.php` ensures that when a user creates an auction, all dates, prices, and images are valid before the code even runs.

---

## 4. Feature-Wise Breakdown 🧩

### 🚪 Authentication & Roles
- **System**: Laravel Breeze / Custom logic with Spatie.
- **Roles**:
    - **Super Admin/Admin**: Access to the `/admin` dashboard. Can manage users, categories, and auctions.
    - **User**: Can browse auctions, create their own auctions, and bid.
- **Files**: `User.php` (Model), `Auth` directory (Controllers).

### 🏷️ Category Management
- **Description**: Auctions are grouped by categories (e.g., Electronics, Vehicles, Art).
- **Features**:
    - Admin can create, edit, and soft-delete categories.
    - Toggle status (Enable/Disable).
- **Files**: `Category.php`, `Admin/CategoryController.php`.

### ⚖️ Auction System
This is the heart of the project.
- **Workflow**:
    1. User submits an auction (Title, Description, Price, Images, Documents).
    2. Status is set to `pending`.
    3. Admin reviews and **Approves** (status becomes `active`) or **Cancels** with a reason.
- **Cool Features**:
    - **Multiple Images**: Each auction can have a gallery of images.
    - **Time Snapping**: If a user selects a start time in the past, the system automatically sets it to "Now."
    - **Soft Deletes**: Deleting an auction doesn't remove it from the DB; it moves it to "Trash" for restoration later.
- **Files**: `Auction.php`, `AuctionService.php`, `StoreAuctionRequest.php`.

### 🔍 Search & Filtering
- **Features**:
    - **Search**: Keyword search across titles, descriptions, and categories.
    - **Filters**: Filter by Category, Price Range (Min/Max).
    - **Sorting**: Sort by "Newly Listed," "Price: Low to High," "Price: High to Low," or "Ending Soon."
- **Files**: `WebsiteController.php`, `AuctionService.php` (`getFilteredAuctions`).

### 📊 Dashboards
- **User Dashboard**: Overview of "My Bids," "Winning Items," and "Wishlist." (Currently in placeholder state).
- **Admin Dashboard**: Stat-heavy overview of platform health (Users, Auctions, REvenue).

---

## 5. Database Schema 🗄️
Key tables and their relationships:
- **`users`**: Stores user data and roles.
- **`categories`**: Auction categories.
- **`auctions`**: Main auction data. Related to `users` and `categories`.
- **`auction_images`**: Stores paths for multiple images per auction.
- **`model_has_roles`**: Links users to their roles (Spatie).

---

## 6. Current Progress (What's Done vs. Next) 📈
| Feature | Status |
| :--- | :--- |
| Core Auction CRUD | ✅ Complete |
| Admin Management (Users/Categories) | ✅ Complete |
| Multiple Image Uploads | ✅ Complete |
| AJAX/Quick Search | ✅ Complete |
| Bidding Logic | ⏳ Planned (Placeholder exists) |
| Wishlist System | ⏳ Planned (Placeholder exists) |
| Payment Integration | ⏳ Planned |

---

## 7. Developer's Cheat Sheet 💡
Commonly used commands for this project:

- **Run the project**: `php artisan serve`
- **Compile styles**: `npm run dev`
- **Refresh Database**: `php artisan migrate:fresh --seed` (⚠️ Deletes all data!)
- **Create a new Service**: Manual creation in `app/Services`.
- **Apply Roles**: `$user->assignRole('admin');`

---

## 8. Theme & Styling Guide 🎨
- **Colors**:
    - Primary: `#D4AF37` (Gold/Metallic)
    - Dark: `#121212` (Deep Charcoal)
    - Accent: Gold/White gradients.
- **Classes**: Use `card-elite` for premium cards and `btn-gold` for primary actions.

---

*This guide was generated by Antigravity to help you master the LaraBids codebase.*
