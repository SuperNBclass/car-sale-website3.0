/**
 * validation.js — form field validation helpers
 * All error messages in English.
 */

const Validation = (() => {

  /* ---------- rules ---------- */
  const RULES = {
    name: {
      pattern: /^[A-Za-z\s]+$/,
      message: 'Name can only contain letters and spaces',
    },
    address: {
      pattern: /^[A-Za-z0-9\s]+$/,
      message: 'Address can only contain letters, numbers and spaces',
    },
    phone: {
      pattern: /^1[3-9]\d{9}$/,
      message: 'Please enter a valid mobile number (11 digits, starting with 1)',
    },
    email: {
      test(val) {
        const atCount = (val.match(/@/g) || []).length;
        return atCount === 1 && /\.(com|cn)$/i.test(val);
      },
      message: 'Email must contain exactly one @ and end with .com or .cn',
    },
    username: {
      pattern: /^[A-Za-z0-9]{6,}$/,
      message: 'Username must be at least 6 alphanumeric characters',
    },
    password: {
      pattern: /^[A-Za-z0-9]{6,}$/,
      message: 'Password must be at least 6 alphanumeric characters',
    },
  };

  /**
   * Validate a single field value.
   * @returns {string|null} error message or null if valid
   */
  function validate(field, value) {
    const trimmed = (value || '').trim();
    if (!trimmed) return 'This field is required';

    const rule = RULES[field];
    if (!rule) return null;

    if (rule.pattern) {
      return rule.pattern.test(trimmed) ? null : rule.message;
    }
    if (rule.test) {
      return rule.test(trimmed) ? null : rule.message;
    }
    return null;
  }

  /**
   * Show or clear an error for a field.
   * Expects elements with id="${fieldId}" and id="${fieldId}-error".
   */
  function showError(fieldId, message) {
    const inputEl = document.getElementById(fieldId);
    const errEl   = document.getElementById(fieldId + '-error');

    if (errEl) {
      errEl.textContent = message || '';
    }
    if (inputEl) {
      if (message) {
        inputEl.classList.add('is-error');
      } else {
        inputEl.classList.remove('is-error');
      }
    }
  }

  function clearError(fieldId) {
    showError(fieldId, '');
  }

  /**
   * Attach blur + live-clear listeners to a list of field IDs.
   * @param {string[]} fieldIds
   */
  function attachLiveValidation(fieldIds) {
    fieldIds.forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;

      el.addEventListener('blur', () => {
        const err = validate(id, el.value);
        showError(id, err);
      });

      el.addEventListener('input', () => {
        // Clear error as soon as user starts correcting
        const errEl = document.getElementById(id + '-error');
        if (errEl && errEl.textContent) {
          const err = validate(id, el.value);
          showError(id, err);
        }
      });
    });
  }

  /* ---------- public API ---------- */
  return { validate, showError, clearError, attachLiveValidation };
})();
