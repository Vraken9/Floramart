# FloraMart Architecture Context

## 1. Database Schema

### Table: `users`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | id (PK) | Auto-increment |
| `name` | string | |
| `email` | string | Unique |
| `email_verified_at` | timestamp | Nullable |
| `password` | string | |
| `role` | enum | `['admin', 'owner', 'user']`, Default: `'user'` |
| `remember_token` | string | |
| `created_at`, `updated_at` | timestamps | |

### Table: `categories`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | id (PK) | Auto-increment |
| `name` | string | Unique |
| `slug` | string | Unique |
| `created_at`, `updated_at` | timestamps | |

### Table: `shops`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | id (PK) | Auto-increment |
| `user_id` | foreignId | Foreign Key `users(id)`, Cascade on delete |
| `district_id` | foreignId | Foreign Key `districts(id)` |
| `name` | string | |
| `description` | text | Nullable |
| `reason` | text | |
| `address_detail` | string | |
| `whatsapp_number` | string | |
| `status` | enum | `['pending', 'approved', 'suspended']`, Default: `'pending'` |
| `logo_path` | string | Nullable |
| `created_at`, `updated_at` | timestamps | |

### Table: `products`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | id (PK) | Auto-increment |
| `shop_id` | foreignId | Foreign Key `shops(id)`, Cascade on delete |
| `category_id` | foreignId | Foreign Key `categories(id)`, Restrict on delete |
| `name` | string | |
| `slug` | string | Unique |
| `description` | text | |
| `price` | integer | |
| `image_path` | string | Nullable |
| `is_active` | boolean | Default: `true` |
| `created_at`, `updated_at` | timestamps | |

### Table: `product_leads`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `id` | id (PK) | Auto-increment |
| `product_id` | foreignId | Foreign Key `products(id)`, Cascade on delete |
| `shop_id` | foreignId | Foreign Key `shops(id)`, Cascade on delete |
| `user_id` | foreignId | Foreign Key `users(id)`, Nullable, Null on delete |
| `clicked_at` | timestamp | Default: `useCurrent()` |

### Other Core Tables
- `password_reset_tokens` (email PK, token, created_at)
- `sessions` (id PK, user_id, ip_address, user_agent, payload, last_activity)
- `provinces`, `regencies`, `districts`, `shop_schedules` (Standard relational schemas forming a 3-tier geographic hierarchy: Province -> Regency -> District)

---

## 2. Eloquent Models

### User
- **Assignment**: `$fillable = ['name', 'email', 'password']`
- **Relationships**: 
  - `favoriteProducts()`: belongsToMany `Product` (Pivot: `wishlists`)
  - `shop()`: hasOne `Shop`

### Shop
- **Assignment**: `$guarded = ['id']`
- **Relationships**:
  - `user()`: belongsTo `User`
  - `district()`: belongsTo `District`
  - `schedules()`: hasMany `ShopSchedule`
  - `products()`: hasMany `Product`

### Product
- **Assignment**: `$guarded = ['id']`
- **Relationships**:
  - `shop()`: belongsTo `Shop`
  - `category()`: belongsTo `Category`
  - `leads()`: hasMany `ProductLead`
  - `favoritedBy()`: belongsToMany `User` (Pivot: `wishlists`)

### Category
- **Assignment**: `$guarded = ['id']`
- **Relationships**:
  - `products()`: hasMany `Product`

### ProductLead
- **Assignment**: `$guarded = ['id']`
- **Relationships**:
  - `shop()`: belongsTo `Shop`
  - `category()`: belongsTo `Category`
  - `leads()`: hasMany `ProductLead`

### Regions Hierarchy
- **Province**: `regencies()` hasMany `Regency`
- **Regency**: `province()` belongsTo `Province`, `districts()` hasMany `District`
- **District**: `regency()` belongsTo `Regency`

---

## 3. Routing Architecture

