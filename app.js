document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('menuToggle');
  const nav = document.getElementById('primaryNav');
  if (toggle && nav) toggle.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
  });
  const themeButton = document.getElementById('themeToggle');
  const savedTheme = localStorage.getItem('studenthub-theme');
  if (savedTheme === 'dark') document.body.classList.add('dark');
  if (themeButton) themeButton.addEventListener('click', () => {
    document.body.classList.toggle('dark');
    localStorage.setItem('studenthub-theme', document.body.classList.contains('dark') ? 'dark' : 'light');
  });
  document.querySelectorAll('[data-confirm]').forEach(el => el.addEventListener('click', e => {
    if (!confirm(el.dataset.confirm || 'Are you sure?')) e.preventDefault();
  }));
  document.querySelectorAll('.faq-question').forEach(button => button.addEventListener('click', () => {
    const answer = document.getElementById(button.getAttribute('aria-controls'));
    const expanded = button.getAttribute('aria-expanded') === 'true';
    button.setAttribute('aria-expanded', String(!expanded));
    if (answer) answer.hidden = expanded;
  }));
  const form = document.getElementById('registrationForm');
  if (form) {
    const password = form.querySelector('[name="password"]');
    const strength = document.getElementById('passwordStrength');
    if (password && strength) password.addEventListener('input', () => {
      const p = password.value; let score = 0;
      if (p.length >= 8) score++; if (/[A-Z]/.test(p)) score++;
      if (/[a-z]/.test(p)) score++; if (/\d/.test(p)) score++; if (/[^A-Za-z0-9]/.test(p)) score++;
      strength.textContent = !p ? '' : score < 3 ? 'Password strength: weak' : score < 5 ? 'Password strength: fair' : 'Password strength: strong';
    });
    form.addEventListener('submit', e => {
      let valid = true;
      form.querySelectorAll('[data-error-for]').forEach(span => span.textContent = '');
      const setError = (name, message) => { const target = form.querySelector(`[name="${name}"]`); const span = form.querySelector(`[data-error-for="${name}"]`); if (message && span) span.textContent = message; if (message) valid = false; if (target) target.setAttribute('aria-invalid', message ? 'true' : 'false'); };
      const name = form.querySelector('[name="name"]')?.value.trim() || '';
      const email = form.querySelector('[name="email"]')?.value.trim() || '';
      const mobile = form.querySelector('[name="mobile"]')?.value.trim() || '';
      const p = password?.value || '';
      const cp = form.querySelector('[name="confirm_password"]')?.value || '';
      setError('name', name.length < 2 ? 'Enter your full name.' : '');
      setError('email', !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) ? 'Enter a valid email address.' : '');
      setError('mobile', !/^[6-9]\d{9}$/.test(mobile) ? 'Enter a valid 10-digit Indian mobile number.' : '');
      setError('password', !/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(p) ? 'Use 8+ characters with uppercase, lowercase and a number.' : '');
      setError('confirm_password', p !== cp ? 'Passwords do not match.' : '');
      if (!form.querySelector('[name="terms"]')?.checked) { setError('terms', 'Please accept the terms.'); }
      if (!valid) e.preventDefault();
    });
  }
  const homeEvents = document.getElementById('homeEvents');
  if (homeEvents) {
    fetch('data/events.json').then(r => { if (!r.ok) throw new Error('Could not load events'); return r.json(); })
      .then(events => { homeEvents.replaceChildren(); events.slice(0,3).forEach(ev => {
        const card = document.createElement('article'); card.className = 'event-card';
        const date = document.createElement('span'); date.className = 'date'; date.textContent = ev.date;
        const title = document.createElement('h3'); title.textContent = ev.title;
        const desc = document.createElement('p'); desc.textContent = ev.description;
        card.append(date,title,desc); homeEvents.append(card);
      }); }).catch(() => { homeEvents.textContent = 'Events are temporarily unavailable. Please visit the Events page later.'; });
  }
});
