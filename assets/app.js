(function () {
  'use strict';

  var doc = document;
  var root = doc.documentElement;

  /* ---------- Mobile menu ---------- */
  var toggle = doc.querySelector('.mobile-menu-toggle');
  var menu = doc.querySelector('#main-navigation');
  var closeMenu = function () {
    if (!toggle || !menu) return;
    toggle.setAttribute('aria-expanded', 'false');
    menu.classList.remove('is-open');
    var icon = toggle.querySelector('i');
    if (icon) icon.className = 'bi bi-list';
    doc.body.style.overflow = '';
  };
  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      var opening = toggle.getAttribute('aria-expanded') !== 'true';
      toggle.setAttribute('aria-expanded', String(opening));
      menu.classList.toggle('is-open', opening);
      var icon = toggle.querySelector('i');
      if (icon) icon.className = opening ? 'bi bi-x-lg' : 'bi bi-list';
      doc.body.style.overflow = opening ? 'hidden' : '';
    });
    menu.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', closeMenu); });
    doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });
    window.addEventListener('resize', function () { if (window.innerWidth > 980) closeMenu(); });
    doc.addEventListener('click', function (e) {
      if (!menu.classList.contains('is-open')) return;
      if (menu.contains(e.target) || toggle.contains(e.target)) return;
      closeMenu();
    });
  }

  /* ---------- Password visibility ---------- */
  doc.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = doc.getElementById(btn.getAttribute('data-password-toggle'));
      if (!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
      btn.setAttribute('aria-pressed', String(show));
      var icon = btn.querySelector('i');
      if (icon) icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
  });

  /* ---------- Password strength ---------- */
  doc.querySelectorAll('[data-strength-for]').forEach(function (bar) {
    var input = doc.getElementById(bar.getAttribute('data-strength-for'));
    if (!input) return;
    var fill = bar.querySelector('span');
    var label = doc.querySelector('[data-strength-label="' + input.id + '"]');
    input.addEventListener('input', function () {
      var v = input.value;
      var score = 0;
      if (v.length >= 8) score++;
      if (/[A-Z]/.test(v)) score++;
      if (/[0-9]/.test(v)) score++;
      if (/[^A-Za-z0-9]/.test(v)) score++;
      var widths = ['0%', '25%', '50%', '75%', '100%'];
      var colors = ['#b42318', '#b42318', '#b54708', '#0f7a52', '#0f7a52'];
      var names = ['', 'Fraca', 'Média', 'Boa', 'Forte'];
      if (fill) { fill.style.width = widths[score]; fill.style.background = colors[score]; }
      if (label) label.textContent = v ? (names[score] || '') : '';
    });
  });

  /* ---------- Form validation hints ---------- */
  doc.querySelectorAll('form[data-validate]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var ok = true;
      form.querySelectorAll('input[required], select[required], textarea[required]').forEach(function (field) {
        var wrap = field.closest('.field');
        if (!wrap) return;
        var msg = wrap.querySelector('.error');
        if (!field.checkValidity()) {
          ok = false;
          wrap.classList.add('has-error');
          if (msg && !msg.textContent) msg.textContent = 'Preencha este campo corretamente.';
        } else {
          wrap.classList.remove('has-error');
        }
      });
      if (!ok) { e.preventDefault(); var first = form.querySelector('.has-error input, .has-error select, .has-error textarea'); if (first) first.focus(); }
    });
  });

  /* ---------- Auto-submit filters ---------- */
  doc.querySelectorAll('form[data-auto-submit]').forEach(function (form) {
    form.querySelectorAll('input[type="checkbox"], select[data-submit]').forEach(function (el) {
      el.addEventListener('change', function () { form.submit(); });
    });
  });

  /* ---------- Mobile filter toggle ---------- */
  var filterToggle = doc.querySelector('[data-filter-toggle]');
  var filterPanel = doc.querySelector('#filtros');
  if (filterToggle && filterPanel) {
    filterToggle.addEventListener('click', function () {
      var open = filterPanel.classList.toggle('is-open');
      filterToggle.setAttribute('aria-expanded', String(open));
      var icon = filterToggle.querySelector('i');
      if (icon) icon.className = open ? 'bi bi-funnel-fill' : 'bi bi-funnel';
    });
  }

  /* ---------- Confirm destructive actions ---------- */
  doc.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (!window.confirm(el.getAttribute('data-confirm'))) e.preventDefault();
    });
  });

  /* ---------- Copy link ---------- */
  doc.querySelectorAll('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var text = btn.getAttribute('data-copy') || window.location.href;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function () { showToast('Link copiado!'); });
      } else {
        var tmp = doc.createElement('textarea');
        tmp.value = text; doc.body.appendChild(tmp); tmp.select();
        try { doc.execCommand('copy'); showToast('Link copiado!'); } catch (e) {}
        doc.body.removeChild(tmp);
      }
    });
  });

  /* ---------- Toast ---------- */
  var toast = doc.querySelector('.toast');
  var toastTimer;
  function showToast(message) {
    if (!toast) {
      toast = doc.createElement('div');
      toast.className = 'toast';
      toast.setAttribute('role', 'status');
      toast.innerHTML = '<i class="bi bi-check-circle"></i><span></span>';
      doc.body.appendChild(toast);
    }
    var span = toast.querySelector('span');
    if (span) span.textContent = message;
    toast.classList.add('is-visible');
    window.clearTimeout(toastTimer);
    toastTimer = window.setTimeout(function () { toast.classList.remove('is-visible'); }, 4200);
  }
  window.showToast = showToast;
  if (toast) {
    window.setTimeout(function () { toast.classList.remove('is-visible'); }, 5000);
  }

  /* ---------- Accessibility toolbar ---------- */
  var a11yFab = doc.querySelector('.a11y-fab');
  var a11yPanel = doc.querySelector('.a11y-panel');
  if (a11yFab && a11yPanel) {
    a11yFab.addEventListener('click', function () {
      var open = a11yPanel.classList.toggle('is-open');
      a11yFab.setAttribute('aria-expanded', String(open));
    });
    doc.addEventListener('click', function (e) {
      if (!a11yPanel.classList.contains('is-open')) return;
      if (a11yPanel.contains(e.target) || a11yFab.contains(e.target)) return;
      a11yPanel.classList.remove('is-open');
      a11yFab.setAttribute('aria-expanded', 'false');
    });
    doc.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { a11yPanel.classList.remove('is-open'); a11yFab.setAttribute('aria-expanded', 'false'); }
    });
  }

  function applyPref(key, value) {
    if (value) root.classList.add('a11y-' + key); else root.classList.remove('a11y-' + key);
    try { localStorage.setItem('a11y-' + key, value ? '1' : '0'); } catch (e) {}
    doc.querySelectorAll('[data-a11y="' + key + '"]').forEach(function (b) {
      b.classList.toggle('is-active', !!value);
      b.setAttribute('aria-pressed', String(!!value));
    });
  }
  ['large', 'contrast', 'underline'].forEach(function (key) {
    var saved = null;
    try { saved = localStorage.getItem('a11y-' + key); } catch (e) {}
    if (saved === '1') applyPref(key, true);
    doc.querySelectorAll('[data-a11y="' + key + '"]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        applyPref(key, !root.classList.contains('a11y-' + key));
      });
    });
  });
  var resetA11y = doc.querySelector('[data-a11y-reset]');
  if (resetA11y) {
    resetA11y.addEventListener('click', function () {
      ['large', 'contrast', 'underline'].forEach(function (k) { applyPref(k, false); });
      showToast('Preferências de acessibilidade restauradas.');
    });
  }

  /* ---------- Cookie banner ---------- */
  var cookie = doc.querySelector('.cookie-banner');
  if (cookie) {
    var stored = null;
    try { stored = localStorage.getItem('cookie-consent'); } catch (e) {}
    if (!stored) cookie.classList.add('is-visible');
    cookie.querySelectorAll('[data-cookie]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        try { localStorage.setItem('cookie-consent', btn.getAttribute('data-cookie')); } catch (e) {}
        cookie.classList.remove('is-visible');
        cookie.style.display = 'none';
      });
    });
  }

  /* ---------- Salvar vagas ---------- */
  doc.querySelectorAll('[data-salvar-vaga]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var base = (window.INCLUI_BASE || '');
      if (!window.INCLUI_LOGADO) {
        window.location.href = base + 'telas/autenticarCandidato.php?aviso=salvar';
        return;
      }
      var id = btn.getAttribute('data-salvar-vaga');
      var salvo = btn.getAttribute('aria-pressed') === 'true';
      btn.disabled = true;
      fetch(base + 'controladores/rota.php?acao=salvarVaga', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'vagaId=' + encodeURIComponent(id) + '&_csrf=' + encodeURIComponent(doc.querySelector('meta[name="csrf-token"]') ? doc.querySelector('meta[name="csrf-token"]').content : (window.INCLUI_CSRF || ''))
      }).then(function (r) { return r.json(); }).then(function (data) {
        if (!data.ok) { showToast(data.mensagem || 'Não foi possível salvar agora.'); return; }
        var agora = !salvo;
        btn.setAttribute('aria-pressed', String(agora));
        btn.classList.toggle('is-saved', agora);
        var icon = btn.querySelector('i');
        if (icon) icon.className = agora ? 'bi bi-heart-fill' : 'bi bi-heart';
        var label = btn.querySelector('[data-save-label]');
        if (label) {
          label.textContent = agora ? 'Vaga salva' : 'Salvar vaga';
          btn.setAttribute('aria-label', agora ? 'Remover vaga salva da lista' : 'Salvar vaga na lista');
        } else {
          btn.setAttribute('aria-label', agora ? 'Remover vaga dos favoritos' : 'Salvar vaga nos favoritos');
        }
        showToast(agora ? 'Vaga salva na sua lista.' : 'Vaga removida da sua lista.');
      }).catch(function () {
        showToast('Não foi possível salvar agora. Tente novamente.');
      }).finally(function () { btn.disabled = false; });
    });
  });

  /* ---------- Reveal on scroll ---------- */
  var revealEls = doc.querySelectorAll('.reveal');
  if (revealEls.length && 'IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { entry.target.classList.add('is-visible'); obs.unobserve(entry.target); }
      });
    }, { threshold: .12 });
    revealEls.forEach(function (el) { obs.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }
})();
