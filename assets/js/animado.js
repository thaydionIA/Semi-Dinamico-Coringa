document.addEventListener("DOMContentLoaded", () => {
    const animatedHeadings = document.querySelectorAll('.h1-animado');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                entry.target.classList.remove('visible');
            }
        });
    }, {
        threshold: 0.1 // Aciona a animação quando 10% do elemento estiver visível
    });

    animatedHeadings.forEach(heading => observer.observe(heading));
});
