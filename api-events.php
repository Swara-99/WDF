

<?php 
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$pageTitle = 'AJAX event CRUD | StudentHub';
include __DIR__ . '/../includes/header.php'; 
?>

<main id="main-content">
    <section class="page-title">
        <h1>AJAX event manager</h1>
        <p>Practical 13: create and delete events through a JSON API without a full page reload.</p>
    </section>

    <section class="section">
        <form id="apiEventForm" class="form-grid">
            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" required minlength="3">
            </div>

            <div class="field">
                <label for="category">Category</label>
                <input id="category" name="category" value="Technology">
            </div>

            <div class="field">
                <label for="date">Date</label>
                <input id="date" name="date" type="date" required>
            </div>

            <div class="field">
                <label for="location">Location</label>
                <input id="location" name="location" required>
            </div>

            <div class="field full">
                <label for="description">Description</label>
                <textarea id="description" name="description" required minlength="5"></textarea>
            </div>

            <div class="field full">
                <button class="btn">Create event</button>
            </div>
        </form>

        <p id="apiStatus" role="status" aria-live="polite"></p>

        <div id="apiEvents" class="event-grid"></div>
    </section>
</main>

<script>
const csrf = <?= json_encode(csrf_token()) ?>;
const apiStatus = document.getElementById('apiStatus');
const apiEvents = document.getElementById('apiEvents');

async function loadEvents() {
    apiStatus.textContent = 'Loading…';
    try {
        const r = await fetch('../api/events.php');
        const d = await r.json();

        if (!r.ok) throw Error(d.error || 'Could not load events');

        apiEvents.replaceChildren();

        d.data.forEach(ev => {
            const c = document.createElement('article');
            c.className = 'event-card';

            const h = document.createElement('h3');
            h.textContent = ev.title;

            const p = document.createElement('p');
            p.textContent = ev.date + ' · ' + ev.category + ' · ' + ev.location;

            const b = document.createElement('button');
            b.className = 'btn danger';
            b.textContent = 'Delete';
            b.addEventListener('click', async () => {
                if (!confirm('Delete this event?')) return;

                const res = await fetch('../api/events.php?id=' + ev.id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': csrf,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: ev.id })
                });

                const result = await res.json();
                apiStatus.textContent = result.message || result.error;

                if (res.ok) loadEvents();
            });

            c.append(h, p, b);
            apiEvents.append(c);
        });

        apiStatus.textContent = d.data.length + ' event(s) loaded.';
    } catch (e) {
        apiStatus.textContent = e.message;
    }
}

document.getElementById('apiEventForm').addEventListener('submit', async e => {
    e.preventDefault();
    apiStatus.textContent = 'Saving…';

    const body = Object.fromEntries(new FormData(e.target));

    try {
        const r = await fetch('../api/events.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrf
            },
            body: JSON.stringify(body)
        });

        const d = await r.json();
        apiStatus.