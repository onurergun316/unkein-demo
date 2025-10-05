// -----------------------------------------------------------------------------
// File: public/js/main.js
// Project: Unkein E-Commerce Frontend
// Purpose: Handles quantity stepper logic (increment/decrement buttons) in
//          forms and cart interfaces without relying on external libraries.
//
// Context:
//   - This script supports <input type="number"> elements wrapped in forms
//     or containers with the `.qty` class.
//   - Buttons use the `data-step="inc"` or `data-step="dec"` attribute.
// -----------------------------------------------------------------------------

document.addEventListener('click', (e) => {
  // Detect nearest button with data-step attribute (either + or −)
  const btn = e.target.closest('[data-step]');
  if (!btn) return; // Ignore unrelated clicks

  // Locate the input field within the same quantity wrapper or form
  const wrap = btn.closest('form, .qty');
  const input = wrap?.querySelector('input[type="number"]');
  if (!input) return; // No valid input found — abort

  // Determine increment or decrement action
  const step = btn.dataset.step === 'inc' ? 1 : -1;

  // Parse current, minimum, and next values (with safe defaults)
  const current = parseInt(input.value || '0', 10) || 0;
  const min = parseInt(input.min || '0', 10) || 0;
  const next = Math.max(min, current + step);

  // Apply the updated quantity back to the input
  input.value = String(next);
});