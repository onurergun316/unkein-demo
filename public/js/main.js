// public/js/main.js
document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-step]');
  if (!btn) return;

  const wrap = btn.closest('form, .qty');
  const input = wrap?.querySelector('input[type="number"]');
  if (!input) return;

  const step = btn.dataset.step === 'inc' ? 1 : -1;
  const current = parseInt(input.value || '0', 10) || 0;
  const min = parseInt(input.min || '0', 10) || 0;

  const next = Math.max(min, current + step);
  input.value = String(next);
});