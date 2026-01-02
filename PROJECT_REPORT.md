
# Wellness Tracker — Project Report

## 1. Introduction
This document summarizes the Wellness Tracker project — a secure, privacy-minded web application that helps users record and reflect on mood and stress levels, access coping resources, set goals, and receive contextual wellness guidance from an AI assistant. The report condenses the system design, implementation details, security considerations, and operational recommendations.
## 2. Problem Statement
Many people lack a simple, private, and consistent way to track mental well-being. Clinical tools can be intimidating or inaccessible, and generic journaling lacks structure and insight. The Wellness Tracker addresses this gap by offering a lightweight, user-friendly platform for daily self-monitoring, trend analysis, and immediate coping suggestions.
## 3. Proposed Solution
A modular web application that combines daily data capture, visualization, and an AI-guided companion. Key elements:

- User-authenticated dashboards for private entries and profile management.
- A compact data model for daily mood/stress entries and goals.
- Visual trends (Chart.js) and simple export for portability.
- AI chat that uses local LLM (Ollama) for contextual, short, supportive responses.
- Voice-to-text input in the chat interface for accessibility and ease-of-use.
## 4. Project Objectives
Primary objectives:

- Deliver a secure, non-judgmental space for daily mood and stress logging.
- Provide quick, actionable insights via charts and AI-driven suggestions.
- Enable users to set and track personal wellness goals.
- Provide admins with content and user-management tools.
## 5. Project Scope
In-scope:

- User registration, login, and profile management.
- Daily mood and stress logging with one-entry-per-day constraint.
- 7-day trend visualization and monthly aggregates.
- AI chat companion with voice input.
- Admin area for prompts/resources and user oversight.

Out of scope for the current release: full mobile apps, automatic clinician escalation, long-term population analytics.
## 6. System Requirements
Software and environment summary:

- PHP 7.x or newer with MySQLi support
- MySQL / MariaDB
- Modern browser (Chrome/Edge/Safari recommended for voice features)
- Optional: Ollama or compatible local LLM for AI responses
## 7. System Architecture
Server-rendered PHP application with a thin frontend layer. Architecture highlights:

- Controllers in `php/` handle requests, validations and DB interaction.
- Views in `html/` render UI and include client-side JS for charts and voice support.
- MySQL database stores users, mood entries, goals, prompts and resources.
- AI communication is handled server-side (calls to local Ollama API) to keep prompts and context secure.
## 8. Module Overview
Modules and responsibilities:

- `php/connect_db.php` — central DB connection used by controllers.
- `php/register.php`, `php/login.php` — authentication flow and session setup.
- `php/dashboard.php` + `html/dashboard_view.php` — entry form, summary chart, and submission logic.
- `php/ai_chat.php` + `html/ai_chat_view.php` — chat endpoint and UI, voice-to-text client code.
- `php/goals.php`, `html/goals_view.php` — create and manage personal goals.
- `php/admin/` — protected admin controllers and views for managing prompts and resources.
- `php/export.php` — CSV export of user entries.
## 9. Functional Requirements
Functional behavior implemented in the repository:

- Secure user registration and login with session handling.
- Single daily mood/stress entry with update-if-exists behavior.
- Charting of recent entries (7-day) and export to CSV.
- AI chat with contextual prompts derived from latest user data.
- Admin CRUD for journal prompts and coping resources.
## 10. Non-Functional Requirements
Key non-functional attributes:

- Security: Prepared statements, password hashing (bcrypt), role checks for admin routes.
- Usability: Clean UI with dark-mode, responsive layout, voice input for chat.
- Performance: Lightweight pages; DB indexed on common keys.
- Maintainability: Modular PHP controllers and separated views; simple SQL schema.
## 11. Database Schema
Primary tables:

- `users(user_id, username, email, password_hash, role, created_at)`
- `mood_entries(entry_id, user_id, mood_score, stress_score, notes, entry_date, created_at)`
- `goals(goal_id, user_id, goal_text, is_completed, created_at)`
- `coping_resources(resource_id, category, title, content, sort_order, user_id)`
- `journal_prompts(prompt_id, prompt_text)`

