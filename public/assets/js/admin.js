document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', (e) => {
      if (!confirm(form.getAttribute('data-confirm'))) e.preventDefault();
    });
  });

  const toggle = document.getElementById('menuToggle');
  const sidebar = document.getElementById('sidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', () => {
      document.body.classList.toggle('nav-open');
    });
    sidebar.querySelectorAll('a').forEach((a) => {
      a.addEventListener('click', () => document.body.classList.remove('nav-open'));
    });
    document.addEventListener('click', (e) => {
      if (!document.body.classList.contains('nav-open')) return;
      if (sidebar.contains(e.target) || toggle.contains(e.target)) return;
      document.body.classList.remove('nav-open');
    });
  }
});
