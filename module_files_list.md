# LaraBids Exact Module-wise Data Flow & File Mapping

Bhai, is list me **routes se lekar view, controller, model, requests, services, aur har ek chhoti-chhoti migration tak** sab strictly jahan use ho rahi hain wahi dal di gai hain. Ek bhi file baaki nahi hai!

---

## 👨‍💼 ADMIN SIDE (Admin Controls & Dashboard)

### 1. Admin - Dashboard & Reports
**Routes**: `routes/web.php` *(Lines: 110, 131, 150)*
**Controllers**: 
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/ReportController.php`
**Views**: 
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/reports.blade.php`
- `resources/views/admin/blank.blade.php`

### 2. Admin - Auction Management
**Routes**: `routes/web.php` *(Lines: 112-119)*
**Controllers**: `app/Http/Controllers/Admin/AuctionController.php`
**Notifications Triggered**: 
- `app/Notifications/AuctionApprovedNotification.php`
- `app/Notifications/AuctionCanceledNotification.php`
**Views**: 
- `resources/views/admin/auctions/index.blade.php`
- `resources/views/admin/auctions/show.blade.php`

### 3. Admin - User & Role Management
**Routes**: `routes/web.php` *(Lines: 121-125)*
**Controllers**: `app/Http/Controllers/Admin/UserController.php`
**Models**: `app/Models/User.php`
**Migrations**: 
- `database/migrations/2026_01_22_101319_create_permission_tables.php` (Spatie Roles/Permissions)
**Views**: 
- `resources/views/admin/users/index.blade.php`
- `resources/views/admin/users/show.blade.php`
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/users/edit.blade.php`

### 4. Admin - Category Management
**Routes**: `routes/web.php` *(Lines: 133-137)*
**Controllers**: `app/Http/Controllers/Admin/CategoryController.php`
**Models**: `app/Models/Category.php`
**Migrations**: 
- `database/migrations/2026_01_23_103442_create_categories_table.php`
- `database/migrations/2026_01_28_062035_add_deleted_at_to_categories_table.php`
- `database/migrations/2026_02_05_150415_add_parent_id_to_categories_table.php`
**Views**: 
- `resources/views/admin/categories/index.blade.php`
- `resources/views/admin/categories/create.blade.php`
- `resources/views/admin/categories/edit.blade.php`

### 5. Admin - KYC Verification (Approve/Reject)
**Routes**: `routes/web.php` *(Lines: 152-157)*
**Controllers**: `app/Http/Controllers/Admin/AdminKycController.php`
**Views**: 
- `resources/views/admin/kyc/index.blade.php`
- `resources/views/admin/kyc/show.blade.php`

### 6. Admin - Contacts (User Inquiries), Payments & Settings
**Routes**: `routes/web.php` *(Lines: 127-128, 139-144, 146-147)*
**Controllers**: 
- `app/Http/Controllers/Admin/ContactController.php`
- `app/Http/Controllers/Admin/PaymentController.php`
- `app/Http/Controllers/Admin/SettingController.php`
**Models**: `app/Models/Contact.php`
**Notifications Triggered**: 
- `app/Notifications/ContactReplyNotification.php`
**Views**: 
- `resources/views/admin/contacts/index.blade.php`
- `resources/views/admin/contacts/show.blade.php`
- `resources/views/admin/payments/index.blade.php`
- `resources/views/admin/settings.blade.php`

---

## 👤 USER SIDE (User Dashboards & Bidding Platform)

### 7. User - Dashboard, Profile & Authentication Sessions
**Routes**: `routes/web.php` *(Lines: 50-53, 65, 77)*, `routes/auth.php`
**Controllers**: 
- `app/Http/Controllers/User/UserDashboardController.php`
- `app/Http/Controllers/User/ProfileController.php`
- `app/Http/Controllers/Website/PublicProfileController.php`
**Models**: `app/Models/User.php`
**Requests**: 
- `app/Http/Requests/ProfileUpdateRequest.php`
- `app/Http/Requests/UploadAvatarRequest.php`
- `app/Http/Requests/ChangePasswordRequest.php`
**Services**: `app/Services/UserService.php`
**Migrations**: 
- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/0001_01_01_000001_create_cache_table.php`
- `database/migrations/0001_01_01_000002_create_jobs_table.php`
- `database/migrations/2026_01_23_061827_add_deleted_at_to_users_table.php`
- `database/migrations/2026_02_04_110642_add_profile_fields_to_users_table.php`
- `database/migrations/2026_02_11_115321_create_personal_access_tokens_table.php`
- `database/migrations/2026_02_24_102623_add_username_to_users_table.php`
- `database/migrations/2026_03_09_125541_add_created_by_to_users_table.php`
- `database/migrations/2026_02_05_105034_add_kicked_out_to_sessions_table.php`
**Views**: 
- `resources/views/website/layouts/dashboard.blade.php`
- `resources/views/website/user/dashboard.blade.php`
- `resources/views/website/user/profile.blade.php`
- `resources/views/website/sellers/show.blade.php`

