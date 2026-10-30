# StudentHub Portal

A practical-oriented student portal for WDF practicals 2–15, using HTML5, CSS3, JavaScript, PHP and MySQL.

## Requirements
- XAMPP (Apache + PHP + MySQL)
- A modern browser
- Optional: VS Code and Git

## Install
1. Copy the `Student_hub` folder into `C:\xampp\htdocs\Student_hub`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `database/studenthub.sql`.
5. Review `config/db.php` and update credentials if needed (default XAMPP commonly uses user `root` with an empty password).
6. Visit `http://localhost/Student_hub/`.

## Demo accounts
Create accounts from Register. To create an admin account for local practical demonstration:
1. Register a normal account.
2. In phpMyAdmin, run:
   `UPDATE users SET role='admin' WHERE email='your-email@example.com';`
3. Log out and log back in.

## Practical mapping
- P2: semantic pages, skip link, accessible labels and navigation
- P3: responsive Grid/Flexbox and media queries
- P4: FAQ accordion, menu, theme preference, notifications
- P5: client-side registration validation
- P6: external JSON data, search/filter/sort/pagination
- P7: PHP POST processing and JSON file storage (`data/contact_messages.json`)
- P8: MySQL schema and PDO connection
- P9: secure registration, duplicate email check, password hashing
- P10: sessions, role checks, timeout, login/logout
- P11: student CRUD, search and course filter
- P12: event CRUD, poster upload type/size validation
- P13: JSON API and Fetch-based event CRUD
- P14: admin dashboard, role-based menu, audit logs
- P15: integration and local deployment instructions

## Notes
- Use only fictional/test data in this classroom project.
- Configure HTTPS, secure cookies, CSRF protections, backups and production secrets before real deployment.
- The remember-me feature is intentionally not enabled in this starter project; do not store raw long-lived authentication tokens.
