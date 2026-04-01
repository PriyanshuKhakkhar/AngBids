# 🚀 AngBids

Frontend migration of the LaraBids auction platform into a modern Angular application, powered by a Laravel API backend.

---

## 🧩 Tech Stack

* **Frontend:** Angular
* **Backend:** Laravel (API-based)
* **Styling:** Custom theme (template-based, partially migrated)
* **State Management:** Angular Signals + Services
* **Authentication:** Token-based (JWT via Laravel)

---

## 📁 Project Structure

```
AngBids/
│
├── src/                    # Angular frontend source code
├── public/                 # Static assets (CSS, images, theme files)
├── larabids/               # Laravel backend (API)
│
├── larabids-template/      # UI reference (Blade templates)
├── pixner.net/             # External template reference
├── startbootstrap...       # Admin template reference
│
├── angular.json
├── package.json
└── tsconfig.json
```

---

## ⚙️ Features

* 🔐 User Authentication (Login / Register / OTP)
* 🏷️ Browse Auctions
* 📦 Auction Details
* 🧑‍💼 User Dashboard (in progress)
* 📡 API Integration with Laravel backend
* 💾 Persistent login using localStorage

---

## 🚀 Getting Started

### 1. Clone the Repository

```
git clone https://github.com/PriyanshuKhakkhar/AngBids.git
cd AngBids
```

---

### 2. Run Angular Frontend

```
npm install
ng serve
```

Open in browser:
👉 http://localhost:4200

---

### 3. Run Laravel Backend

```
cd larabids
php artisan serve
```

API runs on:
👉 http://127.0.0.1:8000

---

## 🔗 API Configuration

Make sure your Angular environment file is configured correctly:

```
apiUrl: 'http://127.0.0.1:8000/api'
```

---

## ⚠️ Notes

* This project is currently in **migration phase** from Laravel Blade templates to Angular.
* Some UI elements still rely on template assets located in `public/`.
* Template folders (`larabids-template`, `pixner.net`, etc.) are used only for reference.

---

## 📌 Future Improvements

* Full migration of UI into Angular components
* Clean removal of unused template folders
* Improved state management
* Better error handling and UX
* Deployment setup (frontend + backend)

---

## 👨‍💻 Author

**Priyanshu Khakkhar**

GitHub:
👉 https://github.com/PriyanshuKhakkhar

---

## ⭐ Support

If you like this project, give it a ⭐ on GitHub!
