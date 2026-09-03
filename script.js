document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const errorMessage = document.getElementById('errorMessage');

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Sembunyikan error sebelumnya
        errorMessage.classList.remove('show');
        errorMessage.textContent = '';
        
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Validasi sederhana
        if (!username || !password) {
            showError('Username dan password harus diisi.');
            return;
        }

        // Tampilkan status loading
        loginBtn.classList.add('loading');
        loginBtn.disabled = true;

        // Simulasi proses login dengan delay (contoh: request ke server)
        setTimeout(() => {
            loginBtn.classList.remove('loading');
            loginBtn.disabled = false;

            // Contoh logika login sederhana (dummy)
            if (username === 'admin' && password === 'admin123') {
                // Berhasil login
                loginBtn.style.backgroundColor = '#10b981'; // Ubah warna jadi hijau sukses
                loginBtn.querySelector('.btn-text').textContent = 'Berhasil Masuk!';
                loginBtn.querySelector('.btn-text').style.display = 'block';
                
                // Redirect atau aksi selanjutnya bisa ditaruh di sini
                setTimeout(() => {
                    alert('Selamat, Anda berhasil login!');
                    // Reset form
                    loginForm.reset();
                    loginBtn.style.backgroundColor = '';
                    loginBtn.querySelector('.btn-text').textContent = 'Masuk';
                }, 500);

            } else {
                // Gagal login
                showError('Username atau password salah. Coba admin / admin123');
            }
        }, 1500);
    });

    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.classList.add('show');
        
        // Efek getar (shake) pada form saat error
        const container = document.querySelector('.login-container');
        container.style.animation = 'none';
        container.offsetHeight; /* trigger reflow */
        container.style.animation = 'shake 0.5s';
    }
});

// Menambahkan keyframes untuk animasi shake secara dinamis
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);