### 8. User - KYC Submission Form
**Routes**: `routes/web.php` *(Lines: 89-91)*
**Controllers**: `app/Http/Controllers/User/KycController.php`
**Models**: `app/Models/Kyc.php`
**Migrations**: 
- `database/migrations/2026_03_10_142936_create_kycs_table.php`
- `database/migrations/2026_03_17_142209_add_gender_and_signature_to_kycs_table.php`
**Views**: `resources/views/website/user/kyc_form.blade.php`

### 9. User - Notifications System
**Routes**: `routes/web.php` *(Lines: 83-87)*
**Controllers**: `app/Http/Controllers/User/NotificationController.php`
**Migrations**: `database/migrations/2026_02_04_142253_create_notifications_table.php`
**Views**: `resources/views/website/user/notifications.blade.php`

### 10. User - Bidding System, Auto Bidding & Auction Automation
**Routes**: `routes/web.php` *(Lines: 93-94, 100-102)*
**Controllers**: 
- `app/Http/Controllers/User/BidController.php`
- `app/Http/Controllers/User/AuctionRegistrationController.php`
**Requests**: `app/Http/Requests/PlaceBidRequest.php`
**Services**: `app/Services/BidService.php`
**Models**: 
- `app/Models/Bid.php`
- `app/Models/AutoBids.php`
- `app/Models/AuctionRegistration.php`
**Events Raised**: `app/Events/BidPlaced.php`
**Notifications Triggered**: 
- `app/Notifications/OutbidNotification.php`
- `app/Notifications/WinnerNotification.php`
- `app/Notifications/SellerAuctionSoldNotification.php`
- `app/Notifications/AuctionStartingSoonNotification.php`
- `app/Notifications/AuctionExtendedNotification.php`
**Console Commands (Cron Jobs)**: 
- `app/Console/Commands/FinalizeAuctions.php`
- `app/Console/Commands/NotifyUpcomingAuctions.php`
**Migrations**: 
- `database/migrations/2026_02_02_061748_create_bids_table.php`
- `database/migrations/2026_02_23_153905_create_auto_bids_table.php`
- `database/migrations/2026_03_12_142302_create_auction_registrations_table.php`
- `database/migrations/2026_03_17_145413_add_notified_to_registrations.php`
**Views**: 
- `resources/views/website/user/my-bids.blade.php`
- `resources/views/website/user/winning-items.blade.php`

