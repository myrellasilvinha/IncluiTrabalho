document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.mobile-menu-toggle');
  const menu = document.querySelector('#main-navigation');
  if (!toggle || !menu) return;
  const close = () => {toggle.setAttribute('aria-expanded', 'false');menu.classList.remove('is-open');toggle.querySelector('i').className = 'bi bi-list';};
  toggle.addEventListener('click', () => {
    const opening = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(opening));
    menu.classList.toggle('is-open', opening);
    toggle.querySelector('i').className = opening ? 'bi bi-x-lg' : 'bi bi-list';
  });
  menu.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
  document.addEventListener('keydown', e => {if (e.key === 'Escape') close();});
  window.addEventListener('resize', () => {if (window.innerWidth > 900) close();});
});
