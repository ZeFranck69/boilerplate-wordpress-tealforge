const initMobileNavigation = () => {
  const toggles = document.querySelectorAll('[data-tf-menu-toggle]');
  const panel = document.querySelector('[data-tf-menu-panel]');

  if (!toggles.length || !panel) {
    return;
  }

  const setOpen = (isOpen) => {
    document.documentElement.classList.toggle('tf-menu-is-open', isOpen);
    toggles.forEach((toggle) => {
      toggle.setAttribute('aria-expanded', String(isOpen));
    });
  };

  toggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
      setOpen(!document.documentElement.classList.contains('tf-menu-is-open'));
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      setOpen(false);
    }
  });
};

document.addEventListener('DOMContentLoaded', () => {
  initMobileNavigation();
});
