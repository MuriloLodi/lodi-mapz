  const toggleBtn = document.querySelector('.nav-toggle');
  const navMenu = document.querySelector('.nav-menu');

  toggleBtn.addEventListener('click', (e) => {
    e.stopPropagation(); 
    navMenu.classList.toggle('active');
  });
  document.addEventListener('click', (e) => {
    if (!navMenu.contains(e.target) && !toggleBtn.contains(e.target)) {
      navMenu.classList.remove('active');
    }
  });