| Method | URI | Action / Controller | Middleware | Route Name |
| :--- | :--- | :--- | :--- | :--- |
| GET | `/` | `HomeController@index` | - | `home` |
| GET | `/katalog` | `HomeController@katalog` | - | `katalog.index` |
| GET | `/toko-florist` | `HomeController@allShops` | - | `shops.index` |
| GET | `/dashboard` | `DashboardController@index` | `auth, verified` | `dashboard` |
| GET | `/wishlist` | `WishlistController@index` | `auth, verified` | `wishlist.index` |
| POST | `/wishlist/{product}`| `WishlistController@toggle`| `auth` | `wishlist.toggle` |
| GET | `/product/{slug}` | `HomeController@show` | - | `product.show` |
| GET | `/shop/{id}` | `ShopController@show` | - | `shop.show` |
| GET | `/bunga/{id}/wa-redirect`| `LeadController@redirectWhatsApp` | - | `product.whatsapp` |
| GET | `/profile` | `ProfileController@edit` | `auth` | `profile.edit` |
| PATCH | `/profile` | `ProfileController@update` | `auth` | `profile.update` |
| DELETE| `/profile` | `ProfileController@destroy`| `auth` | `profile.destroy` |
| GET | `/buka-toko` | `ShopController@create` | `auth` | `shop.create` |
| POST | `/buka-toko` | `ShopController@store` | `auth` | `shop.store` |
| PATCH | `/admin/shops/{id}/approve` | `AdminController@approveShop` | `auth, role:admin` | `admin.shops.approve` |
| GET | `/owner/products` | `ProductController@index` | `auth, role:owner` | `owner.products.index` |
| GET | `/owner/products/create` | `ProductController@create`| `auth, role:owner` | `owner.products.create` |
| POST | `/owner/products` | `ProductController@store` | `auth, role:owner` | `owner.products.store` |
| GET | `/owner/products/{id}/edit` | `ProductController@edit` | `auth, role:owner` | `owner.products.edit` |
| PUT | `/owner/products/{id}`| `ProductController@update` | `auth, role:owner` | `owner.products.update` |
| DELETE| `/owner/products/{id}`| `ProductController@destroy`| `auth, role:owner` | `owner.products.destroy` |
| PATCH | `/owner/products/{id}/toggle`|`ProductController@toggleStatus`| `auth, role:owner` | `owner.products.toggle` |

*(In addition, uses standard auth routes via `require auth.php`)*

---

## 4. Controller Logic

### `HomeController`
- `index()`: Returns `$groupedProducts` (4 active products per category with an approved shop, along with shop district & regency data), `$categories`, and `$districts` (with regency and province relationships) to `welcome` view.
- `katalog()`: Handles search/filter logic. Employs a 2-tier location filtering logic (`regency` and `district`). If searching/filtering, returns flat `$products`; otherwise, returns default grouped `$groupedProducts` (4 per category).
- `show()`: Returns single `$product` (by slug, if active) along with `shop.district.regency` data, and `$categories` to `product.show` view.
- `allShops()`: Lists shops with 'approved' status. Also employs the 2-tier location filter (`regency` and `district`). Returns `$shops`, `$regencies`, and `$categories` to `shop.index`.

### `DashboardController` & `AdminController`
- `index()`: Diverts user based on their role (`admin`, `owner`, or default user). If `admin`, it queries all `pending` shops along with their `user` and `district.regency` relationships, providing `$pendingShops` to the `admin.dashboard` view. If `owner`, returns `owner.dashboard`.
- `approveShop()`: Updates shop status to 'approved' and promotes the associated user role to 'owner'.

### `ProductController` (Owner)
- `index()`: Returns owner's `$products` and `$shop` to `owner.products.index`.
- `create()`: Returns `$categories` and `$shop` to `owner.products.create`.
- `store()`: Validates input and image (URL or local upload) and creates `Product`.
- `edit()`: Returns `$product`, `$categories`, `$shop` to `owner.products.edit`.
- `update()`: Modifies product, handles old image cleanup if replaced.
- `destroy()`: Deletes product and cleans local image.
- `toggleStatus()`: Switches `is_active` boolean for a specific product.

### `ShopController`
- `show()`: Returns `$shop` (with its products & relationships) and `$categories` to `shop.show`.
- `create()` / `store()`: Validates and saves new shop registration data with `status = pending`. Supports newly added `logo_path` functionality for shop logos.

### `LeadController`
- `redirectWhatsApp()`: Inserts a click tracker log (`ProductLead`) and redirects the user to the `https://wa.me/` endpoint with a heavily formatted message linking back to the product.

### `WishlistController`
- `index()`: Serves the 'user wishlist dashboard' with `$favoriteProducts` and `$categories` at the `/wishlist` route.
- `toggle()`: Syncs/Toggles a given product ID to the authenticated user's `wishlists` pivot table.

---

## 5. View Structure & Design System
**Root (`resources/views/`)**
- `welcome.blade.php`
- `dashboard.blade.php`

**Component and Layout Views**
- `layouts/app.blade.php`, `guest.blade.php`, `navigation.blade.php`
- `components/` (Breeze components: `application-logo.blade.php`, `modal.blade.php`, button components, inputs, etc.)

**Entity Domains**
- `admin/dashboard.blade.php` (Incorporates Plum-Beige design system, 2-tier filtering UI, standardized navigation)
- `auth/` (Login, Register, Passwords, etc.)
- `katalog/index.blade.php`, `katalog/detail.blade.php`
- `owner/dashboard.blade.php` (Aligned with Admin Dashboard's Plum-Beige aesthetic)
- `owner/products/index.blade.php`, `create.blade.php`, `edit.blade.php`
- `product/show.blade.php`
- `profile/edit.blade.php`, `partials/...`
- `shop/create.blade.php`, `shop/index.blade.php`
