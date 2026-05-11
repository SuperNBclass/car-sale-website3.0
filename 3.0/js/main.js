document.addEventListener('DOMContentLoaded', () => {
  const page = document.body.dataset.page;

  if (page === 'register') {
    Validation.attachLiveValidation(['name', 'address', 'phone', 'email', 'username', 'password']);
  }

  if (page === 'add-car') {
    initImagePreview();
  }

  initUI();
});

function initImagePreview() {
  const fileInput = document.getElementById('image');
  const preview   = document.getElementById('img-preview');
  if (!fileInput || !preview) return;

  fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
      preview.innerHTML = `<img src="${ev.target.result}" alt="Preview">`;
    };
    reader.readAsDataURL(file);
  });
}

function initUI() {
  // Back to top button
  const btn = document.createElement('button');
  btn.id = 'back-to-top';
  btn.textContent = '↑';
  document.body.appendChild(btn);

  window.addEventListener('scroll', () => {
    btn.classList.toggle('visible', window.scrollY > 300);
  });
  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // Typewriter effect on hero title
  const heroTitle = document.querySelector('.hero-title');
  if (heroTitle) {
    const text = heroTitle.textContent;
    heroTitle.textContent = '';
    let i = 0;
    const timer = setInterval(() => {
      heroTitle.textContent += text[i];
      i++;
      if (i >= text.length) clearInterval(timer);
    }, 80);
  }

  // Page fade-in transition on link click
  document.addEventListener('click', e => {
    const link = e.target.closest('a');
    if (link && link.href && !link.href.startsWith('#') &&
        !link.href.startsWith('javascript') && link.target !== '_blank') {
      e.preventDefault();
      document.body.style.transition = 'opacity 0.3s ease';
      document.body.style.opacity = '0';
      setTimeout(() => { window.location.href = link.href; }, 300);
    }
  });

  // Animate car list items on results page
  document.querySelectorAll('.car-list-item').forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(16px)';
    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
    setTimeout(() => {
      el.style.opacity = '1';
      el.style.transform = 'translateY(0)';
    }, i * 120);
  });
}

// Utility: escape HTML
function esc(str) {
  return String(str)
    .replace(/&/g, '&amp;').replace(/</g, '&lt;')
    .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

function fmtPrice(n) { return Number(n).toLocaleString('zh-CN'); }
function get(id)      { const el = document.getElementById(id); return el ? el.value.trim() : ''; }
function setVal(id, v){ const el = document.getElementById(id); if (el) el.value = v; }

function showFlash(el, type, msg) {
  if (!el) return;
  el.className = 'flash flash-' + type;
  el.textContent = msg;
  el.style.display = 'block';
}

function setFieldError(id, msg) {
  const inputEl = document.getElementById(id);
  const errEl   = document.getElementById(id + '-error');
  if (errEl) errEl.textContent = msg;
  if (inputEl) {
    if (msg) inputEl.classList.add('is-error');
    else     inputEl.classList.remove('is-error');
  }
}
