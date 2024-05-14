const nav = document.querySelector('navbar'),
  toggleMenu = document.getElementById('toggle-menu'),
  closeButton = document.getElementById('close-button');

if (toggleMenu) {
  toggleMenu.addEventListener('click', () => {
    nav.classList.add('show-menu')
  })
}

if (closeButton) {
  closeButton.addEventListener('click', () => {
    nav.classList.remove('show-menu')
  })
}