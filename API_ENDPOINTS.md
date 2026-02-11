# Admin API Documentation

**Base URL**: `http://localhost:8000/api`

---

## 🔐 1. Authentication (Login)

**You must login first to get an Access Token.**

- **URL:** `{{base_url}}/api/login`
- **Method:** `POST`
- **Headers:** `Accept: application/json`
- **Body (JSON):**
  ```json
  {
      "email": "admin@example.com",
      "password": "password"
  }
  ```

**Response (Success):**
```json
{
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        ...
    },
    "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz1234567890"  <-- COPY THIS TOKEN
}
```

---

## 🚀 2. How to Use the Token

For all the Admin API requests below, you must include the token in the **Headers**.

**Header Key:** `Authorization`
**Header Value:** `Bearer YOUR_COPIED_TOKEN`

Example: `Bearer 1|AbCdEfGhIjKlMnOpQrStUvWxYz1234567890`

---

## 📂 3. Category Management API

**Base URL for Categories**: `{{base_url}}/api/admin`

### Get All Categories
- **Method:** `GET`
- **URL:** `{{base_url}}/api/admin/_categories`

### Create Category
- **Method:** `POST`
- **URL:** `{{base_url}}/api/admin/_categories`
- **Body (JSON):**
  ```json
  {
      "name": "Electronics",
      "icon": "fas fa-laptop",
      "parent_id": null
  }
  ```

### Show Single Category
- **Method:** `GET`
- **URL:** `{{base_url}}/api/admin/_categories/{id}`

### Update Category
- **Method:** `PUT`
- **URL:** `{{base_url}}/api/admin/_categories/{id}`
- **Body (JSON):**
  ```json
  {
      "name": "Updated Electronics",
      "icon": "fas fa-plug"
  }
  ```

### Soft Delete Category
- **Method:** `DELETE`
- **URL:** `{{base_url}}/api/admin/_categories/{id}`

### Restore Category
- **Method:** `POST`
- **URL:** `{{base_url}}/api/admin/_categories/{id}/restore`

### Force Delete Category
- **Method:** `DELETE`
- **URL:** `{{base_url}}/api/admin/_categories/{id}/force-delete`
