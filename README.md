# 🎪 FestLoop — Festival & College Event Management Platform

FestLoop is a full-stack web application designed for universities and colleges to seamlessly manage campus festivals, technical hackathons, cultural fests, workshops, and sports competitions.

Built with **PHP 8.2**, **Laravel 11**, **Laravel Breeze (Blade components)**, **Tailwind CSS**, and **Vite**.

---

## ✨ Key Features

### 🌐 1. Public Discovery Portal
- **Festival Showcase**: Hero landing page with featured upcoming events across campus.
- **Event Catalog**: Filter by categories (`Cultural`, `Technical`, `Sports`, `Workshop`, `Seminar`, `Other`) and real-time debounce search.
- **Event Schedule & Details**: Markdown-rendered event descriptions, venue, registration deadlines, and live capacity tracking.
- **Instant Enrollment**: One-click registration for logged-in attendees.

### 🎟️ 2. Attendee Dashboard
- **My Registrations**: Separate tabs for *Upcoming* and *Past* events with live status badges.
- **Digital Event Pass**: Dedicated, printable event passes complete with reference IDs and print CSS styles.
- **Registration Management**: Option to cancel registrations and automatically release reserved spots.

### 🎪 3. Event Coordinator Portal
- **Dashboard Overview**: Metrics on total events, participant registrations, and upcoming events.
- **Full Event CRUD**: Create, edit, and cancel events, with banner image uploads.
- **Attendee Roster**: Real-time view of enrolled participants with their statuses.
- **CSV Data Export**: Streamed CSV downloads of attendee lists for offline event entry management.

### 🛡️ 4. Administrator Control Center
- **Campus-Wide KPI Metrics**: Total users (attendees, coordinators, admins), events (published, draft, cancelled), and registrations.
- **User & Role Governance**: Searchable user roster with instant inline role promotions/demotions (`attendee` ⇄ `coordinator` ⇄ `admin`).
- **Global Event Moderation**: Oversee, update status, or delete any campus event.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS 3.x, Alpine.js
- **Asset Bundler**: Vite
- **Database**: SQLite (default for development), easily switchable to MySQL or PostgreSQL
- **Testing**: PHPUnit / Pest with RefreshDatabase (59 feature & unit tests)

---

## 🚀 Quick Start Guide

### Prerequisites
- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/arjunvkaimal/FestLoop.git
   cd FestLoop
   ```

2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure the environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Initialize database & storage symlink:**
   ```bash
   touch database/database.sqlite
   php artisan storage:link
   php artisan migrate:fresh --seed
   ```

5. **Compile frontend assets:**
   ```bash
   npm run build
   ```

6. **Start the local development server:**
   ```bash
   php artisan serve
   ```
   Open your browser at `http://localhost:8000`.

---

## 🔑 Demo & Test Credentials

All pre-seeded test accounts use the password: **`password`**

| Role | Email | Access |
|---|---|---|
| **Admin** | `admin@festloop.com` | Full platform governance (`/admin/dashboard`) |
| **Coordinator** | `coordinator1@festloop.com` | Event coordinator dashboard (`/coordinator/dashboard`) |
| **Coordinator** | `coordinator2@festloop.com` | Event coordinator dashboard (`/coordinator/dashboard`) |
| **Coordinator** | `coordinator3@festloop.com` | Event coordinator dashboard (`/coordinator/dashboard`) |
| **Attendee** | `attendee1@festloop.com` | Attendee tickets & passes (`/dashboard`) |

---

## 🧪 Running Automated Tests

Run the complete test suite containing 59 feature and unit tests (140 assertions):

```bash
php artisan test
```

---

## 📄 License
Open-source software licensed under the [MIT license](LICENSE).
