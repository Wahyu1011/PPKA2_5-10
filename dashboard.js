document.addEventListener("DOMContentLoaded", () => {
    // Basic logout logic
    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", (e) => {
            e.preventDefault();
            alert("Anda telah keluar.");
            window.location.href = "index.html";
        });
    }

    // Add slight entrance animation for cards
    const cards = document.querySelectorAll('.stat-card, .facility-card, .reservation-item');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease-out';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});