Constraints and relations: foreign keys link entries/goals/resources to users; unique constraint on (user_id, entry_date) prevents duplicate daily entries.
## 12. Security Overview
Security measures in the project:

- Passwords hashed with PHP's `password_hash()` (bcrypt).
- Use of prepared statements (mysqli prepared) for DB queries.
- Session-based authorization; admin-only pages protected by role checks.
- Input validation and basic sanitization before DB insertion and output escaping in views.
- Sensitive configuration isolated in `php/connect_db.php` for easy environment-specific updates.
## 13. Testing & Validation
Project testing and checks performed:

- Manual functional testing of registration, login, dashboard entry, editing, and export flows.
- PHP syntax checks across controllers; no parse errors found.
- Manual verification of voice-to-text behavior in supported browsers.
- Basic validation in controllers (score ranges, required fields).

Suggested next steps: automated unit tests for PHP controllers and integration tests for key flows.
## 14. Deployment & Operations
Deployment guidance:

- Configure a LAMP/LEMP server with PHP and MySQL/MariaDB.
- Create `wellness_tracker_db` and run `database/create_table.sql`.
- Update DB credentials in `php/connect_db.php`.
- Optional: deploy and configure Ollama if local AI is required.

Operational notes: ensure secure TLS/HTTPS in production, rotate DB credentials, and backup the database regularly.
## 15. User Guide (Usage)
How end-users interact with the system:

- Register or sign in, then open the dashboard to submit daily scores.
- Edit or update today's entry if needed; the system prevents duplicate entries per day.
- Use AI Chat for short, supportive advice; optionally use the microphone button to dictate messages.
- Manage goals via the Goals page; export data from Export page.
## 16. Admin Guide
Admin capabilities and workflow:

- Create and manage journal prompts and coping resources via admin UI.
- View system metrics (total users, entries) in admin dashboard.
- Manage user accounts (view details, delete if necessary).
- Use caution when deleting users; data loss is cascading for associated records.
## 17. Voice & AI Features
Implemented features:

- Client-side voice-to-text using the Web Speech API with a graceful fallback to typing.
- Server-side AI integration via a local Ollama endpoint; system prompts include the user's most recent mood/stress data for contextual responses.
- Short, supportive response format enforced by system prompts to keep AI replies concise.
## 18. Future Enhancements
Practical improvements to consider:

- Automated tests (unit + integration) and CI pipeline.
- Text-to-speech for AI responses for a full voice experience.
- Role-based audit logs and soft-delete for user safety.
- Internationalization/localization support for multiple languages.
- Mobile app or PWA to increase accessibility and offline caching.
## 19. Known Issues & Limitations
Current limitations:

- Voice recognition depends on browser support and network (cloud-based service behind Web Speech API).
- AI depends on a local Ollama instance; not available if Ollama is not running.
- No automated testing or CI configured yet.
- Basic UI — suitable for MVP but could benefit from UX polish and accessibility audits.
## 20. Recommendations & Next Steps
Recommended immediate actions:

- Add automated tests and set up CI for releases.
- Harden deployment by enabling HTTPS and securing DB credentials via environment variables.
- Replace any default admin credentials and enforce a password policy.
- Add monitoring and periodic backups for production deployments.
## 21. Project Metrics & Statistics
Current metrics snapshot (approximate):

- Files: ~30
- Lines of code: ~4,800
- PHP controllers: 24
- Database tables: 6
- Key features implemented: authentication, tracking, AI chat, voice input, admin panel, export
## 22. Credits & Acknowledgements
Contributors and resources:

- Project author / repository owner: (see repository metadata)
- Open-source libraries: Chart.js and standard browser APIs
- Local LLM: Ollama (optional integration)
## 23. Appendix
Supporting materials and scripts:

- `database/create_table.sql` — schema and initial data
- `database/fix_admin_password.sql` — admin password fix script
- Documentation files for the voice feature and setup are included in the repository.

---

**Report generated:** January 02, 2026
