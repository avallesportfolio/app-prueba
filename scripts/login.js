// login.js
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Realizar una petición al servidor para autenticar
            fetch('../login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Guardar información del usuario
                    localStorage.setItem('user', JSON.stringify(data.user));
                    // Redirigir a perfil.html
                    window.location.href = 'perfil.html';
                } else {
                    alert(data.message || 'Error en el inicio de sesión');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error durante el inicio de sesión');
            });
        });
    } else {
        console.error('El formulario de login no se encontró');
    }
});