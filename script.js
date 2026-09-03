document.addEventListener('DOMContentLoaded', () => {
    // --- Logika Login ---
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const errorMessage = document.getElementById('errorMessage');

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            errorMessage.classList.remove('show');
            errorMessage.textContent = '';
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (!username || !password) {
                showError(errorMessage, 'Username dan password harus diisi.');
                return;
            }

            loginBtn.classList.add('loading');
            loginBtn.disabled = true;

            setTimeout(() => {
                loginBtn.classList.remove('loading');
                loginBtn.disabled = false;

                if (username === 'admin' && password === 'admin123') {
                    loginBtn.style.backgroundColor = '#10b981';
                    loginBtn.querySelector('.btn-text').textContent = 'Berhasil Masuk!';
                    loginBtn.querySelector('.btn-text').style.display = 'block';
                    
                    setTimeout(() => {
                        alert('Selamat, Anda berhasil login!');
                        loginForm.reset();
                        loginBtn.style.backgroundColor = '';
                        loginBtn.querySelector('.btn-text').textContent = 'Masuk';
                    }, 500);
                } else {
                    showError(errorMessage, 'Username atau password salah. Coba admin / admin123');
                }
            }, 1500);
        });
    }

    // --- Logika Register ---
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    const regErrorMessage = document.getElementById('regErrorMessage');

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            regErrorMessage.classList.remove('show');
            regErrorMessage.textContent = '';
            
            const username = document.getElementById('regUsername').value;
            const email = document.getElementById('regEmail').value;
            const password = document.getElementById('regPassword').value;
            const confirmPassword = document.getElementById('regConfirmPassword').value;

            if (password !== confirmPassword) {
                showError(regErrorMessage, 'Password dan konfirmasi password tidak cocok.');
                return;
            }

            registerBtn.classList.add('loading');
            registerBtn.disabled = true;

            setTimeout(() => {
                registerBtn.classList.remove('loading');
                registerBtn.disabled = false;

                registerBtn.style.backgroundColor = '#10b981';
                registerBtn.querySelector('.btn-text').textContent = 'Pendaftaran Berhasil!';
                registerBtn.querySelector('.btn-text').style.display = 'block';
                
                setTimeout(() => {
                    alert('Akun berhasil dibuat! Silakan login.');
                    window.location.href = 'index.html'; // Redirect ke halaman login
                }, 1000);
            }, 1500);
        });
    }

    // Fungsi helper untuk menampilkan error
    function showError(element, message) {
        element.textContent = message;
        element.classList.add('show');
        
        const container = document.querySelector('.login-container');
        container.style.animation = 'none';
        container.offsetHeight; /* trigger reflow */
        container.style.animation = 'shake 0.5s';
    }
});

// Menambahkan keyframes untuk animasi shake secara dinamis
if (!document.getElementById('shakeAnimation')) {
    const style = document.createElement('style');
    style.id = 'shakeAnimation';
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
}
