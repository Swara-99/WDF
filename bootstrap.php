<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_start();
}
define('APP_NAME', 'StudentHub');
define('SESSION_TIMEOUT', 1800);
if (isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT) {
    $_SESSION = [];
    session_destroy();
    session_start();
}
$_SESSION['LAST_ACTIVITY'] = time();
require_once __DIR__ . '/../config/db.php';
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function is_logged_in(): bool { return isset($_SESSION['user_id']); }
function current_role(): string { return $_SESSION['role'] ?? 'guest'; }
function require_login(): void {
    if (!is_logged_in()) { header('Location: login.php'); exit; }
}
function require_admin(): void {
    require_login();
    if (current_role() !== 'admin') { http_response_code(403); exit('403 Forbidden: admin access required.'); }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419); exit('Invalid form token. Please go back and try again.');
    }
}
function audit(PDO $pdo, ?int $userId, string $action, string $detail = ''): void {
    try {
        $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, detail, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$userId, $action, mb_substr($detail, 0, 250)]);
    } catch (Throwable $ex) { /* audit failure must not expose database details */ }
}
