# Admin API Postman Guide 🚀

Ye guide tujhe batayegi ki **Postman** mein kaunsi API kaise use karni hai aur usse kya hoga.

> **Zaroori Note:** Har request ke **Headers** tab mein ye zaroor daalna:
> - **Authorization**: `Bearer YOUR_ACCESS_TOKEN` (Login ke baad jo mile)
> - **Accept**: `application/json`

---

## 1. 👤 User Management (Users)

| Method | URL | Body (JSON) | Kya Hoga? (Outcome) |
| :--- | :--- | :--- | :--- |
| **GET** | `.../api/admin/_users` | - | Saare users ki list aayegi (10 per page). |
| **GET** | `.../api/admin/_users?search=Rahul` | - | Naam, Email ya ID se user dhundhne ke liye. |
| **GET** | `.../api/admin/_users?role=admin` | - | Sirf Admins ya sirf Users dekhne ke liye. |
| **GET** | `.../api/admin/_users?status=trashed` | - | Delete kiye huye users dekhne ke liye. |
| **POST** | `.../api/admin/_users` | `{ "name": "Name", "email": "e@mail.com", "password": "pass", "password_confirmation": "pass", "role": "admin" }` | Naya User ya Admin create hoga. |
| **GET** | `.../api/admin/_users/{id}` | - | Kisi ek user ki puri details dikhengi. |
| **PUT** | `.../api/admin/_users/{id}` | `{ "name": "New Name", "role": "user" }` | User ka naam ya role change karne ke liye. |
| **DELETE** | `.../api/admin/_users/{id}` | - | User ko delete (trash) karne ke liye. |
| **POST** | `.../api/admin/_users/{id}/restore` | - | Delete huye user ko wapis laane ke liye. |
| **DELETE** | `.../api/admin/_users/{id}/force-delete` | - | User ko hamesha ke liye mitane ke liye. |

---

## 2. 🔨 Auction Management (Auctions)

| Method | URL | Body (JSON) | Kya Hoga? (Outcome) |
| :--- | :--- | :--- | :--- |
| **GET** | `.../api/admin/_auctions` | - | Saare auctions ki list aayegi. |
| **GET** | `.../api/admin/_auctions?search=Phone` | - | Title/Category/ID se auction search hoga. |
| **POST** | `.../api/admin/_auctions` | `{ "title": "...", "starting_price": 100, ... }` | Naya auction create hoga. |
| **PUT** | `.../api/admin/_auctions/{id}` | `{ "title": "New Title" }` | Auction edit karne ke liye. |
| **POST** | `.../api/admin/_auctions/{id}/approve` | - | **PENDING** auction ko **ACTIVE** karne ke liye. |
| **POST** | `.../api/admin/_auctions/{id}/cancel` | `{ "reason": "Illegal item" }` | **ACTIVE** auction ko cancel karne ke liye. |
| **DELETE** | `.../api/admin/_auctions/{id}` | - | Auction delete karne ke liye. |

---

## 3. 📂 Category Management (Categories)

| Method | URL | Body (JSON) | Kya Hoga? (Outcome) |
| :--- | :--- | :--- | :--- |
| **GET** | `.../api/admin/_categories` | - | Saari categories aur sub-categories dikhengi. |
| **POST** | `.../api/admin/_categories` | `{ "name": "Mobiles", "icon": "fa-mobile" }` | Nayi category banegi. |
| **PUT** | `.../api/admin/_categories/{id}` | `{ "name": "Smartphones" }` | Category ka naam update hoga. |
| **DELETE** | `.../api/admin/_categories/{id}` | - | Category delete hogi. |

---

## 4. 📩 Contact Management (Contacts/Enquiries)

| Method | URL | Body (JSON) | Kya Hoga? (Outcome) |
| :--- | :--- | :--- | :--- |
| **GET** | `.../api/admin/_contacts` | - | Saare messages dikhenge. |
| **GET** | `.../api/admin/_contacts?status=unread` | - | Sirf **Unread** messages dikhenge. |
| **GET** | `.../api/admin/_contacts?search=Help` | - | Subject/Message/Email se search hoga. |
| **GET** | `.../api/admin/_contacts/{id}` | - | Message open hoga (aur automatically **Read** mark ho jayega). |
| **PUT** | `.../api/admin/_contacts/{id}` | `{ "status": "replied", "admin_notes": "Replied via email" }` | Status update karke **Replied** karne ke liye. |
| **DELETE** | `.../api/admin/_contacts/{id}` | - | Message trash mein jayega. |

---
