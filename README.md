# Eventix — Event Venue Booking System

Eventix is a full-featured, modern Event Venue Booking & Management System built with PHP, MySQL, and vanilla JavaScript. It provides a seamless experience for customers to discover and book event venues, for venue managers to manage their spaces and earnings, and for administrators to oversee the entire platform.

---

## Features

### Public Pages
- **Homepage** — Browse all active venues with search, category filters, star ratings, and pricing. Popular venues and best-rated picks are highlighted.
- **Venue Detail** — View full venue descriptions, image galleries, capacity info, reviews, and pricing before booking.
- **About Us** — Platform background, mission/vision, live stats, how it works, and contact information.
- **Guest Access** — "Continue as Guest" option on login/register pages to browse without an account.
- **Venue Slideshow** — Login and register pages feature an auto-cycling carousel of real venue images pulled from the database.

### Customer Portal
- **Venue Browsing** — Search, filter, and explore all available venues with real photos and reviews.
- **Multi-Day Booking** — Book venues across date ranges with dynamic price calculations, guest count, notes, and optional add-ons (audiovisual gear, lighting, furniture, catering, event planning).
- **Payment with Proof Upload** — Select a payment method (Online Banking, Credit Card, Debit Card, eWallet) and upload a screenshot/receipt as payment proof.
- **Booking Status Tracking** — Track bookings through the full lifecycle: Pending → Paid → Manager Confirmed.
- **My Bookings** — View all bookings with status indicators and payment details.
- **My Reviews** — Leave, edit, and delete venue reviews with star ratings.

### Manager Portal
- **Dashboard** — Overview of total venues, bookings, revenue, and recent activity.
- **Venue Management** — Add new venues with thumbnail + gallery images, edit descriptions, pricing, capacity, and status. Toggle venues active/inactive.
- **Booking Management** — View all customer bookings, review uploaded payment proof images, and confirm/cancel bookings.
- **Earnings Report** — Track total revenue, view earnings breakdown, and generate printable reports.

### Admin Portal
- **Dashboard** — System-wide statistics: total users, venues, bookings, and revenue at a glance.
- **User Management** — View, search, and delete users across all roles (admin, manager, customer).
- **Venue Oversight** — View all venues across all managers.
- **Booking Management** — View and manage all bookings system-wide with payment proof verification.
- **Payment Records** — Full payment history with status tracking.
- **Reports** — Generate and print reports for earnings and user lists.

### Cross-Cutting Features
- **Role-Based Access Control** — Three distinct roles (admin, manager, customer) with separate dashboards and permissions.
- **Multi-Language Support** — Built-in language switcher (English, Malay, Chinese) with translation system.
- **Dark Mode** — Theme toggle with persistent preference across sessions.
- **Client-Side Validation** — JavaScript form validation for email format, password length, phone numbers, and required fields.
- **Confirmation Dialogs** — "Are you sure?" prompts on destructive actions (delete, cancel).
- **Empty States** — Friendly messages when tables/lists are empty instead of blank pages.
- **Responsive Design** — Mobile-friendly layouts using CSS Grid and Flexbox.
- **Smooth Animations** — AOS (Animate On Scroll) library for page transitions and micro-interactions.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5, Vanilla CSS (Flexbox, Grid, Glassmorphism, CSS Variables), Vanilla JavaScript (Fetch API) |
| **Backend** | PHP 8.x (Session-based Auth, prepared statements, file uploads) |
| **Database** | MySQL / MariaDB |
| **CSS Framework** | TailwindCSS (CDN) |
| **Animations** | AOS (Animate On Scroll) |
| **Fonts** | Google Fonts (Playfair Display, Inter) |
| **Server** | Apache (XAMPP) with `.htaccess` URL rewriting |

---

## Project Structure

```
eventix/
├── public/              # Public-facing pages (index, login, register, about, venue detail, profile)
├── customer/            # Customer portal (venues, book, my_bookings, my_reviews, payment)
├── manager/             # Manager portal (dashboard, venues, edit_venue, bookings, earnings)
├── admin/               # Admin portal (dashboard, users, venues, bookings, payments, reports)
├── includes/            # Shared PHP includes
│   ├── auth.php         # Authentication & session management
│   ├── db.php           # Database connection
│   ├── lang.php         # Multi-language translation system
│   ├── navbar.php       # Role-aware navigation bar
│   ├── footer.php       # Site footer with links
│   ├── validation.php   # Server-side input validation
│   ├── icons.php        # SVG icon helper functions
│   └── header_scripts.php  # CSS/JS asset includes & design tokens
├── css/                 # Stylesheets
├── js/                  # JavaScript (navbar, auth, theme toggle)
├── images/              # Static images & logos
├── uploads/             # User-uploaded files
│   ├── profiles/        # Profile pictures
│   ├── venues/          # Venue thumbnails & gallery images
│   └── payments/        # Payment proof screenshots
├── tests/               # Test files
├── database.sql         # Full database schema + seed data
├── seed_users.php       # Script to seed 40 demo customers with bookings & reviews
├── .htaccess            # Apache URL rewriting rules
└── README.md            # This file
```

---

## Installation

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.x)
- Web browser

### Setup Steps

1. **Clone or copy** the project into your XAMPP `htdocs` directory:
   ```
   C:\xampp\htdocs\eventix\
   ```

2. **Start Apache and MySQL** from the XAMPP Control Panel.

3. **Create the database**:
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Create a new database named `eventix_db`
   - Import `database.sql` to set up the schema and default data

4. **Ensure upload directories exist** and are writable:
   ```
   uploads/profiles/
   uploads/venues/
   uploads/payments/
   ```

5. **(Optional) Seed demo data** — Run the seed script to populate 40 customers with bookings and reviews:
   ```
   php seed_users.php
   ```

6. **Access the application** at:
   ```
   http://localhost/eventix/
   ```

---

## Default Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@eventix.com` | `admin123` |
| **Manager** | `ridhuan@gmail.com` | (set during registration) |
| **Manager** | `anas@gmail.com` | (set during registration) |
| **Demo Customers** | `james@gmail.com`, `sarah@gmail.com`, etc. | `password` |

> All 40 seeded demo customers use the password: **`password`**

---

## Database Schema

The system uses 8 core tables:

| Table | Purpose |
|-------|---------|
| `users` | All user accounts (admin, manager, customer) with profile info |
| `venues` | Event venue listings managed by managers |
| `venue_images` | Thumbnail and gallery images for each venue |
| `bookings` | Customer booking records with dates, guest count, and status |
| `booking_addons` | Add-on services selected for each booking |
| `addons` | Available add-on services (AV gear, lighting, furniture, etc.) |
| `payments` | Payment records with method, amount, status, and proof uploads |
| `ratings` | Customer reviews and star ratings for venues |

An ERD diagram (`erd.xml`) is included and can be imported into [draw.io](https://app.diagrams.net/) for visualization.

---

## Booking Flow

```
Customer browses venues
        ↓
Customer selects dates, guests, add-ons
        ↓
Booking created (status: pending)
        ↓
Customer selects payment method + uploads payment proof
        ↓
Payment recorded (status: paid)
        ↓
Manager views booking + verifies payment proof
        ↓
Manager confirms booking (status: confirmed)
```

---

## License

This project was built as part of a university coursework assignment.
