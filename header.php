<?php $pageTitle = $pageTitle ?? 'StudentHub'; $current = basename($_SERVER['PHP_SELF'] ?? 'index.php'); $base = str_contains($_SERVER['PHP_SELF'] ?? '', '/admin/') ? '../' : ''; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="StudentHub campus portal for students, events and notices">
  <title><?= e($pageTitle) ?></title>
  <link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
  <script src="<?= $base ?>assets/js/app.js" defer></script>
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>
<header class="header">
  <a class="logo" href="<?= $base ?>index.php" aria-label="StudentHub home"><span class="logo-mark">S</span><span>Student<span class="logo-blue">Hub</span></span></a>
  <button class="menu-toggle" id="menuToggle" aria-expanded="false" aria-controls="primaryNav" aria-label="Open navigation">☰</button>
  <nav id="primaryNav" aria-label="Main navigation">
    <a class="<?= $current === 'index.php' ? 'active' : '' ?>" href="<?= $base ?>index.php">Home</a>
    <a class="<?= $current === 'about.php' ? 'active' : '' ?>" href="<?= $base ?>about.php">About</a>
    <a class="<?= $current === 'events.php' ? 'active' : '' ?>" href="<?= $base ?>events.php">Events</a>
    <a class="<?= $current === 'notices.php' ? 'active' : '' ?>" href="<?= $base ?>notices.php">Notices</a>
    <a class="<?= $current === 'contact.php' ? 'active' : '' ?>" href="<?= $base ?>contact.php">Contact</a>
    <a class="<?= $current === 'feedback.php' ? 'active' : '' ?>" href="<?= $base ?>feedback.php">Feedback</a>
    <?php if (is_logged_in()): ?>
      <a href="<?= $base ?>dashboard.php">Dashboard</a>
      <?php if (current_role() === 'admin'): ?><a href="<?= $base ?>admin/index.php">Admin</a><?php endif; ?>
      <a class="nav-cta" href="<?= $base ?>logout.php">Log out</a>
    <?php else: ?>
      <a href="<?= $base ?>login.php">Log in</a><a class="nav-cta" href="<?= $base ?>register.php">Register</a>
    <?php endif; ?>
    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark theme">◐</button>
  </nav>
</header>
