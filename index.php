<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'StudentHub | Your campus, connected';
include __DIR__ . '/includes/header.php';
?>
<main id="main-content">
  <section class="hero">
    <div class="hero-text">
      <p class="eyebrow">YOUR CAMPUS, CONNECTED</p>
      <h1>Everything you need for <span class="blue">student life.</span></h1>
      <p>Keep track of campus events, notices, learning resources and your student profile in one friendly portal.</p>
      <a class="btn" href="register.php">Get started</a>
      <a class="btn btn-outline" href="events.php">Explore events</a>
    </div>
    <div class="hero-image">
      <div class="hero-illustration" aria-label="Illustration of books and a graduation cap" role="img">
        <span class="cap">🎓</span><span class="book">📘</span><span class="book">📗</span>
        <p>Learn · Connect · Grow</p>
      </div>
    </div>
  </section>
  <section class="quick-access">
    <h2>Quick access</h2>
    <p class="section-lead">Your most-used student services, one click away.</p>
    <div class="card-container">
      <a class="card" href="dashboard.php"><span class="card-icon">▦</span><h3>Dashboard</h3><p>Your student overview</p></a>
      <a class="card" href="events.php"><span class="card-icon">📅</span><h3>Campus events</h3><p>Discover what's happening</p></a>
      <a class="card" href="notices.php"><span class="card-icon">🔔</span><h3>Notices</h3><p>Important campus updates</p></a>
      <a class="card" href="faq.php"><span class="card-icon">❔</span><h3>Help & FAQ</h3><p>Answers to common questions</p></a>
    </div>
  </section>
  <section class="events home-events">
    <div class="heading"><div><h2>Upcoming events</h2><p class="section-lead">Find your next campus experience.</p></div><a href="events.php">View all →</a></div>
    <div id="homeEvents" class="event-grid"><p>Loading events…</p></div>
  </section>
  <section class="callout"><div><h2>Make campus life simpler.</h2>
  <p>Sign in to manage your profile and explore the student dashboard.</p>
</div>
<a class="btn" href="<?= is_logged_in() ? 'dashboard.php' : 'login.php' ?>"><?= is_logged_in() ? 'Open dashboard' : 'Sign in' ?>
</a></section>
</main>
<script src="assets/js/app.js" defer></script>
<?php include __DIR__ . '/includes/footer.php'; ?>