### 11. User - Creating & Controlling Personal Auctions
**Routes**: `routes/web.php` *(Lines: 55-60)*
**Controllers**: `app/Http/Controllers/Website/AuctionController.php`
**Requests**: 
- `app/Http/Requests/StoreAuctionRequest.php`
- `app/Http/Requests/UpdateAuctionRequest.php`
**Services**: `app/Services/AuctionService.php`
**Models**: `app/Models/Auction.php`, `app/Models/AuctionImage.php`
**Migrations**: 
- `database/migrations/2026_01_22_072804_create_auctions_table.php`
- `database/migrations/2026_01_27_050809_add_category_id_to_auctions_table.php`
- `database/migrations/2026_01_28_093413_add_soft_deletes_and_reason_to_auctions_table.php`
- `database/migrations/2026_01_28_094700_update_auction_status_enum.php`
- `database/migrations/2026_01_28_105155_add_specifications_and_document_to_auctions_table.php`
- `database/migrations/2026_01_30_060821_create_auction_images_table.php`
- `database/migrations/2026_02_06_112341_add_min_increment_to_auctions_table.php`
- `database/migrations/2026_02_16_105220_add_search_indexes_to_auctions_table.php`
- `database/migrations/2026_02_25_150921_update_default_min_increment_on_auctions_table.php`
- `database/migrations/2026_02_25_163034_increase_precision_for_price_columns.php`
- `database/migrations/2026_02_25_172024_fix_min_increment_default_on_auctions_table.php`
- `database/migrations/2026_02_27_142334_add_winner_id_to_auctions_table.php`
**Views**: 
- `resources/views/website/auctions/create.blade.php`
- `resources/views/website/auctions/edit.blade.php`
- `resources/views/website/user/my-auctions.blade.php`

### 12. User - Watchlists (Favorites)
**Routes**: `routes/web.php` *(Lines: 72-75)*
**Controllers**: `app/Http/Controllers/User/WatchlistController.php`
**Models**: `app/Models/Watchlist.php`
**Migrations**: `database/migrations/2026_02_03_151522_create_watchlists_table.php`
**Views**: `resources/views/website/user/watchlist.blade.php`

---

## 🌐 PUBLIC WEBSITE & AUTHENTICATION FLOW

### 13. Public - Home, About & Contact Us Form
**Routes**: `routes/web.php` *(Lines: 30-34)*
**Controllers**: `app/Http/Controllers/Website/WebsiteController.php`
**Requests**: `app/Http/Requests/ContactRequest.php`
**Models**: `app/Models/Testimonial.php`, `app/Models/Contact.php`
**Migrations**: 
- `database/migrations/2026_01_30_114225_create_testimonials_table.php`
- `database/migrations/2026_01_30_051646_create_contacts_table.php`
**Views**: 
- `resources/views/website/index.blade.php`
- `resources/views/website/about.blade.php`
- `resources/views/website/contact.blade.php`

### 14. Public - Action Discovery (Browse & Search)
**Routes**: `routes/web.php` *(Lines: 36-39, 98-99)*
**Controllers**: `app/Http/Controllers/Website/AuctionController.php`
**Requests**: `app/Http/Requests/SearchAuctionRequest.php`
**Services**: `app/Services/CategorySpecificationService.php`
**Views**: 
- `resources/views/website/auctions/index.blade.php`
- `resources/views/website/auctions/show.blade.php`

### 15. User Authentication (Login, Google Auth, Register, Password Reset)
**Routes**: `routes/web.php` *(Lines: 160-165)*
**Controllers**: 
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/Auth/SocialController.php`
- `app/Http/Controllers/Auth/PasswordController.php`
- `app/Http/Controllers/Auth/ForgotPasswordController.php`
- `app/Http/Controllers/Auth/NewPasswordController.php`
- `app/Http/Controllers/Auth/OtpController.php`
**Jobs**: `app/Jobs/SendOtpEmailJob.php`
**Config**: `config/services.php` (Google OAuth config)
**Migrations**: `database/migrations/2026_02_24_113107_add_google_id_to_users_table.php`
**Views**: 
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/auth/verify-otp.blade.php`
- `resources/views/emails/login_otp.blade.php`
- `resources/views/components/*` (Blade specific Auth components if any)

---

## 📡 API MODULE (For Mobile Apps or External Systems)
*(Yeh poora alag module flow hai sirf `api.php` ke liye)*
- `app/Http/Controllers/Api/Admin/*`
- `app/Http/Controllers/Api/User/*`
- `app/Http/Controllers/Api/Website/*`
