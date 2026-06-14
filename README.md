# Eventix (Alpha)

Eventix is a modern, geometric, Airbnb-inspired Event Management System built with PHP and MySQL. It allows users to browse and book event venues, and allows venue managers and administrators to oversee operations through custom dashboards. Eventix eliminates the old, traditional way of booking a venue through WhatsApp, phone calls, or manual paperwork, replacing it with a centralized, organized, and efficient online platform.

## Features

- **Public e-Commerce Flow**: Unauthenticated users can browse all venues with a beautiful UI.
- **Frictionless Authentication**: AJAX-powered glassmorphism popup modals for quick login without losing your place in the booking funnel.
- **Multi-Day Booking Engine**: Book venues across date ranges with dynamic JavaScript price calculations.
- **Role-Based Portals**:
  - **Customer Portal**: Track bookings, leave reviews, and pay for reservations.
  - **Manager Portal**: Add/edit venues, upload image galleries, and track earnings.
  - **Admin Portal**: Oversee all users, all venues, system-wide bookings, and payments.

## Installation

1. Clone this repository into your local web server (e.g., `htdocs` for XAMPP).
2. Create a database named `eventix_db` in MySQL.
3. Import the `database.sql` file to set up the schema and default data.
4. Ensure the `uploads/venues/` and `uploads/profiles/` directories exist and are writable.

## Default Credentials

- **Admin Account**: `admin@eventix.com` / `admin123`

## Tech Stack
- Frontend: HTML5, Vanilla CSS (Flexbox, Glassmorphism, CSS Grid), Vanilla JavaScript (Fetch API).
- Backend: PHP (Session-based Auth, custom lightweight MVC routing).
- Database: MySQL.
