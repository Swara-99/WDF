<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$pageTitle = 'Manage events | StudentHub';
$notice = '';
$uploadDir = __DIR__ . '/../uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action =$_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($pdo && $action === 'delete' &&$id) {
        $s =$pdo->prepare('DELETE FROM events WHERE id = ?');
        $s->execute([$id]);
        audit($pdo, (int)$_SESSION['user_id'], 'event_delete', (string)$id);
        $notice = 'Event deleted.';
    }

    if ($pdo && in_array($action, ['create', 'update'], true)) {$title = trim($_POST['title'] ?? '');$category = trim($_POST['category'] ?? 'General');$date = $_POST['event_date'] ?? '';$location = trim($_POST['location'] ?? '');$description = trim($_POST['description'] ?? '');$status = $_POST['status'] ?? 'open';$poster = null;
        $uploadError = '';

        if (mb_strlen($title) < 3 || !preg_match('/^\d{4}-\d{2}-\d{2}$/',$date) || $location === '' \vert{}\vert{} mb_strlen($description) < 5 || !in_array($status, ['open', 'closed'], true)) {$uploadError = 'Please complete all event fields correctly.';
        }

        if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {$f = $_FILES['poster'];$mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

            if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > 2 * 1024 * 1024 \vert{}\vert{} !isset($allowed[$mime])) {$uploadError = 'Poster must be JPG, PNG or WEBP and no larger than 2 MB.';
            } else {
                $poster = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
                if (!move_uploaded_file($f['tmp_name'],$uploadDir . $poster)) {$poster = null;
                    $uploadError = 'Could not save poster. Check uploads folder permissions.';
                }
            }
        }

        if (!$uploadError) {
            if ($action === 'create') {$s = $pdo->prepare('INSERT INTO events (title, category, event_date, location, description, status, poster, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');$s->execute([$title,$category, $date,$location, $description,$status, $poster,$_SESSION['user_id']]);
                audit($pdo, (int)$_SESSION['user_id'], 'event_create',$title);
                $notice = 'Event created.';
            } else {
                $sql = 'UPDATE events SET title = ?, category = ?, event_date = ?, location = ?, description = ?, status = ?' . ($poster ? ', poster = ?' : '') . ' WHERE id = ?';
                $args = [$title,$category, $date,$location, $description,$status];
                if ($poster) {
                    $args[] =$poster;
                }
                $args[] =$id;
                $s = $pdo->prepare($sql);
                $s->execute($args);
                audit($pdo, (int)$_SESSION['user_id'], 'event_update',$title);
                $notice = 'Event updated.';
            }
        } else {
            $notice = $uploadError;
        }
    }
}

$rows =$pdo ? $pdo->query('SELECT * FROM events ORDER BY event_date DESC LIMIT 100')->fetchAll() : [];$edit = null;

if (isset($_GET['edit']) &&$pdo) {
    $s =$pdo->prepare('SELECT * FROM events WHERE id = ?');
    $s->execute([(int)$_GET['edit']]);
    $edit =$s->fetch() ?: null;
}

include __DIR__ . '/../includes/header.php';
?>

<main id="main-content">
    <section class="page-title">
        <h1>Event management</h1>
        <p>Create, edit, remove events and validate poster uploads.</p>
    </section