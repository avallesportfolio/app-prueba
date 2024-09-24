function loadHTML(file, elementId) {
    fetch(file)
      .then(response => response.text())
      .then(data => {
        document.getElementById(elementId).innerHTML = data;
        // Re-run the JavaScript initialization if needed, for example, for Bootstrap dropdowns
        const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
        dropdowns.forEach(dropdown => {
          new bootstrap.Dropdown(dropdown);
        });
      })
      .catch(error => console.error('Error loading HTML:', error));
  }
  
  document.addEventListener('DOMContentLoaded', function() {
    loadHTML('../components/header.html', 'header-placeholder');
    loadHTML('../components/footer.html', 'footer-placeholder');
  
    // Highlight the active link
    const currentPath = window.location.pathname;
    const pathMap = {
      '../index.html': 'homeLink',
      './explorar.html': 'explorarLink',
      './registrar_aloj.html': 'registrarLink'
    };
    const activeLinkId = pathMap[currentPath];
    if (activeLinkId) {
      document.getElementById(activeLinkId).classList.add('active');
    }
  });
  