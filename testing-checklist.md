# Practical 15: Testing checklist

## Environment
- [ ] Apache and MySQL start in XAMPP.
- [ ] Import `database/studenthub.sql`.
- [ ] `config/db.php` credentials match local MySQL.
- [ ] Open `http://localhost/Student_hub/`.

## Functional tests
- [ ] Header navigation links open their intended pages.
- [ ] Mobile menu opens/closes and theme choice persists after reload.
- [ ] Registration rejects invalid email, mobile, weak password and mismatched confirmation.
- [ ] Registration rejects a duplicate email and stores a password hash.
- [ ] Login succeeds for a valid account and fails for invalid credentials.
- [ ] Logout clears the session; dashboard requires login.
- [ ] Session expires after 30 minutes of inactivity.
- [ ] Events load from JSON, search/filter/sort and pagination work.
- [ ] Contact form validates and writes a record to JSON.
- [ ] Admin-only pages reject student accounts.
- [ ] Student search/filter/update/delete work.
- [ ] Event create/edit/delete works; invalid/oversized poster is rejected.
- [ ] JSON API returns valid JSON and CRUD works without page reload.
- [ ] Audit entries appear after login and admin actions.
- [ ] Keyboard focus, labels, alt text/accessible names and skip link are checked.
- [ ] Test at mobile, tablet and desktop widths.

## Security notes
- [ ] Do not commit passwords, real student data, or contact messages.
- [ ] Use prepared statements and output escaping.
- [ ] Keep upload directory restricted and serve only validated image types.
- [ ] Add production HTTPS, secure cookie settings, CSRF protection to all state-changing endpoints, and deployment secrets before real use.
