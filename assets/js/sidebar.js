document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.sidebar a');
    links.forEach(link => {
        link.addEventListener('click', () => {
            const isActive = link.classList.contains('active-link');
            links.forEach(l => l.classList.remove('active-link'));
            if (!isActive) {
                link.classList.add('active-link');
            }
        });
    });
});