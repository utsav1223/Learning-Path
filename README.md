<div align="center">

<br/>

<img src="https://img.shields.io/badge/Dynamic-SkillWeave-6366f1?style=for-the-badge&labelColor=0f172a" alt="Dynamic"/>

# Dynamic — Learning Planner & Roadmap Generator

**Assess your skills. Get a personalised AI roadmap. Track your progress.**

Dynamic is a full-featured Laravel application that takes learners through a short technical assessment, recommends personalised technology stacks, and generates AI-powered, week-by-week study roadmaps — all tailored to their goals, pace, and schedule.

<br/>

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-Framework-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Sanctum](https://img.shields.io/badge/Sanctum-API%20Auth-F9322C?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/docs/sanctum)
[![Vite](https://img.shields.io/badge/Vite-Assets-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev)
[![License](https://img.shields.io/badge/License-MIT-22c55e?style=flat-square)](LICENSE)

<br/>

</div>

---

## 📸 Screenshots

<br/>

**Landing Page**

<img src="public/screenshots/home.png" width="100%" alt="SkillWeave landing page hero — Learn Smarter. Not Harder."/>

<br/>

<img src="public/screenshots/home2.png" width="100%" alt="SkillWeave landing page — Learning snapshots section with stats"/>

<br/>

---

**Authentication**

<table>
<tr>
<td width="50%">

<img src="public/screenshots/login.png" width="100%" alt="Login page"/>

</td>
<td width="50%">

<img src="public/screenshots/register.png" width="100%" alt="Register / Create account page"/>

</td>
</tr>
<tr>
<td align="center"><sub>Sign In</sub></td>
<td align="center"><sub>Create Account</sub></td>
</tr>
</table>

<br/>

---

**Learner Dashboard**

<img src="public/screenshots/dashboard.png" width="100%" alt="Learner Command Center dashboard — score, readiness, topic performance, action queue"/>

<br/>

---

**AI Roadmap**

<img src="public/screenshots/roadmap.png" width="100%" alt="AI Learning Roadmap — Full Stack Developer improvement roadmap with plan summary and mentor notes"/>

<br/>

---

**Weekly Plan**

<img src="public/screenshots/weekly-plan.png" width="100%" alt="Detailed study timeline — week-by-week tasks, progress, courses and checkpoints"/>

<br/>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Architecture](#-architecture)
- [Database Schema](#-database-schema)
- [API & Routes](#-api--routes)
- [Models](#-models)
- [Installation](#-installation)
- [Seeding & Tests](#-seeding--tests)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🎯 Overview

Dynamic (branded as **SkillWeave**) solves the static learning problem. Most learning platforms give everyone the same path — Dynamic adapts. It starts with a 25-question assessment to identify your strongest and weakest areas, then an AI provider generates a phased, week-by-week roadmap that prioritises your gaps first and builds momentum through your strengths.

Learners get:

- A scored, insight-rich assessment result
- A recommended technology stack for their chosen goal
- A day-by-day, week-by-week learning plan with deliverables and checkpoints
- A resource library, todo board, and project suggestions
- A dashboard with live topic performance analytics and an action queue

Admins get full user management and a support ticket console.

---

## ✨ Key Features

| Feature | Description |
|---|---|
| 🎯 **Assessment Engine** | 25 adaptive questions across technologies; tracks attempts, answers, score, percentage, and insights |
| 🤖 **AI Roadmap Generation** | Pluggable AI provider (OpenAI / custom) generates a phased `ai_roadmap` JSON per attempt |
| 📊 **Learner Dashboard** | Live readiness score, topic performance chart, study rhythm, and prioritised action queue |
| 🗓️ **Weekly Plan** | Week-by-week breakdown with tasks, time estimates, priority levels, courses, and checkpoints |
| 👤 **Rich Profiles** | Bio, education, skill level, interests, career stage, target role, weekly days, study window |
| 📚 **Course Catalog** | Categorised courses with difficulty (Beginner → Advanced), estimated hours, and thumbnails |
| 🚀 **Onboarding Flow** | Collects goal, proficiency, learning format, and pace before the first assessment |
| 🎫 **Support Tickets** | Users file categorised tickets with priority; admins resolve with notes and timestamps |
| 🛡️ **Admin Console** | Full user management and ticket resolution panel behind the `is_admin` flag |
| 🔐 **Sanctum API Auth** | Token-based authentication for all JSON API endpoints |

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| **Runtime** | PHP 8.1+ |
| **Framework** | Laravel (latest stable) |
| **Database** | MySQL / MariaDB (SQLite supported for local dev) |
| **Auth** | Laravel Sanctum |
| **Templating** | Blade |
| **Assets** | Vite + npm |
| **Queue / Cache** | Redis (optional) |
| **AI Integration** | OpenAI API (or any provider via `roadmap_provider` field) |
| **Storage** | S3 / Local disk |

---

## 🏗 Architecture

```mermaid
flowchart LR
    Browser(["🌐 Browser"]):::client --> WS["NGINX / Apache"]:::infra
    WS --> PHP["PHP-FPM"]:::infra
    PHP --> App["Laravel App\nControllers · Models · Providers"]:::core

    App --> DB[("MySQL / MariaDB")]:::store
    App --> Queue["Redis Queue Worker"]:::store
    App --> Storage["S3 / Local Storage"]:::store
    App --> AI{"OpenAI / AI Provider\nRoadmap Engine"}:::ai

    App --> Sanctum["Sanctum\nAPI Auth"]:::svc
    App --> Mail["Mailer\nSMTP / SES"]:::svc

    classDef client  fill:#1e1b4b,stroke:#6366f1,color:#c7d2fe
    classDef infra   fill:#0f172a,stroke:#334155,color:#94a3b8
    classDef core    fill:#082f49,stroke:#0ea5e9,color:#7dd3fc
    classDef store   fill:#052e16,stroke:#16a34a,color:#86efac
    classDef ai      fill:#2e1065,stroke:#9333ea,color:#d8b4fe
    classDef svc     fill:#2d1b0e,stroke:#d97706,color:#fcd34d
```

**Directory layout:**

```
app/
├── Http/Controllers/     # Request handling — auth, onboarding, assessment, roadmap, admin, tickets
├── Models/               # Eloquent models and relations
├── Providers/            # Service providers
└── Support/              # Helper classes

routes/
├── web.php               # Blade-rendered views
└── api.php               # JSON endpoints (Sanctum-protected)

database/
├── migrations/           # All schema migrations
└── seeders/              # Sample data seeders
```

---

## 🗄 Database Schema

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email
        string goal
        tinyint proficiency
        string learning_format
        string learning_pace
        timestamp onboarded_at
        boolean is_admin
    }
    profiles {
        bigint id PK
        bigint user_id FK
        enum skill_level
        json interests
        string learning_goal
        string target_role
        string career_stage
        tinyint experience_years
        int daily_learning_time
        tinyint weekly_days
        string preferred_study_window
    }
    categories {
        bigint id PK
        string name
        text description
    }
    courses {
        bigint id PK
        bigint category_id FK
        string title
        enum difficulty_level
        int estimated_hours
        string thumbnail
    }
    assessment_questions {
        bigint id PK
        string technology
        string topic
        string difficulty
        text question
        json options
        string correct_answer
        boolean is_active
    }
    assessment_attempts {
        bigint id PK
        bigint user_id FK
        string selected_goal
        json recommended_stack
        json question_ids
        smallint score
        smallint total_questions
        decimal percentage
        json insights
        json ai_roadmap
        string roadmap_provider
        timestamp completed_at
    }
    assessment_answers {
        bigint id PK
        bigint attempt_id FK
        bigint question_id FK
        string selected_answer
        boolean is_correct
    }
    support_tickets {
        bigint id PK
        bigint user_id FK
        string category
        string priority
        string subject
        text message
        string status
        text admin_notes
        timestamp resolved_at
    }

    users ||--o| profiles : "has one"
    users ||--o{ assessment_attempts : "takes"
    users ||--o{ support_tickets : "files"
    assessment_attempts ||--o{ assessment_answers : "contains"
    assessment_questions ||--o{ assessment_answers : "answered via"
    categories ||--o{ courses : "groups"
```

> Full migration definitions live in [`database/migrations/`](database/migrations).

---

## 🔌 API & Routes

### Public JSON API

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/categories` | List all categories |
| `GET` | `/api/courses` | List all courses |

### Authenticated API (Sanctum token required)

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/categories` | Create a category |
| `POST` | `/api/courses` | Create a course |
| `GET` | `/api/profile` | Get authenticated user's profile |
| `POST` | `/api/profile` | Store profile |
| `PUT` | `/api/profile` | Update profile |

### Web Routes (Blade)

| Route | Description |
|---|---|
| `GET /` | Landing page |
| `GET /login` · `POST /login` | Authentication |
| `GET /register` · `POST /register` | Registration |
| `GET /onboarding` | Onboarding form |
| `GET /assessment` | Start assessment |
| `POST /assessment/submit` | Submit answers |
| `GET /dashboard` | Learner dashboard |
| `GET /roadmap` | AI roadmap view |
| `GET /tickets` | Support tickets |
| `GET /settings` | Account settings |
| `GET /admin` | Admin dashboard |
| `GET /admin/users` | User management |
| `GET /admin/tickets` | Ticket management |

> See [`routes/web.php`](routes/web.php) and [`routes/api.php`](routes/api.php) for the complete definitions.

---

## 🧩 Models

| Model | Key Relations & Fields |
|---|---|
| `User` | `hasOne Profile`, `hasMany AssessmentAttempt`, `hasMany SupportTicket`; `is_admin` flag |
| `Profile` | `belongsTo User`; `skill_level` enum, `interests` JSON, `target_role`, `daily_learning_time` |
| `Category` | `hasMany Course` |
| `Course` | `belongsTo Category`; `difficulty_level` enum, `estimated_hours` |
| `AssessmentQuestion` | `hasMany AssessmentAnswer`; `options` JSON, `correct_answer`, `is_active` |
| `AssessmentAttempt` | `belongsTo User`, `hasMany AssessmentAnswer`; `ai_roadmap` JSON, `roadmap_provider`, `percentage` |
| `AssessmentAnswer` | `belongsTo AssessmentAttempt`, `belongsTo AssessmentQuestion`; `is_correct` |
| `SupportTicket` | `belongsTo User`; `priority`, `status`, `admin_notes`, `resolved_at` |

> Refer to [`app/Models/`](app/Models) for full relations and helper methods.

---

## 🚀 Installation

### Prerequisites

- PHP 8.1+
- Composer
- Node.js & npm
- MySQL / MariaDB _(or SQLite for quick local dev)_

### Steps

**1. Clone the repository**

```bash
git clone <your-repo-url> dynamic
cd dynamic
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Configure environment**

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and set your values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=dynamic
DB_USERNAME=root
DB_PASSWORD=

# AI Provider
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4o

# Mail
MAIL_MAILER=smtp
MAIL_HOST=...
```

**4. Install frontend assets** _(optional)_

```bash
npm install
npm run dev
```

**5. Run migrations and seeders**

```bash
php artisan migrate --seed
```

**6. Start the development server**

```bash
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000) 🎉

---

### Quick start with SQLite

For rapid local testing, skip MySQL entirely:

```bash
# In .env
DB_CONNECTION=sqlite

# Create the file
touch database/database.sqlite

# Then migrate
php artisan migrate --seed
```

---

## 🌱 Seeding & Tests

**Seed sample data:**

```bash
php artisan db:seed
```

Seeders live in [`database/seeders/`](database/seeders).

**Run the test suite:**

```bash
php artisan test
```

Tests live in [`tests/`](tests). Run specific suites with:

```bash
php artisan test --filter AssessmentTest
```

---

## ☁️ Deployment

Standard Laravel production deployment:

```bash
# Install production deps only
composer install --no-dev --optimize-autoloader

# Cache config, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start queue worker (supervisor recommended)
php artisan queue:work --daemon
```

**Recommended setups:**

| Option | Notes |
|---|---|
| **Laravel Forge** | Managed servers + zero-downtime deploys |
| **Laravel Vapor** | Serverless on AWS |
| **Docker** | Containerise for portability and consistency |

**Security checklist before going live:**

- [ ] `.env` is out of version control (`.gitignore`)
- [ ] AI provider keys are in a secrets manager
- [ ] `SANCTUM_STATEFUL_DOMAINS` and CORS are configured
- [ ] `APP_ENV=production` and `APP_DEBUG=false`
- [ ] SSL/TLS certificate in place
- [ ] Queue workers supervised (Supervisor / systemd)

---

## 🤝 Contributing

Contributions are welcome!

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/my-feature`
3. **Commit** your changes: `git commit -m 'Add some feature'`
4. **Push** to the branch: `git push origin feature/my-feature`
5. **Open** a Pull Request with a clear description of what changed and why

Please run `php artisan test` locally and ensure no regressions before opening a PR.

---

## 🔒 Security

- Never commit `.env` or API keys to source control
- Use a secrets manager (AWS Secrets Manager, Vault, etc.) for production credentials
- Rotate AI provider keys regularly and audit access logs
- Report security vulnerabilities privately via email rather than opening a public issue

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

<div align="center">

Built with ❤️ using **Laravel** · **PHP** · **OpenAI**

<br/>

<sub>Dynamic — Personalized paths that evolve with your progress.</sub>

</div>
