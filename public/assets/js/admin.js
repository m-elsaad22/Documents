document.addEventListener('DOMContentLoaded', () => {
  // Confirm destructive forms
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', (e) => {
      if (!confirm(form.getAttribute('data-confirm'))) e.preventDefault();
    });
  });
});
