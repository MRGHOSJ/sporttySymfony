// Contenu du fichier star-rating.js
document.querySelectorAll('.star').forEach(star => {
    star.onmouseover = () => {
        document.querySelectorAll('.star').forEach(s => {
            s.style.color = 'gray';
        });
        let currentStar = star;
        while (currentStar) {
            currentStar.style.color = 'gold';
            currentStar = currentStar.previousElementSibling;
        }
    };
    star.onmouseout = () => {
        document.querySelectorAll('.star').forEach(s => {
            s.style.color = 'gray';
        });
        // Optionally re-highlight based on actual rating
    };
});
